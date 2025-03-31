<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get available templates
$settings = new Flexible_COD_Settings();
$templates = $settings->get_form_templates();
?>

<h3>Style Settings</h3>

<div class="fcod-style-tabs">
    <div class="fcod-style-tab-header">
        <button type="button" class="fcod-style-tab-button active" data-tab="presets">Templates</button>
        <button type="button" class="fcod-style-tab-button" data-tab="colors">Colors</button>
        <button type="button" class="fcod-style-tab-button" data-tab="spacing">Spacing & Borders</button>
        <button type="button" class="fcod-style-tab-button" data-tab="typography">Typography</button>
        <button type="button" class="fcod-style-tab-button" data-tab="effects">Effects & Animation</button>
    </div>
    
    <!-- Templates Tab -->
    <div class="fcod-style-tab-content" id="fcod-tab-presets">
        <h4>Choose a Template</h4>
        <p>Select a pre-made template to quickly style your form. You can customize further using the other tabs.</p>
        
        <div class="fcod-templates-grid">
            <?php foreach ($templates as $template_id => $template): ?>
                <div class="fcod-template-card" data-template="<?php echo esc_attr($template_id); ?>">
                    <div class="fcod-template-preview" style="background-color: <?php echo esc_attr($template['styles']['bg_color']); ?>; border-color: <?php echo esc_attr($template['styles']['form_border_color']); ?>;">
                        <div class="fcod-template-form-preview">
                            <div class="fcod-preview-label" style="color: <?php echo esc_attr($template['styles']['label_color']); ?>;">Field Label</div>
                            <div class="fcod-preview-input" style="background-color: <?php echo esc_attr($template['styles']['input_bg_color']); ?>; border-color: <?php echo esc_attr($template['styles']['border_color']); ?>;"></div>
                            
                            <div class="fcod-preview-button" style="background-color: <?php echo esc_attr($template['styles']['btn_color']); ?>; color: <?php echo esc_attr($template['styles']['btn_text_color']); ?>;"></div>
                        </div>
                    </div>
                    <div class="fcod-template-info">
                        <h4><?php echo esc_html($template['name']); ?></h4>
                        <button type="button" class="button fcod-apply-template" data-template="<?php echo esc_attr($template_id); ?>">Apply</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Colors Tab -->
    <div class="fcod-style-tab-content" id="fcod-tab-colors" style="display: none;">
        <h4>Color Settings</h4>
        
        <table class="form-table">
            <tr>
                <th>Form Background</th>
                <td><input type="color" name="fcod_form_bg_color" value="<?php echo esc_attr(get_option('fcod_form_bg_color', '#ffffff')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Input Background</th>
                <td><input type="color" name="fcod_form_input_bg_color" value="<?php echo esc_attr(get_option('fcod_form_input_bg_color', '#ffffff')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Input Text Color</th>
                <td><input type="color" name="fcod_form_input_text_color" value="<?php echo esc_attr(get_option('fcod_form_input_text_color', '#333333')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Label Color</th>
                <td><input type="color" name="fcod_form_label_color" value="<?php echo esc_attr(get_option('fcod_form_label_color', '#000000')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Border Color</th>
                <td><input type="color" name="fcod_form_border_color" value="<?php echo esc_attr(get_option('fcod_form_border_color', '#dddddd')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Button Color</th>
                <td><input type="color" name="fcod_form_btn_color" value="<?php echo esc_attr(get_option('fcod_form_btn_color', '#27ae60')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Button Text Color</th>
                <td><input type="color" name="fcod_form_btn_text_color" value="<?php echo esc_attr(get_option('fcod_form_btn_text_color', '#ffffff')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Error Color</th>
                <td><input type="color" name="fcod_form_error_color" value="<?php echo esc_attr(get_option('fcod_form_error_color', '#dc3232')); ?>" class="fcod-color-picker"></td>
            </tr>
            <tr>
                <th>Form Border Color</th>
                <td><input type="color" name="fcod_form_form_border_color" value="<?php echo esc_attr(get_option('fcod_form_form_border_color', '#dddddd')); ?>" class="fcod-color-picker"></td>
            </tr>
        </table>
    </div>
    
    <!-- Spacing & Borders Tab -->
    <div class="fcod-style-tab-content" id="fcod-tab-spacing" style="display: none;">
        <h4>Spacing & Borders</h4>
        
        <table class="form-table">
            <tr>
                <th>Form Padding (px)</th>
                <td>
                    <input type="number" name="fcod_form_padding" value="<?php echo esc_attr(get_option('fcod_form_padding', '25')); ?>" min="0" max="50" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Space Between Fields (px)</th>
                <td>
                    <input type="number" name="fcod_form_field_spacing" value="<?php echo esc_attr(get_option('fcod_form_field_spacing', '20')); ?>" min="0" max="50" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Input Padding</th>
                <td>
                    <label>
                        Vertical:
                        <input type="number" name="fcod_form_input_padding_vertical" value="<?php echo esc_attr(get_option('fcod_form_input_padding_vertical', '12')); ?>" min="0" max="30" class="small-text">px
                    </label>
                    <label style="margin-left: 15px;">
                        Horizontal:
                        <input type="number" name="fcod_form_input_padding_horizontal" value="<?php echo esc_attr(get_option('fcod_form_input_padding_horizontal', '15')); ?>" min="0" max="30" class="small-text">px
                    </label>
                </td>
            </tr>
            <tr>
                <th>Border Width (px)</th>
                <td>
                    <input type="number" name="fcod_form_border_width" value="<?php echo esc_attr(get_option('fcod_form_border_width', '1')); ?>" min="0" max="10" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Border Radius (px)</th>
                <td>
                    <input type="number" name="fcod_form_border_radius" value="<?php echo esc_attr(get_option('fcod_form_border_radius', '6')); ?>" min="0" max="50" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Border Style</th>
                <td>
                    <select name="fcod_form_border_style">
                        <option value="solid" <?php selected(get_option('fcod_form_border_style', 'solid'), 'solid'); ?>>Solid</option>
                        <option value="dashed" <?php selected(get_option('fcod_form_border_style', 'solid'), 'dashed'); ?>>Dashed</option>
                        <option value="dotted" <?php selected(get_option('fcod_form_border_style', 'solid'), 'dotted'); ?>>Dotted</option>
                        <option value="double" <?php selected(get_option('fcod_form_border_style', 'solid'), 'double'); ?>>Double</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Form Border Width (px)</th>
                <td>
                    <input type="number" name="fcod_form_form_border_width" value="<?php echo esc_attr(get_option('fcod_form_form_border_width', '1')); ?>" min="0" max="10" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Form Border Radius (px)</th>
                <td>
                    <input type="number" name="fcod_form_form_border_radius" value="<?php echo esc_attr(get_option('fcod_form_form_border_radius', '8')); ?>" min="0" max="50" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Box Shadow</th>
                <td>
                    <select name="fcod_form_box_shadow">
                        <option value="none" <?php selected(get_option('fcod_form_box_shadow', 'none'), 'none'); ?>>None</option>
                        <option value="0 2px 5px rgba(0,0,0,0.1)" <?php selected(get_option('fcod_form_box_shadow', 'none'), '0 2px 5px rgba(0,0,0,0.1)'); ?>>Light</option>
                        <option value="0 4px 12px rgba(0,0,0,0.1)" <?php selected(get_option('fcod_form_box_shadow', 'none'), '0 4px 12px rgba(0,0,0,0.1)'); ?>>Medium</option>
                        <option value="0 8px 20px rgba(0,0,0,0.15)" <?php selected(get_option('fcod_form_box_shadow', 'none'), '0 8px 20px rgba(0,0,0,0.15)'); ?>>Strong</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Typography Tab -->
    <div class="fcod-style-tab-content" id="fcod-tab-typography" style="display: none;">
        <h4>Typography</h4>
        
        <table class="form-table">
            <tr>
                <th>Input Text Size (px)</th>
                <td>
                    <input type="number" name="fcod_form_input_font_size" value="<?php echo esc_attr(get_option('fcod_form_input_font_size', '16')); ?>" min="12" max="24" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Label Text Size (px)</th>
                <td>
                    <input type="number" name="fcod_form_label_font_size" value="<?php echo esc_attr(get_option('fcod_form_label_font_size', '14')); ?>" min="10" max="20" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Button Text Size (px)</th>
                <td>
                    <input type="number" name="fcod_form_button_font_size" value="<?php echo esc_attr(get_option('fcod_form_button_font_size', '16')); ?>" min="12" max="24" class="small-text">
                </td>
            </tr>
            <tr>
                <th>Font Family</th>
                <td>
                    <select name="fcod_form_font_family">
                        <option value="inherit" <?php selected(get_option('fcod_form_font_family', 'inherit'), 'inherit'); ?>>Theme Default</option>
                        <option value="Arial, sans-serif" <?php selected(get_option('fcod_form_font_family', 'inherit'), 'Arial, sans-serif'); ?>>Arial</option>
                        <option value="'Helvetica Neue', Helvetica, sans-serif" <?php selected(get_option('fcod_form_font_family', 'inherit'), "'Helvetica Neue', Helvetica, sans-serif"); ?>>Helvetica</option>
                        <option value="'Segoe UI', Tahoma, sans-serif" <?php selected(get_option('fcod_form_font_family', 'inherit'), "'Segoe UI', Tahoma, sans-serif"); ?>>Segoe UI</option>
                        <option value="'Roboto', sans-serif" <?php selected(get_option('fcod_form_font_family', 'inherit'), "'Roboto', sans-serif"); ?>>Roboto</option>
                        <option value="'Cairo', sans-serif" <?php selected(get_option('fcod_form_font_family', 'inherit'), "'Cairo', sans-serif"); ?>>Cairo (Arabic Support)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Text Direction</th>
                <td>
                    <select name="fcod_form_text_direction">
                        <option value="rtl" <?php selected(get_option('fcod_form_text_direction', 'rtl'), 'rtl'); ?>>Right to Left (RTL)</option>
                        <option value="ltr" <?php selected(get_option('fcod_form_text_direction', 'rtl'), 'ltr'); ?>>Left to Right (LTR)</option>
                        <option value="auto" <?php selected(get_option('fcod_form_text_direction', 'rtl'), 'auto'); ?>>Auto</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Effects & Animation Tab -->
    <div class="fcod-style-tab-content" id="fcod-tab-effects" style="display: none;">
        <h4>Effects & Animation</h4>
        
        <table class="form-table">
            <tr>
                <th>Quantity Controls</th>
                <td>
                    <label>
                        <input type="checkbox" 
                            name="fcod_form_quantity_controls_enabled"
                            value="1"
                            <?php checked(get_option('fcod_form_quantity_controls_enabled', '1'), '1'); ?>>
                        Enable +/- quantity controls
                    </label>
                </td>
            </tr>
            <tr>
                <th>Button Animation</th>
                <td>
                    <select name="fcod_form_button_animation">
                        <option value="none" <?php selected(get_option('fcod_form_button_animation', 'none'), 'none'); ?>>None</option>
                        <option value="shake" <?php selected(get_option('fcod_form_button_animation', 'none'), 'shake'); ?>>Shake</option>
                        <option value="pulse" <?php selected(get_option('fcod_form_button_animation', 'none'), 'pulse'); ?>>Pulse</option>
                        <option value="glow" <?php selected(get_option('fcod_form_button_animation', 'none'), 'glow'); ?>>Glow</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Input Focus Effect</th>
                <td>
                    <select name="fcod_form_input_focus_effect">
                        <option value="border" <?php selected(get_option('fcod_form_input_focus_effect', 'border'), 'border'); ?>>Border Highlight</option>
                        <option value="glow" <?php selected(get_option('fcod_form_input_focus_effect', 'border'), 'glow'); ?>>Glow Effect</option>
                        <option value="scale" <?php selected(get_option('fcod_form_input_focus_effect', 'border'), 'scale'); ?>>Subtle Scale</option>
                        <option value="none" <?php selected(get_option('fcod_form_input_focus_effect', 'border'), 'none'); ?>>None</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Form Transitions</th>
                <td>
                    <select name="fcod_form_transitions">
                        <option value="fast" <?php selected(get_option('fcod_form_transitions', 'medium'), 'fast'); ?>>Fast</option>
                        <option value="medium" <?php selected(get_option('fcod_form_transitions', 'medium'), 'medium'); ?>>Medium</option>
                        <option value="slow" <?php selected(get_option('fcod_form_transitions', 'medium'), 'slow'); ?>>Slow</option>
                        <option value="none" <?php selected(get_option('fcod_form_transitions', 'medium'), 'none'); ?>>None</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.fcod-style-tab-button').on('click', function() {
        var tab = $(this).data('tab');
        
        // Update active tab button
        $('.fcod-style-tab-button').removeClass('active');
        $(this).addClass('active');
        
        // Show selected tab content
        $('.fcod-style-tab-content').hide();
        $('#fcod-tab-' + tab).show();
    });
    
    // Template selection
    $('.fcod-apply-template').on('click', function() {
        var templateId = $(this).data('template');
        
        if (confirm('Are you sure you want to apply this template? This will overwrite your current style settings.')) {
            // Show loading overlay
            $('<div class="fcod-loading-overlay"><span>Applying template...</span></div>').css({
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                background: 'rgba(255,255,255,0.8)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                zIndex: 9999,
                fontSize: '18px',
                fontWeight: 'bold'
            }).appendTo('body');
            
            // Send AJAX request to apply template
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'fcod_apply_template',
                    template_id: templateId,
                    nonce: '<?php echo wp_create_nonce('fcod_apply_template'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page to show updated settings
                        location.reload();
                    } else {
                        alert('Error applying template: ' + response.data);
                        $('.fcod-loading-overlay').remove();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr, status, error);
                    alert('An error occurred while applying the template. Check console for details.');
                    $('.fcod-loading-overlay').remove();
                }
            });
        }
    });
});
</script>