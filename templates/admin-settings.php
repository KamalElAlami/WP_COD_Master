<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h2>COD Form Settings</h2>
    
    <h2 class="nav-tab-wrapper">
        <a href="#style-settings" class="nav-tab nav-tab-active">Style Settings</a>
        <a href="#field-settings" class="nav-tab">Form Fields</a>
        <a href="#telegram-settings" class="nav-tab">Telegram Settings</a>
    </h2>
    
    <form method="post" action="options.php">
        <?php settings_fields('cod-form-settings-group'); ?>
        
        <div id="style-settings" class="tab-content">
            <?php include SCF_PLUGIN_DIR . 'templates/style-settings.php'; ?>
        </div>
        
        <div id="field-settings" class="tab-content" style="display:none;">
            <?php include SCF_PLUGIN_DIR . 'templates/field-settings.php'; ?>
        </div>
        
        <div id="telegram-settings" class="tab-content" style="display:none;">
            <?php include SCF_PLUGIN_DIR . 'templates/telegram-settings.php'; ?>
        </div>
        
        <?php submit_button(); ?>
    </form>
</div>