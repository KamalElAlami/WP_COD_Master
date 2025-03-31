<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get saved custom fields or use defaults
$custom_fields = get_option('cod_form_custom_fields');

// If no fields are set or not an array, use defaults
if (empty($custom_fields) || !is_array($custom_fields)) {
    $custom_fields = array(
        array(
            'label' => 'Full Name',
            'type' => 'text',
            'required' => true,
            'enabled' => true
        ),
        array(
            'label' => 'Phone',
            'type' => 'tel',
            'required' => true,
            'enabled' => true
        ),
        array(
            'label' => 'Address',
            'type' => 'textarea',
            'required' => true,
            'enabled' => true
        )
    );
}
?>

<h3>Form Fields</h3>
<div id="custom-fields">
    <?php foreach($custom_fields as $index => $field): 
        $field = wp_parse_args($field, array(
            'label' => '',
            'type' => 'text',
            'required' => true,
            'enabled' => true
        ));
    ?>
    <div class="custom-field">
        <input type="text" 
               name="cod_form_custom_fields[<?php echo $index; ?>][label]" 
               value="<?php echo esc_attr($field['label']); ?>" 
               placeholder="Field Label">
               
        <select name="cod_form_custom_fields[<?php echo $index; ?>][type]">
            <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
            <option value="tel" <?php selected($field['type'], 'tel'); ?>>Phone</option>
            <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
            <option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Textarea</option>
        </select>
        
        <label>
            <input type="checkbox" 
                   name="cod_form_custom_fields[<?php echo $index; ?>][required]"
                   <?php checked($field['required'], true); ?>> Required
        </label>
        
        <label>
            <input type="checkbox" 
                   name="cod_form_custom_fields[<?php echo $index; ?>][enabled]"
                   <?php checked($field['enabled'], true); ?>> Enabled
        </label>
        
        <button type="button" class="remove-field">Remove</button>
    </div>
    <?php endforeach; ?>
</div>
<button type="button" id="add-field" class="button button-secondary">Add New Field</button>

<style>
.custom-field {
    background: #fff;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.custom-field input[type="text"] {
    width: 200px;
    margin-right: 10px;
}

.custom-field select {
    min-width: 120px;
    margin-right: 10px;
}

.custom-field label {
    margin-right: 15px;
}

.remove-field {
    color: #dc3232;
    border: 1px solid #dc3232;
    background: transparent;
    padding: 3px 8px;
    border-radius: 3px;
    cursor: pointer;
}

.remove-field:hover {
    background: #dc3232;
    color: #fff;
}

#add-field {
    margin-top: 10px;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Add new field
    $('#add-field').on('click', function() {
        var index = $('.custom-field').length;
        var newField = `
                <div class="custom-field">
                    <input type="text" 
                        name="cod_form_custom_fields[<?php echo $index; ?>][label]" 
                        value="<?php echo esc_attr($field['label']); ?>" 
                        placeholder="Field Label">
                
                    <select name="cod_form_custom_fields[<?php echo $index; ?>][type]">
                        <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                        <option value="tel" <?php selected($field['type'], 'tel'); ?>>Phone</option>
                        <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                        <option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Textarea</option>
                    </select>
                    
                    <label>
                        <input type="checkbox" 
                            name="cod_form_custom_fields[<?php echo $index; ?>][required]"
                            <?php checked($field['required'], true); ?>> Required
                    </label>
                    
                    <label>
                        <input type="checkbox" 
                            name="cod_form_custom_fields[<?php echo $index; ?>][enabled]"
                            value="1"
                            <?php checked($field['enabled'], true); ?>> Enabled
                    </label>
                    
                    <button type="button" class="remove-field">Remove</button>
                    
                    <!-- Add this hidden field to ensure disabled fields are still submitted -->
                    <input type="hidden" name="cod_form_custom_fields[<?php echo $index; ?>][field_exists]" value="1">
        </div>
        `;
        $('#custom-fields').append(newField);
    });
    
    // Remove field
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.custom-field').remove();
    });
});
</script>