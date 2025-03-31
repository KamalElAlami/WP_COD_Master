<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get all styling options
$bg_color = get_option('cod_form_bg_color', '#ffffff');
$btn_color = get_option('cod_form_btn_color', '#333333');
$label_color = get_option('cod_form_label_color', '#000000');
$border_color = get_option('cod_form_border_color', '#dddddd');
$border_width = get_option('cod_form_border_width', '1');
$input_padding = get_option('cod_form_input_padding', '8');

// Get saved custom fields or use defaults if not set
$custom_fields = get_option('cod_form_custom_fields');
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

<div class="cod-form-wrapper">
    <form id="simple-cod-form" method="POST">
        <?php wp_nonce_field('process_cod_form', 'cod_nonce'); ?>
        <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
        
        <?php if ($product->get_type() == 'variable'): ?>
            <div class="form-row variations-select">
                <?php 
                $attributes = $product->get_variation_attributes();
                foreach($attributes as $attribute_name => $options) {
                    ?>
                    <label for="<?php echo sanitize_title($attribute_name); ?>">
                        <?php echo wc_attribute_label($attribute_name); ?>
                    </label>
                    <select name="attribute_<?php echo sanitize_title($attribute_name); ?>" required>
                        <option value="">Choose an option</option>
                        <?php foreach($options as $option): ?>
                            <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php
                }
                ?>
            </div>
        <?php endif; ?>
        
        <?php
        foreach($custom_fields as $field):
            // Skip if field is not enabled or required key is not set
            if(!isset($field['enabled']) || !$field['enabled']) continue;
            
            // Set default values if not set
            $field = wp_parse_args($field, array(
                'label' => '',
                'type' => 'text',
                'required' => false,
                'enabled' => true
            ));
            
            $field_id = sanitize_title($field['label']);
        ?>
            <div class="form-row">
                <label for="cod_<?php echo esc_attr($field_id); ?>">
                    <?php echo esc_html($field['label']); ?>
                    <?php echo $field['required'] ? '*' : ''; ?>
                </label>
                
                <?php if($field['type'] === 'textarea'): ?>
                    <textarea 
                        name="cod_<?php echo esc_attr($field_id); ?>" 
                        id="cod_<?php echo esc_attr($field_id); ?>"
                        <?php echo $field['required'] ? 'required' : ''; ?>
                    ></textarea>
                <?php else: ?>
                    <input 
                        type="<?php echo esc_attr($field['type']); ?>"
                        name="cod_<?php echo esc_attr($field_id); ?>"
                        id="cod_<?php echo esc_attr($field_id); ?>"
                        <?php echo $field['required'] ? 'required' : ''; ?>
                    >
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <?php if (get_option('cod_form_quantity_controls_enabled', '0') === '1'): ?>
            <div class="form-row">
                <label for="cod_quantity">Quantity *</label>
                <div class="quantity-control">
                    <button type="button" class="quantity-btn minus">-</button>
                    <input type="number" name="cod_quantity" id="cod_quantity" class="quantity-input" min="1" value="1" required>
                    <button type="button" class="quantity-btn plus">+</button>
                </div>
            </div>
        <?php else: ?>
            <div class="form-row">
                <label for="cod_quantity">Quantity *</label>
                <input type="number" name="cod_quantity" id="cod_quantity" min="1" value="1" required>
            </div>
        <?php endif; ?>
        
        <button type="submit" class="cod-submit-btn">Order Now - Cash on Delivery</button>
    </form>
</div>

<style>
      /* Form border (new) */
      .cod-form-wrapper {
        background-color: <?php echo esc_attr(get_option('cod_form_bg_color', '#ffffff')); ?>;
        border: <?php echo esc_attr(get_option('cod_form_border_width', '1')); ?>px 
                <?php echo esc_attr(get_option('cod_form_border_style', 'solid')); ?> 
                <?php echo esc_attr(get_option('cod_form_border_color', '#dddddd')); ?>;
        border-radius: <?php echo esc_attr(get_option('cod_form_border_radius', '4')); ?>px;
        padding: <?php echo esc_attr(get_option('cod_form_padding', '20')); ?>px;
    }
    
    /* Button animations */
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }
    
    <?php if (get_option('cod_form_button_animation') === 'shake'): ?>
    .cod-submit-btn:hover {
        animation: shake 0.5s ease-in-out;
    }
    <?php endif; ?>
    
    /* Quantity controls styling */
    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .quantity-btn {
        width: 40px;
        height: 40px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
    }
    
    .quantity-input {
        width: 60px;
        text-align: center;
        margin: 0 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        height: 40px;
    }
    
    .form-row {
        margin-bottom: <?php echo esc_attr(get_option('cod_form_field_spacing', '15')); ?>px;
    }
    
    .form-row:last-child {
        margin-bottom: 0;
    }
    
    .form-row label {
        display: block;
        margin-bottom: 5px;
        color: <?php echo esc_attr(get_option('cod_form_label_color', '#000000')); ?>;
        font-size: <?php echo esc_attr(get_option('cod_form_label_font_size', '14')); ?>px;
    }
    
    .form-row input,
    .form-row textarea,
    .form-row select {
        width: 100%;
        background-color: <?php echo esc_attr(get_option('cod_form_input_bg_color', '#ffffff')); ?>;
        color: <?php echo esc_attr(get_option('cod_form_input_text_color', '#333333')); ?>;
        font-size: <?php echo esc_attr(get_option('cod_form_input_font_size', '16')); ?>px;
        border: <?php echo esc_attr(get_option('cod_form_border_width', '1')); ?>px 
                <?php echo esc_attr(get_option('cod_form_border_style', 'solid')); ?> 
                <?php echo esc_attr(get_option('cod_form_border_color', '#dddddd')); ?>;
        border-radius: <?php echo esc_attr(get_option('cod_form_border_radius', '4')); ?>px;
        padding: 8px 12px;
    }
    
    .form-row input:focus,
    .form-row textarea:focus,
    .form-row select:focus {
        outline: none;
        border-color: <?php echo esc_attr(get_option('cod_form_btn_color', '#333333')); ?>;
    }
    
    .cod-submit-btn {
        background: <?php echo esc_attr(get_option('cod_form_btn_color', '#333333')); ?>;
        color: <?php echo esc_attr(get_option('cod_form_btn_text_color', '#ffffff')); ?>;
        font-size: <?php echo esc_attr(get_option('cod_form_button_font_size', '16')); ?>px;
        border: none;
        border-radius: <?php echo esc_attr(get_option('cod_form_border_radius', '4')); ?>px;
        cursor: pointer;
        width: 100%;
        padding: 12px;
    }
    
    .cod-submit-btn:hover {
        opacity: 0.9;
    }

    .form-row .error {
        color: <?php echo esc_attr(get_option('cod_form_error_color', '#dc3232')); ?>;
        font-size: 12px;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .cod-form-wrapper {
            padding: <?php echo esc_attr(get_option('cod_form_padding', '20')/1.5); ?>px;
        }
        
        .form-row {
            margin-bottom: <?php echo esc_attr(get_option('cod_form_field_spacing', '15')/1.5); ?>px;
        }
    }
</style>

<?php if ($product->get_type() == 'variable'): ?>
    <script>
jQuery(document).ready(function($) {
    // Quantity buttons functionality
    $('.quantity-btn.plus').click(function() {
        var $input = $(this).siblings('.quantity-input');
        var currentVal = parseInt($input.val());
        $input.val(currentVal + 1).trigger('change');
    });
    
    $('.quantity-btn.minus').click(function() {
        var $input = $(this).siblings('.quantity-input');
        var currentVal = parseInt($input.val());
        if (currentVal > 1) {
            $input.val(currentVal - 1).trigger('change');
        }
    });
    
    <?php if ($product->get_type() == 'variable'): ?>
    // Variation handling
    $('.variations-select select').on('change', function() {
        var selectedValues = {};
        var allSelected = true;
        
        $('.variations-select select').each(function() {
            var value = $(this).val();
            if (!value) {
                allSelected = false;
            }
            selectedValues[$(this).attr('name')] = value;
        });
        
        if (allSelected) {
            // Any additional code for when all variations are selected
        }
    });
    <?php endif; ?>
});
</script>
<?php endif; ?>