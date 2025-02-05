<?php
class COD_Form_Handler {
    public function __construct() {
        add_action('init', array($this, 'handle_form_submission'));
        add_action('woocommerce_single_product_summary', array($this, 'display_form'), 30);
        
        // Remove default WooCommerce elements
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    }

    public function display_form() {
        global $product;
        include SCF_PLUGIN_DIR . 'templates/form-template.php';
    }

    public function handle_form_submission() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['cod_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['cod_nonce'], 'process_cod_form')) {
            wp_die('Invalid request');
        }

        // Create WooCommerce order
        $order = wc_create_order();
        
        // Add product to order
        $product_id = sanitize_text_field($_POST['product_id']);
        $quantity = sanitize_text_field($_POST['cod_quantity']);
        $product = wc_get_product($product_id);
        
        if ($product->get_type() == 'variable') {
            $variation_id = $this->get_variation_id($product, $_POST);
            $variation = new WC_Product_Variation($variation_id);
            $order->add_product($variation, $quantity);
        } else {
            $order->add_product($product, $quantity);
        }
        
        // Add customer details from custom fields
        $custom_fields = get_option('cod_form_custom_fields');
        
        // Prepare billing/shipping address
        $address = array(
            'first_name' => '',
            'last_name'  => '',
            'company'    => '',
            'email'      => '',
            'phone'      => '',
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'state'      => '',
            'postcode'   => '',
            'country'    => 'MA'  // Default to Morocco
        );

        // Map custom fields to address fields
        foreach ($custom_fields as $field) {
            if (!isset($field['enabled']) || !$field['enabled']) {
                continue;
            }

            $field_id = sanitize_title($field['label']);
            $field_value = isset($_POST['cod_' . $field_id]) ? sanitize_text_field($_POST['cod_' . $field_id]) : '';

            // Map fields based on label
            switch (strtolower($field['label'])) {
                case 'full name':
                    $name_parts = explode(' ', $field_value, 2);
                    $address['first_name'] = $name_parts[0];
                    $address['last_name'] = isset($name_parts[1]) ? $name_parts[1] : '';
                    break;
                case 'phone':
                    $address['phone'] = $field_value;
                    break;
                case 'address':
                    $address['address_1'] = $field_value;
                    break;
                case 'email':
                    $address['email'] = $field_value;
                    break;
                case 'city':
                    $address['city'] = $field_value;
                    break;
                // Add more field mappings as needed
            }
        }

        // Set addresses
        $order->set_address($address, 'billing');
        $order->set_address($address, 'shipping');
        
        // Set payment method
        $order->set_payment_method('cod');
        $order->set_payment_method_title('Cash on Delivery');
        
        // Set order status and save
        $order->set_status('processing');
        $order->calculate_totals();
        $order->save();
        
        // Send Telegram notification
        do_action('cod_form_order_created', $order);
        
        // Redirect to thank you page
        wp_safe_redirect($order->get_checkout_order_received_url());
        exit;
    }

    private function get_variation_id($product, $posted_data) {
        $variation_id = 0;
        $variations = $product->get_available_variations();
        
        foreach ($variations as $variation) {
            $attributes_match = true;
            
            foreach ($variation['attributes'] as $attribute_name => $attribute_value) {
                $posted_value = isset($posted_data['attribute_' . $attribute_name]) ? 
                    sanitize_title($posted_data['attribute_' . $attribute_name]) : '';
                
                if ($posted_value !== $attribute_value) {
                    $attributes_match = false;
                    break;
                }
            }
            
            if ($attributes_match) {
                $variation_id = $variation['variation_id'];
                break;
            }
        }
        
        return $variation_id;
    }
}