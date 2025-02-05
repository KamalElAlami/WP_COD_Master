<?php
/*
Plugin Name: Simple COD Form
Description: Adds a customizable Cash on Delivery form to product pages
Version: 1.0.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SCF_VERSION', '1.0.0');
define('SCF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SCF_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SCF_PLUGIN_DIR . 'includes/class-cod-form-settings.php';
require_once SCF_PLUGIN_DIR . 'includes/class-cod-form-handler.php';
require_once SCF_PLUGIN_DIR . 'includes/class-telegram-notification.php';

// Initialize the plugin
function scf_init() {
    new COD_Form_Settings();
    new COD_Form_Handler();
    new Telegram_Notification();
}
add_action('plugins_loaded', 'scf_init');

// Enqueue admin scripts and styles
function scf_admin_enqueue_scripts() {
    wp_enqueue_style('scf-admin-style', SCF_PLUGIN_URL . 'assets/css/admin-style.css', array(), SCF_VERSION);
    wp_enqueue_script('scf-admin-script', SCF_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery'), SCF_VERSION, true);
}
add_action('admin_enqueue_scripts', 'scf_admin_enqueue_scripts');

// File: includes/class-cod-form-settings.php

// File: includes/class-cod-form-handler.php

// File: includes/class-telegram-notification.php

// File: assets/css/admin-style.css

// File: assets/js/admin-script.js


// File: templates/admin-settings.php


// File: templates/form-template.php


// File: templates/style-settings.php

// File: templates/field-settings.php


// File: templates/telegram-settings.php
