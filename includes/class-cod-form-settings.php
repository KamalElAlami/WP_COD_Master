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
        register_setting('cod-form-settings-group', 'cod_form_custom_fields');
        
        // Telegram settings
        register_setting('cod-form-settings-group', 'cod_form_telegram_enabled');
        register_setting('cod-form-settings-group', 'cod_form_telegram_bot_token');
        register_setting('cod-form-settings-group', 'cod_form_telegram_chat_id');
        register_setting('cod-form-settings-group', 'cod_form_telegram_message_template');
    }
    public function settings_page() {
        include SCF_PLUGIN_DIR . 'templates/admin-settings.php';
    }
}
