<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<h3>Style Settings</h3>
<table class="form-table">
    <!-- Colors Section -->
    <tr>
        <th colspan="2">
            <h4 style="margin: 0;">Colors</h4>
        </th>
    </tr>
    <tr>
        <th>Form Background</th>
        <td><input type="color" name="cod_form_bg_color" value="<?php echo esc_attr(get_option('cod_form_bg_color', '#ffffff')); ?>"></td>
    </tr>
    <tr>
        <th>Input Background</th>
        <td><input type="color" name="cod_form_input_bg_color" value="<?php echo esc_attr(get_option('cod_form_input_bg_color', '#ffffff')); ?>"></td>
    </tr>
    <tr>
        <th>Input Text Color</th>
        <td><input type="color" name="cod_form_input_text_color" value="<?php echo esc_attr(get_option('cod_form_input_text_color', '#333333')); ?>"></td>
    </tr>
    <tr>
        <th>Label Color</th>
        <td><input type="color" name="cod_form_label_color" value="<?php echo esc_attr(get_option('cod_form_label_color', '#000000')); ?>"></td>
    </tr>
    <tr>
        <th>Border Color</th>
        <td><input type="color" name="cod_form_border_color" value="<?php echo esc_attr(get_option('cod_form_border_color', '#dddddd')); ?>"></td>
    </tr>
    <tr>
        <th>Button Color</th>
        <td><input type="color" name="cod_form_btn_color" value="<?php echo esc_attr(get_option('cod_form_btn_color', '#333333')); ?>"></td>
    </tr>
    <tr>
        <th>Button Text Color</th>
        <td><input type="color" name="cod_form_btn_text_color" value="<?php echo esc_attr(get_option('cod_form_btn_text_color', '#ffffff')); ?>"></td>
    </tr>
    <tr>
        <th>Error Color</th>
        <td><input type="color" name="cod_form_error_color" value="<?php echo esc_attr(get_option('cod_form_error_color', '#dc3232')); ?>"></td>
    </tr>

    <!-- Spacing Section -->
    <tr>
        <th colspan="2">
            <h4 style="margin: 15px 0 0;">Spacing</h4>
        </th>
    </tr>
    <tr>
        <th>Form Padding (px)</th>
        <td><input type="number" name="cod_form_padding" value="<?php echo esc_attr(get_option('cod_form_padding', '20')); ?>" min="0" max="50"></td>
    </tr>
    <tr>
        <th>Space Between Fields (px)</th>
        <td><input type="number" name="cod_form_field_spacing" value="<?php echo esc_attr(get_option('cod_form_field_spacing', '15')); ?>" min="0" max="50"></td>
    </tr>

    <!-- Borders Section -->
    <tr>
        <th colspan="2">
            <h4 style="margin: 15px 0 0;">Borders</h4>
        </th>
    </tr>
    <tr>
        <th>Border Width (px)</th>
        <td><input type="number" name="cod_form_border_width" value="<?php echo esc_attr(get_option('cod_form_border_width', '1')); ?>" min="0" max="10"></td>
    </tr>
    <tr>
        <th>Border Radius (px)</th>
        <td><input type="number" name="cod_form_border_radius" value="<?php echo esc_attr(get_option('cod_form_border_radius', '4')); ?>" min="0" max="50"></td>
    </tr>
    <tr>
        <th>Border Style</th>
        <td>
            <select name="cod_form_border_style">
                <option value="solid" <?php selected(get_option('cod_form_border_style', 'solid'), 'solid'); ?>>Solid</option>
                <option value="dashed" <?php selected(get_option('cod_form_border_style', 'solid'), 'dashed'); ?>>Dashed</option>
                <option value="dotted" <?php selected(get_option('cod_form_border_style', 'solid'), 'dotted'); ?>>Dotted</option>
            </select>
        </td>
    </tr>

    <!-- Text Sizes Section -->
    <tr>
        <th colspan="2">
            <h4 style="margin: 15px 0 0;">Text Sizes</h4>
        </th>
    </tr>
    <tr>
        <th>Input Text Size (px)</th>
        <td><input type="number" name="cod_form_input_font_size" value="<?php echo esc_attr(get_option('cod_form_input_font_size', '16')); ?>" min="12" max="24"></td>
    </tr>
    <tr>
        <th>Label Text Size (px)</th>
        <td><input type="number" name="cod_form_label_font_size" value="<?php echo esc_attr(get_option('cod_form_label_font_size', '14')); ?>" min="10" max="20"></td>
    </tr>
    <tr>
        <th>Button Text Size (px)</th>
        <td><input type="number" name="cod_form_button_font_size" value="<?php echo esc_attr(get_option('cod_form_button_font_size', '16')); ?>" min="12" max="24"></td>
    </tr>
    <!-- Add this to your existing style settings -->
    <tr>
        <th colspan="2">
            <h4 style="margin: 15px 0 0;">Form Border</h4>
        </th>
    </tr>
    <tr>
        <th>Form Border Color</th>
        <td><input type="color" name="cod_form_form_border_color" value="<?php echo esc_attr(get_option('cod_form_form_border_color', '#dddddd')); ?>"></td>
    </tr>
    <tr>
        <th>Form Border Width (px)</th>
        <td><input type="number" name="cod_form_form_border_width" value="<?php echo esc_attr(get_option('cod_form_form_border_width', '1')); ?>" min="0" max="10"></td>
    </tr>

    <tr>
        <th colspan="2">
            <h4 style="margin: 15px 0 0;">Button Effects</h4>
        </th>
    </tr>
    <tr>
        <th>Button Animation</th>
        <td>
            <select name="cod_form_button_animation">
                <option value="none" <?php selected(get_option('cod_form_button_animation', 'none'), 'none'); ?>>None</option>
                <option value="shake" <?php selected(get_option('cod_form_button_animation', 'none'), 'shake'); ?>>Shake</option>
                <option value="pulse" <?php selected(get_option('cod_form_button_animation', 'none'), 'pulse'); ?>>Pulse</option>
                <option value="glow" <?php selected(get_option('cod_form_button_animation', 'none'), 'glow'); ?>>Glow</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Quantity Controls</th>
        <td>
            <input type="checkbox" 
                name="cod_form_quantity_controls_enabled"
                value="1"
                <?php checked(get_option('cod_form_quantity_controls_enabled', '0'), '1'); ?>>
            Enable +/- quantity controls
        </td>
    </tr>
</table>