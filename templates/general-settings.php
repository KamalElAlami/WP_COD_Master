<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get available templates
$settings = new Flexible_COD_Settings();
$templates = $settings->get_form_templates();

// Get WooCommerce order statuses
$order_statuses = wc_get_order_statuses();

// Get all pages for thank you page selection
$pages = get_pages();
?>

<h3>General Settings</h3>
<table class="form-table">
    <tr>
        <th>Form Title</th>
        <td>
            <input type="text" 
                   name="fcod_form_title" 
                   value="<?php echo esc_attr(get_option('fcod_form_title', 'أدخل معلوماتك للطلب')); ?>" 
                   class="regular-text">
            <p class="description">The title that appears at the top of the form</p>
        </td>
    </tr>
    
    <tr>
        <th>Order Button Text</th>
        <td>
            <input type="text" 
                   name="fcod_button_text" 
                   value="<?php echo esc_attr(get_option('fcod_button_text', 'اشتري الآن - J\'achète')); ?>" 
                   class="regular-text">
            <p class="description">Text that appears on the order submission button</p>
        </td>
    </tr>
    
    <tr>
        <th>Default Template</th>
        <td>
            <select name="fcod_default_template">
                <?php foreach ($templates as $template_id => $template): ?>
                    <option value="<?php echo esc_attr($template_id); ?>" <?php selected(get_option('fcod_default_template', 'default'), $template_id); ?>>
                        <?php echo esc_html($template['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description">Default template to use when none is specified</p>
        </td>
    </tr>
    
    <tr>
        <th>Default Country</th>
        <td>
            <select name="fcod_default_country">
                <?php 
                $countries = WC()->countries->get_countries();
                foreach ($countries as $code => $name): 
                ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php selected(get_option('fcod_default_country', 'MA'), $code); ?>>
                        <?php echo esc_html($name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description">Default country for orders</p>
        </td>
    </tr>
    
    <tr>
        <th>Initial Order Status</th>
        <td>
            <select name="fcod_initial_order_status">
                <?php foreach ($order_statuses as $status => $label): 
                    $status_code = str_replace('wc-', '', $status);
                ?>
                    <option value="<?php echo esc_attr($status_code); ?>" <?php selected(get_option('fcod_initial_order_status', 'processing'), $status_code); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description">Initial status for new orders</p>
        </td>
    </tr>
    
    <tr>
        <th>Payment Method Title</th>
        <td>
            <input type="text" 
                   name="fcod_payment_method_title" 
                   value="<?php echo esc_attr(get_option('fcod_payment_method_title', 'Cash on Delivery')); ?>" 
                   class="regular-text">
            <p class="description">Payment method title that appears in order details</p>
        </td>
    </tr>
    
    <tr>
        <th>Thank You Page</th>
        <td>
            <select name="fcod_thank_you_page">
                <option value="">Default WooCommerce Thank You Page</option>
                <?php foreach ($pages as $page): ?>
                    <option value="<?php echo esc_attr(get_permalink($page->ID)); ?>" <?php selected(get_option('fcod_thank_you_page', ''), get_permalink($page->ID)); ?>>
                        <?php echo esc_html($page->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description">Page to redirect to after successful order</p>
        </td>
    </tr>
    
    <tr>
        <th>Replace WooCommerce Checkout</th>
        <td>
            <label>
                <input type="checkbox" 
                       name="fcod_replace_woocommerce" 
                       value="yes" 
                       <?php checked(get_option('fcod_replace_woocommerce', 'no'), 'yes'); ?>>
                Replace Add to Cart button on product pages with this form
            </label>
        </td>
    </tr>
    
    <tr>
        <th>Hide Product Price</th>
        <td>
            <label>
                <input type="checkbox" 
                       name="fcod_hide_price" 
                       value="yes" 
                       <?php checked(get_option('fcod_hide_price', 'no'), 'yes'); ?>>
                Hide product price on product pages when the form is displayed
            </label>
        </td>
    </tr>
    
    <tr>
        <th>Display Product Rating</th>
        <td>
            <label>
                <input type="checkbox" 
                       name="fcod_show_rating" 
                       value="1" 
                       <?php checked(get_option('fcod_show_rating', '1'), '1'); ?>>
                Show star rating in the form
            </label>
        </td>
    </tr>
    
    <tr>
        <th>Rating Count</th>
        <td>
            <input type="number" 
                   name="fcod_rating_count" 
                   value="<?php echo esc_attr(get_option('fcod_rating_count', '5')); ?>" 
                   min="1" max="1000" class="small-text">
            <p class="description">Number of customer reviews to display (for marketing purposes)</p>
        </td>
    </tr>
</table>