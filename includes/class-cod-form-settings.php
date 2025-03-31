<?php

class COD_Form_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_menu_page() {
        add_menu_page(
            'COD Form Settings',
            'COD Form',
            'manage_options',
            'cod-form-settings',
            array($this, 'settings_page'),
            'dashicons-format-aside'
        );
    }
    public function get_form_templates() {
        return array(
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
                    'button_animation' => 'none'
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
                    'field_spacing' => '15',
                    'input_font_size' => '16',
                    'label_font_size' => '15',
                    'button_font_size' => '16',
                    'error_color' => '#e53935',
                    'form_border_color' => '#27ae60', // Green border for the entire form
                    'form_border_width' => '2',
                    'form_border_radius' => '8',
                    'quantity_controls_enabled' => '1', // Enable +/- quantity controls
                    'button_animation' => 'shake' // Button animation type
                )
            )
            // You can add more templates here
        );
    }
	public function register_settings() {
        // Colors
        register_setting('cod-form-settings-group', 'cod_form_bg_color');
        register_setting('cod-form-settings-group', 'cod_form_input_bg_color');
        register_setting('cod-form-settings-group', 'cod_form_input_text_color');
        register_setting('cod-form-settings-group', 'cod_form_label_color');
        register_setting('cod-form-settings-group', 'cod_form_border_color');
        register_setting('cod-form-settings-group', 'cod_form_btn_color');
        register_setting('cod-form-settings-group', 'cod_form_btn_text_color');
        register_setting('cod-form-settings-group', 'cod_form_error_color');

        // Spacing
        register_setting('cod-form-settings-group', 'cod_form_padding');
        register_setting('cod-form-settings-group', 'cod_form_field_spacing');

        // Borders
        register_setting('cod-form-settings-group', 'cod_form_border_width');
        register_setting('cod-form-settings-group', 'cod_form_border_radius');
        register_setting('cod-form-settings-group', 'cod_form_border_style');

        // Text Sizes
        register_setting('cod-form-settings-group', 'cod_form_input_font_size');
        register_setting('cod-form-settings-group', 'cod_form_label_font_size');
        register_setting('cod-form-settings-group', 'cod_form_button_font_size');
        
        // Custom fields settings
        // register_setting('cod-form-settings-group', 'cod_form_custom_fields');
        register_setting('cod-form-settings-group', 'cod_form_custom_fields', array($this, 'sanitize_custom_fields'));
        
        // Telegram settings
        register_setting('cod-form-settings-group', 'cod_form_telegram_enabled');
        register_setting('cod-form-settings-group', 'cod_form_telegram_bot_token');
        register_setting('cod-form-settings-group', 'cod_form_telegram_chat_id');
        register_setting('cod-form-settings-group', 'cod_form_telegram_message_template');

        register_setting('cod-form-settings-group', 'cod_form_form_border_color');
        register_setting('cod-form-settings-group', 'cod_form_form_border_width');
        register_setting('cod-form-settings-group', 'cod_form_button_animation');
        register_setting('cod-form-settings-group', 'cod_form_quantity_controls_enabled');
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
                'enabled' => isset($field['enabled']) ? true : false
            );
        }
        
        return $sanitized_fields;
    }
    public function settings_page() {
        include SCF_PLUGIN_DIR . 'templates/admin-settings.php';
    }
}
