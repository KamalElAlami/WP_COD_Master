<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get saved custom fields or use defaults
$custom_fields = get_option('fcod_form_custom_fields');

// If no fields are set or not an array, use defaults
if (empty($custom_fields) || !is_array($custom_fields)) {
    $custom_fields = array(
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
?>

<h3>Form Fields</h3>
<p>Define your custom form fields below. Drag to reorder.</p>

<div id="fcod-fields-container">
    <?php foreach($custom_fields as $index => $field): 
        $field = wp_parse_args($field, array(
            'label' => '',
            'type' => 'text',
            'required' => true,
            'enabled' => true,
            'placeholder' => '',
            'width' => '100%',
            'icon' => ''
        ));
    ?>
    <div class="fcod-field-item" data-index="<?php echo esc_attr($index); ?>">
        <div class="fcod-field-header">
            <span class="fcod-field-drag dashicons dashicons-menu"></span>
            <span class="fcod-field-title"><?php echo esc_html($field['label']); ?></span>
            <span class="fcod-field-toggle dashicons dashicons-arrow-down"></span>
        </div>
        
        <div class="fcod-field-settings">
            <div class="fcod-field-row">
                <label>Field Label</label>
                <input type="text" 
                       name="fcod_form_custom_fields[<?php echo $index; ?>][label]" 
                       value="<?php echo esc_attr($field['label']); ?>" 
                       placeholder="Field Label" class="fcod-field-label-input">
            </div>
            
            <div class="fcod-field-row">
                <label>Field Type</label>
                <select name="fcod_form_custom_fields[<?php echo $index; ?>][type]" class="fcod-field-type">
                    <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                    <option value="tel" <?php selected($field['type'], 'tel'); ?>>Phone</option>
                    <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                    <option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Textarea</option>
                    <option value="select" <?php selected($field['type'], 'select'); ?>>Dropdown</option>
                </select>
            </div>
            
            <div class="fcod-field-row">
                <label>Placeholder</label>
                <input type="text" 
                       name="fcod_form_custom_fields[<?php echo $index; ?>][placeholder]" 
                       value="<?php echo esc_attr($field['placeholder']); ?>" 
                       placeholder="Placeholder text">
            </div>
            
            <div class="fcod-field-row">
                <label>Width</label>
                <select name="fcod_form_custom_fields[<?php echo $index; ?>][width]">
                    <option value="100%" <?php selected($field['width'], '100%'); ?>>Full Width</option>
                    <option value="48%" <?php selected($field['width'], '48%'); ?>>Half Width</option>
                    <option value="31%" <?php selected($field['width'], '31%'); ?>>One Third</option>
                </select>
            </div>
            
            <div class="fcod-field-row">
                <label>Icon (emoji or text)</label>
                <input type="text" 
                       name="fcod_form_custom_fields[<?php echo $index; ?>][icon]" 
                       value="<?php echo esc_attr($field['icon']); ?>" 
                       placeholder="Optional icon">
            </div>
            
            <div class="fcod-field-row fcod-field-options">
                <label>
                    <input type="checkbox" 
                           name="fcod_form_custom_fields[<?php echo $index; ?>][required]"
                           <?php checked($field['required'], true); ?>> Required
                </label>
                
                <label>
                    <input type="checkbox" 
                           name="fcod_form_custom_fields[<?php echo $index; ?>][enabled]"
                           <?php checked($field['enabled'], true); ?>> Enabled
                </label>
                
                <button type="button" class="button button-link-delete fcod-remove-field">Remove</button>
            </div>
            
            <!-- Hidden field to ensure disabled fields are still submitted -->
            <input type="hidden" name="fcod_form_custom_fields[<?php echo $index; ?>][field_exists]" value="1">
        </div>
    </div>
    <?php endforeach; ?>
</div>

<button type="button" id="fcod-add-field" class="button button-primary">Add New Field</button>

<div id="fcod-field-template" style="display: none;">
    <div class="fcod-field-item" data-index="{{index}}">
        <div class="fcod-field-header">
            <span class="fcod-field-drag dashicons dashicons-menu"></span>
            <span class="fcod-field-title">New Field</span>
            <span class="fcod-field-toggle dashicons dashicons-arrow-down"></span>
        </div>
        
        <div class="fcod-field-settings">
            <div class="fcod-field-row">
                <label>Field Label</label>
                <input type="text" 
                       name="fcod_form_custom_fields[{{index}}][label]" 
                       value="" 
                       placeholder="Field Label" class="fcod-field-label-input">
            </div>
            
            <div class="fcod-field-row">
                <label>Field Type</label>
                <select name="fcod_form_custom_fields[{{index}}][type]" class="fcod-field-type">
                    <option value="text">Text</option>
                    <option value="tel">Phone</option>
                    <option value="email">Email</option>
                    <option value="textarea">Textarea</option>
                    <option value="select">Dropdown</option>
                </select>
            </div>
            
            <div class="fcod-field-row">
                <label>Placeholder</label>
                <input type="text" 
                       name="fcod_form_custom_fields[{{index}}][placeholder]" 
                       value="" 
                       placeholder="Placeholder text">
            </div>
            
            <div class="fcod-field-row">
                <label>Width</label>
                <select name="fcod_form_custom_fields[{{index}}][width]">
                    <option value="100%">Full Width</option>
                    <option value="48%">Half Width</option>
                    <option value="31%">One Third</option>
                </select>
            </div>
            
            <div class="fcod-field-row">
                <label>Icon (emoji or text)</label>
                <input type="text" 
                       name="fcod_form_custom_fields[{{index}}][icon]" 
                       value="" 
                       placeholder="Optional icon">
            </div>
            
            <div class="fcod-field-row fcod-field-options">
                <label>
                    <input type="checkbox" 
                           name="fcod_form_custom_fields[{{index}}][required]"
                           checked> Required
                </label>
                
                <label>
                    <input type="checkbox" 
                           name="fcod_form_custom_fields[{{index}}][enabled]"
                           checked> Enabled
                </label>
                
                <button type="button" class="button button-link-delete fcod-remove-field">Remove</button>
            </div>
            
            <!-- Hidden field to ensure disabled fields are still submitted -->
            <input type="hidden" name="fcod_form_custom_fields[{{index}}][field_exists]" value="1">
        </div>
    </div>
</div>

<style>
.fcod-field-item {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 10px;
    overflow: hidden;
}

.fcod-field-header {
    padding: 12px 15px;
    background: #f9f9f9;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
    cursor: pointer;
}

.fcod-field-drag {
    margin-right: 10px;
    cursor: move;
    color: #777;
}

.fcod-field-title {
    flex: 1;
    font-weight: 600;
}

.fcod-field-toggle {
    color: #777;
    transition: transform 0.3s ease;
}

.fcod-field-item.closed .fcod-field-toggle {
    transform: rotate(-90deg);
}

.fcod-field-settings {
    padding: 15px;
    border-top: 1px solid #eee;
    display: none;
}

.fcod-field-item.closed .fcod-field-settings {
    display: none;
}

.fcod-field-row {
    margin-bottom: 12px;
}

.fcod-field-row:last-child {
    margin-bottom: 0;
}

.fcod-field-row label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.fcod-field-row input[type="text"],
.fcod-field-row select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.fcod-field-options {
    display: flex;
    align-items: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.fcod-field-options label {
    margin-right: 15px;
    display: inline-flex;
    align-items: center;
}

.fcod-field-options input[type="checkbox"] {
    margin-right: 5px;
}

.fcod-remove-field {
    margin-left: auto;
}

#fcod-add-field {
    margin-top: 15px;
}
</style>