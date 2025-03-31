<?php
class Flexible_COD_Handler {
    public function __construct() {
        add_action('init', array($this, 'handle_form_submission'));
        
        // Option to replace WooCommerce checkout on product pages
        if (get_option('fcod_replace_woocommerce', 'no') === 'yes') {
            add_action('woocommerce_single_product_summary', array($this, 'display_product_form'), 30);
            
            // Remove default WooCommerce elements
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            if (get_option('fcod_hide_price', 'no') === 'yes') {
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            }
        }
    }

    public function display_product_form() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        $this->display_form(array(
            'product_id' => $product->get_id(),
            'template' => get_option('fcod_default_template', 'default'),
        ));
    }

    public function display_form($attributes) {
        // Extract attributes
        $product_id = intval($attributes['product_id']);
        $template = sanitize_text_field($attributes['template']);
        $custom_title = !empty($attributes['title']) ? sanitize_text_field($attributes['title']) : '';
        $button_text = !empty($attributes['button_text']) ? sanitize_text_field($attributes['button_text']) : '';
        
        // Get product information if product_id is provided
        $product = null;
        if ($product_id > 0) {
            $product = wc_get_product($product_id);
        }
        
        // If we have a product, use its information
        $product_name = '';
        $product_price = '';
        $product_image = '';
        
        if ($product) {
            $product_name = $product->get_name();
            $product_price = $product->get_price_html();
            
            // Get product image
            $image_id = $product->get_image_id();
            if ($image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                $product_image = $image_url ? '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product_name) . '" class="fcod-product-image">' : '';
            }
        }
        
        // Get custom fields
        $custom_fields = get_option('fcod_form_custom_fields');
        if (empty($custom_fields) || !is_array($custom_fields)) {
            $custom_fields = $this->get_default_fields();
        }
        
        // Get template settings
        $settings = new Flexible_COD_Settings();
        $templates = $settings->get_form_templates();
        $template_data = isset($templates[$template]) ? $templates[$template] : $templates['default'];
        
        // Set form title
        $form_title = !empty($custom_title) ? $custom_title : get_option('fcod_form_title', 'أدخل معلوماتك للطلب');
        
        // Set button text
        $submit_button_text = !empty($button_text) ? $button_text : get_option('fcod_button_text', 'اشتري الآن - J\'achète');
        
        // Include the form template
        include FCOD_PLUGIN_DIR . 'templates/form-template.php';
    }

    public function handle_form_submission() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['fcod_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['fcod_nonce'], 'process_fcod_form')) {
            wp_die('Invalid request');
        }

        // Create WooCommerce order
        $order = wc_create_order();
        
        // Add product to order if product_id is set
        if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
            $product_id = sanitize_text_field($_POST['product_id']);
            $quantity = isset($_POST['fcod_quantity']) ? sanitize_text_field($_POST['fcod_quantity']) : 1;
            $product = wc_get_product($product_id);
            
            if (!$product) {
                wp_die('Invalid product');
            }
            
            if ($product->get_type() == 'variable' && isset($_POST['variation_id'])) {
                $variation_id = sanitize_text_field($_POST['variation_id']);
                $variation = new WC_Product_Variation($variation_id);
                $order->add_product($variation, $quantity);
            } else {
                $order->add_product($product, $quantity);
            }
        } else {
            // If no product is specified, possibly handle custom amount
            $custom_amount = isset($_POST['fcod_custom_amount']) ? floatval($_POST['fcod_custom_amount']) : 0;
            
            if ($custom_amount <= 0) {
                wp_die('Invalid amount');
            }
            
            // Create a custom fee
            $item_fee = new WC_Order_Item_Fee();
            $item_fee->set_name('Custom Order');
            $item_fee->set_amount($custom_amount);
            $item_fee->set_total($custom_amount);
            $order->add_item($item_fee);
        }
        
        // Add customer details from custom fields
        $custom_fields = get_option('fcod_form_custom_fields');
        if (empty($custom_fields) || !is_array($custom_fields)) {
            $custom_fields = $this->get_default_fields();
        }
        
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
            'country'    => get_option('fcod_default_country', 'MA')
        );

        // Map custom fields to address fields
        foreach ($custom_fields as $field) {
            if (!isset($field['enabled']) || !$field['enabled']) {
                continue;
            }

            $field_id = sanitize_title($field['label']);
            $field_value = isset($_POST['fcod_' . $field_id]) ? sanitize_text_field($_POST['fcod_' . $field_id]) : '';

            // Map fields based on label or type
            $label_lower = strtolower($field['label']);
            
            if (strpos($label_lower, 'full name') !== false || strpos($label_lower, 'name') !== false || 
                strpos($label_lower, 'الاسم الكامل') !== false || strpos($label_lower, 'اسم') !== false) {
                $name_parts = explode(' ', $field_value, 2);
                $address['first_name'] = $name_parts[0];
                $address['last_name'] = isset($name_parts[1]) ? $name_parts[1] : '';
            } 
            elseif (strpos($label_lower, 'phone') !== false || strpos($label_lower, 'الهاتف') !== false || 
                    strpos($label_lower, 'رقم') !== false || $field['type'] == 'tel') {
                $address['phone'] = $field_value;
            }
            elseif (strpos($label_lower, 'address') !== false || strpos($label_lower, 'العنوان') !== false) {
                $address['address_1'] = $field_value;
            }
            elseif (strpos($label_lower, 'email') !== false || strpos($label_lower, 'البريد') !== false || 
                    $field['type'] == 'email') {
                $address['email'] = $field_value;
            }
            elseif (strpos($label_lower, 'city') !== false || strpos($label_lower, 'مدينة') !== false) {
                $address['city'] = $field_value;
            }
            
            // Store all fields as order meta for reference
            $order->update_meta_data('_fcod_field_' . $field_id, $field_value);
        }

        // Set addresses
        $order->set_address($address, 'billing');
        $order->set_address($address, 'shipping');
        
        // Set payment method
        $order->set_payment_method('cod');
        $order->set_payment_method_title(get_option('fcod_payment_method_title', 'Cash on Delivery'));
        
        // Set order status and save
        $order->set_status(get_option('fcod_initial_order_status', 'processing'));
        $order->calculate_totals();
        $order->save();
        
        // Send Telegram notification
        do_action('fcod_order_created', $order);
        
        // Redirect to thank you page
        $redirect_url = get_option('fcod_thank_you_page', '');
        if (empty($redirect_url)) {
            $redirect_url = $order->get_checkout_order_received_url();
        }
        
        wp_safe_redirect($redirect_url);
        exit;
    }
    
    private function get_default_fields() {
        return array(
            array(
                'label' => 'الإسم الكامل',
                'type' => 'text',
                'required' => true,
                'enabled' => true,
                'placeholder' => 'أدخل الإسم الكامل',
                'width' => '100%',
                'icon' => '👤'
            ),
            array(
                'label' => 'رقم الهاتف',
                'type' => 'tel',
                'required' => true,
                'enabled' => true,
                'placeholder' => 'أدخل رقم الهاتف',
                'width' => '100%',
                'icon' => '📱'
            ),
            array(
                'label' => 'العنوان',
                'type' => 'textarea',
                'required' => true,
                'enabled' => true,
                'placeholder' => 'أدخل العنوان الكامل',
                'width' => '100%',
                'icon' => '🏠'
            )
        );
    }
}