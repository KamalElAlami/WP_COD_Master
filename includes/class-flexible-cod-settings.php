<?php
class Flexible_COD_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_menu_page() {
        add_menu_page(
            'Flexible COD Settings',
            'Flexible COD',
            'manage_options',
            'flexible-cod-settings',
            array($this, 'settings_page'),
            'dashicons-cart',
            30
        );
    }

    public function get_form_templates() {
        $default_templates = array(
            'default' => array(
                'name' => 'Default',
                'styles' => array(
                    'bg_color' => '#ffffff',
                    'input_bg_color' => '#ffffff',
                    'input_text_color' => '#333333',
                    'label_color' => '#000000',
                    'border_color' => '#dddddd',
                    'border_width' => '1',
                    'border_radius' => '4',
                    'border_style' => 'solid',
                    'btn_color' => '#333333',
                    'btn_text_color' => '#ffffff',
                    'padding' => '20',
                    'field_spacing' => '15',
                    'input_font_size' => '16',
                    'label_font_size' => '14',
                    'button_font_size' => '16',
                    'error_color' => '#dc3232',
                    'form_border_color' => '#dddddd',
                    'form_border_width' => '1',
                    'form_border_radius' => '4',
                    'quantity_controls_enabled' => '0',
                    'button_animation' => 'none',
                    'input_padding_vertical' => '12',
                    'input_padding_horizontal' => '15',
                )
            ),
            'smartlook' => array(
                'name' => 'SmartLook',
                'styles' => array(
                    'bg_color' => '#ffffff',
                    'input_bg_color' => '#ffffff',
                    'input_text_color' => '#333333',
                    'label_color' => '#333333',
                    'border_color' => '#e0e0e0',
                    'border_width' => '1',
                    'border_radius' => '8',
                    'border_style' => 'solid',
                    'btn_color' => '#27ae60', // Green button color
                    'btn_text_color' => '#ffffff',
                    'padding' => '25',
                    'field_spacing' => '20',
                    'input_font_size' => '16',
                    'label_font_size' => '15',
                    'button_font_size' => '16',
                    'error_color' => '#e53935',
                    'form_border_color' => '#27ae60', // Green border for the entire form
                    'form_border_width' => '2',
                    'form_border_radius' => '8',
                    'quantity_controls_enabled' => '1', // Enable +/- quantity controls
                    'button_animation' => 'shake', // Button animation type
                    'input_padding_vertical' => '15',
                    'input_padding_horizontal' => '18',
                )
            ),
            'clean' => array(
                'name' => 'Clean Modern',
                'styles' => array(
                    'bg_color' => '#f9f9f9',
                    'input_bg_color' => '#ffffff',
                    'input_text_color' => '#333333',
                    'label_color' => '#555555',
                    'border_color' => '#eeeeee',
                    'border_width' => '1',
                    'border_radius' => '6',
                    'border_style' => 'solid',
                    'btn_color' => '#3498db', // Blue button
                    'btn_text_color' => '#ffffff',
                    'padding' => '30',
                    'field_spacing' => '20',
                    'input_font_size' => '16',
                    'label_font_size' => '14',
                    'button_font_size' => '16',
                    'error_color' => '#e74c3c',
                    'form_border_color' => '#eeeeee',
                    'form_border_width' => '0',
                    'form_border_radius' => '10',
                    'quantity_controls_enabled' => '1',
                    'button_animation' => 'pulse',
                    'input_padding_vertical' => '14',
                    'input_padding_horizontal' => '16',
                    'form_box_shadow' => '0 4px 12px rgba(0,0,0,0.1)',
                )
            )
        );
        
        // Allow for custom templates via filter
        return apply_filters('fcod_form_templates', $default_templates);
    }

    public function register_settings() {
        // General Options
        register_setting('fcod-general-settings', 'fcod_replace_woocommerce');
        register_setting('fcod-general-settings', 'fcod_hide_price');
        register_setting('fcod-general-settings', 'fcod_default_template');
        register_setting('fcod-general-settings', 'fcod_form_title');
        register_setting('fcod-general-settings', 'fcod_button_text');
        register_setting('fcod-general-settings', 'fcod_thank_you_page');
        register_setting('fcod-general-settings', 'fcod_default_country');
        register_setting('fcod-general-settings', 'fcod_payment_method_title');
        register_setting('fcod-general-settings', 'fcod_initial_order_status');
        
        // Style Settings
        register_setting('fcod-style-settings', 'fcod_form_bg_color');
        register_setting('fcod-style-settings', 'fcod_form_input_bg_color');
        register_setting('fcod-style-settings', 'fcod_form_input_text_color');
        register_setting('fcod-style-settings', 'fcod_form_label_color');
        register_setting('fcod-style-settings', 'fcod_form_border_color');
        register_setting('fcod-style-settings', 'fcod_form_btn_color');
        register_setting('fcod-style-settings', 'fcod_form_btn_text_color');
        register_setting('fcod-style-settings', 'fcod_form_error_color');
        register_setting('fcod-style-settings', 'fcod_form_padding');
        register_setting('fcod-style-settings', 'fcod_form_field_spacing');
        register_setting('fcod-style-settings', 'fcod_form_border_width');
        register_setting('fcod-style-settings', 'fcod_form_border_radius');
        register_setting('fcod-style-settings', 'fcod_form_border_style');
        register_setting('fcod-style-settings', 'fcod_form_input_font_size');
        register_setting('fcod-style-settings', 'fcod_form_label_font_size');
        register_setting('fcod-style-settings', 'fcod_form_button_font_size');
        register_setting('fcod-style-settings', 'fcod_form_form_border_color');
        register_setting('fcod-style-settings', 'fcod_form_form_border_width');
        register_setting('fcod-style-settings', 'fcod_form_button_animation');
        register_setting('fcod-style-settings', 'fcod_form_quantity_controls_enabled');
        register_setting('fcod-style-settings', 'fcod_form_input_padding_vertical');
        register_setting('fcod-style-settings', 'fcod_form_input_padding_horizontal');
        register_setting('fcod-style-settings', 'fcod_form_box_shadow');
        
        // Form Fields
        register_setting('fcod-field-settings', 'fcod_form_custom_fields', array($this, 'sanitize_custom_fields'));
        
        // Telegram Settings
        register_setting('fcod-telegram-settings', 'fcod_form_telegram_enabled');
        register_setting('fcod-telegram-settings', 'fcod_form_telegram_bot_token');
        register_setting('fcod-telegram-settings', 'fcod_form_telegram_chat_id');
        register_setting('fcod-telegram-settings', 'fcod_form_telegram_message_template');
    }

    public function sanitize_custom_fields($fields) {
        $sanitized_fields = array();
        
        if (!is_array($fields)) {
            return $sanitized_fields;
        }
        
        foreach ($fields as $index => $field) {
            // If field exists but enabled isn't checked, set it to false explicitly
            if (isset($field['field_exists']) && !isset($field['enabled'])) {
                $field['enabled'] = false;
            }
            
            $sanitized_fields[] = array(
                'label' => sanitize_text_field($field['label']),
                'type' => sanitize_text_field($field['type']),
                'required' => isset($field['required']) ? true : false,
                'enabled' => isset($field['enabled']) ? true : false,
                'placeholder' => isset($field['placeholder']) ? sanitize_text_field($field['placeholder']) : '',
                'width' => isset($field['width']) ? sanitize_text_field($field['width']) : '100%',
                'icon' => isset($field['icon']) ? sanitize_text_field($field['icon']) : '',
            );
        }
        
        return $sanitized_fields;
    }

    public function settings_page() {
        // Check current tab
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        
        ?>
        <div class="wrap">
            <h2>Flexible COD Form Settings</h2>
            
            <h2 class="nav-tab-wrapper">
                <a href="?page=flexible-cod-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General Settings</a>
                <a href="?page=flexible-cod-settings&tab=style" class="nav-tab <?php echo $active_tab == 'style' ? 'nav-tab-active' : ''; ?>">Style Settings</a>
                <a href="?page=flexible-cod-settings&tab=fields" class="nav-tab <?php echo $active_tab == 'fields' ? 'nav-tab-active' : ''; ?>">Form Fields</a>
                <a href="?page=flexible-cod-settings&tab=telegram" class="nav-tab <?php echo $active_tab == 'telegram' ? 'nav-tab-active' : ''; ?>">Telegram Settings</a>
                <a href="?page=flexible-cod-settings&tab=shortcode" class="nav-tab <?php echo $active_tab == 'shortcode' ? 'nav-tab-active' : ''; ?>">Shortcode Usage</a>
            </h2>
            
            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'general') {
                    settings_fields('fcod-general-settings');
                    include FCOD_PLUGIN_DIR . 'templates/general-settings.php';
                } elseif ($active_tab == 'style') {
                    settings_fields('fcod-style-settings');
                    include FCOD_PLUGIN_DIR . 'templates/style-settings.php';
                } elseif ($active_tab == 'fields') {
                    settings_fields('fcod-field-settings');
                    include FCOD_PLUGIN_DIR . 'templates/field-settings.php';
                } elseif ($active_tab == 'telegram') {
                    settings_fields('fcod-telegram-settings');
                    include FCOD_PLUGIN_DIR . 'templates/telegram-settings.php';
                } elseif ($active_tab == 'shortcode') {
                    include FCOD_PLUGIN_DIR . 'templates/shortcode-help.php';
                }
                
                if ($active_tab != 'shortcode') {
                    submit_button();
                }
                ?>
            </form>
        </div>
        <?php
    }
}