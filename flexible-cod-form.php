<?php
/*
Plugin Name: Flexible COD Form
Description: Customizable Cash on Delivery form that can be added to any page with shortcode
Version: 1.0.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FCOD_VERSION', '1.0.0');
define('FCOD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FCOD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once FCOD_PLUGIN_DIR . 'includes/class-flexible-cod-settings.php';
require_once FCOD_PLUGIN_DIR . 'includes/class-flexible-cod-handler.php';
require_once FCOD_PLUGIN_DIR . 'includes/class-telegram-notification.php';


// Initialize the plugin
function fcod_init() {
    Flexible_COD_Settings::get_instance();
    new Flexible_COD_Handler();
    new Telegram_Notification();
}
add_action('plugins_loaded', 'fcod_init');

// Register shortcode for adding the form anywhere
function fcod_form_shortcode($atts) {
    $attributes = shortcode_atts(array(
        'product_id' => 0,
        'template' => 'default',
        'title' => '',
        'button_text' => '',
    ), $atts);
    
    ob_start();
    $handler = new Flexible_COD_Handler();
    $handler->display_form($attributes);
    return ob_get_clean();
}
add_shortcode('flexible_cod_form', 'fcod_form_shortcode');

// Add Gutenberg block for easier insertion
function fcod_register_block() {
    if (!function_exists('register_block_type')) {
        return;
    }
    
    wp_register_script(
        'fcod-block-editor',
        FCOD_PLUGIN_URL . 'assets/js/block.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor'),
        FCOD_VERSION
    );
    
    // Add data for the block editor
    $settings = Flexible_COD_Settings::get_instance(); // Use get_instance() instead of new
    $templates = $settings->get_form_templates();
    $templates_data = array();
    
    foreach ($templates as $id => $template) {
        $templates_data[] = array(
            'id' => $id,
            'name' => $template['name']
        );
    }
    
    // Get available products for the selector
    $products_data = array();
    $products = wc_get_products(array('limit' => 100));
    foreach ($products as $product) {
        $products_data[] = array(
            'id' => $product->get_id(),
            'title' => $product->get_name()
        );
    }
    
    wp_localize_script('fcod-block-editor', 'fcodBlockData', array(
        'templates' => $templates_data,
        'products' => $products_data
    ));
    
    register_block_type('flexible-cod/form-block', array(
        'editor_script' => 'fcod-block-editor',
        'render_callback' => 'fcod_form_shortcode',
        'attributes' => array(
            'product_id' => array('type' => 'number', 'default' => 0),
            'template' => array('type' => 'string', 'default' => 'default'),
            'title' => array('type' => 'string', 'default' => ''),
            'button_text' => array('type' => 'string', 'default' => ''),
        ),
    ));
}
add_action('init', 'fcod_register_block');

// Enqueue admin scripts and styles
function fcod_admin_enqueue_scripts($hook) {
    if ($hook !== 'toplevel_page_flexible-cod-settings') {
        return;
    }
    
    wp_enqueue_style('fcod-admin-style', FCOD_PLUGIN_URL . 'assets/css/admin-style.css', array(), FCOD_VERSION);
    wp_enqueue_script('fcod-admin-script', FCOD_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery', 'jquery-ui-sortable'), FCOD_VERSION, true);
    
    // Add color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    
    // Add media uploader
    wp_enqueue_media();
    
    // Localize script with our variables
    wp_localize_script('fcod-admin-script', 'fcod_admin_vars', array(
        'nonce' => wp_create_nonce('fcod_admin_nonce'),
        'apply_template_nonce' => wp_create_nonce('fcod_apply_template'),
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('admin_enqueue_scripts', 'fcod_admin_enqueue_scripts');

// Enqueue frontend styles
function fcod_enqueue_frontend_styles() {
    wp_enqueue_style('fcod-form-style', FCOD_PLUGIN_URL . 'assets/css/form-style.css', array(), FCOD_VERSION);
    wp_enqueue_script('fcod-form-script', FCOD_PLUGIN_URL . 'assets/js/form-script.js', array('jquery'), FCOD_VERSION, true);
}
add_action('wp_enqueue_scripts', 'fcod_enqueue_frontend_styles');

// Add settings link to plugins page
function fcod_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=flexible-cod-settings">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin_file = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_file", 'fcod_add_settings_link');

// AJAX handlers for admin
function fcod_admin_ajax_handlers() {
    // Apply template AJAX handler
    add_action('wp_ajax_fcod_apply_template', 'fcod_ajax_apply_template');
    
    // Test Telegram AJAX handler
    add_action('wp_ajax_fcod_test_telegram', 'fcod_ajax_test_telegram');
}
add_action('admin_init', 'fcod_admin_ajax_handlers');

// Apply template handler
function fcod_ajax_apply_template() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fcod_apply_template')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    // Get template ID
    $template_id = isset($_POST['template_id']) ? sanitize_text_field($_POST['template_id']) : '';
    if (empty($template_id)) {
        wp_send_json_error('No template selected');
        return;
    }
    
    // Get available templates
    $settings = new Flexible_COD_Settings();
    $templates = $settings->get_form_templates();
    
    // Check if template exists
    if (!isset($templates[$template_id])) {
        wp_send_json_error('Template not found');
        return;
    }
    
    // Get template styles
    $styles = $templates[$template_id]['styles'];
    
    // Update options with template styles
    foreach ($styles as $key => $value) {
        update_option('fcod_form_' . $key, $value);
    }
    
    wp_send_json_success();
}

// Test Telegram handler
function fcod_ajax_test_telegram() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fcod_admin_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $bot_token = isset($_POST['bot_token']) ? sanitize_text_field($_POST['bot_token']) : '';
    $chat_id = isset($_POST['chat_id']) ? sanitize_text_field($_POST['chat_id']) : '';
    $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : 'Test message from Flexible COD Form';
    
    if (empty($bot_token) || empty($chat_id)) {
        wp_send_json_error('Bot token and chat ID are required');
        return;
    }
    
    // Send test message
    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    $params = array(
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'Markdown'
    );
    
    $response = wp_remote_post($url, array(
        'body' => $params,
        'timeout' => 15
    ));
    
    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
        return;
    }
    
    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);
    
    if (isset($result['ok']) && $result['ok'] === true) {
        wp_send_json_success('Message sent successfully');
    } else {
        $error = isset($result['description']) ? $result['description'] : 'Unknown error';
        wp_send_json_error($error);
    }
}