<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get styling options from the template
$styles = $template_data['styles'];

// Extract common style variables
$bg_color = $styles['bg_color'] ?? '#ffffff';
$input_bg_color = $styles['input_bg_color'] ?? '#ffffff';
$input_text_color = $styles['input_text_color'] ?? '#333333';
$label_color = $styles['label_color'] ?? '#000000';
$border_color = $styles['border_color'] ?? '#dddddd';
$border_width = $styles['border_width'] ?? '1';
$border_radius = $styles['border_radius'] ?? '4';
$padding = $styles['padding'] ?? '20';
$field_spacing = $styles['field_spacing'] ?? '15';
$btn_color = $styles['btn_color'] ?? '#27ae60';
$btn_text_color = $styles['btn_text_color'] ?? '#ffffff';
$input_padding_v = $styles['input_padding_vertical'] ?? '12';
$input_padding_h = $styles['input_padding_horizontal'] ?? '15';
$box_shadow = $styles['form_box_shadow'] ?? 'none';

// Whether to show quantity controls (+/-)
$quantity_controls = $styles['quantity_controls_enabled'] ?? '1';

// Button animation type
$button_animation = $styles['button_animation'] ?? 'none';

// Check for product rating display
$show_rating = get_option('fcod_show_rating', '1');
$rating_count = get_option('fcod_rating_count', '5');
?>

<div class="fcod-form-wrapper">
    <?php if (!empty($form_title)): ?>
        <h2 class="fcod-form-title"><?php echo esc_html($form_title); ?></h2>
    <?php endif; ?>

    <?php if ($product): ?>
    <div class="fcod-product-info">
        <?php if (!empty($product_image)): ?>
            <div class="fcod-product-image-container">
                <?php echo $product_image; ?>
            </div>
        <?php endif; ?>
        
        <div class="fcod-product-details">
            <h3 class="fcod-product-title"><?php echo esc_html($product_name); ?></h3>
            
            <?php if ($show_rating == '1'): ?>
            <div class="fcod-product-rating">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <span class="fcod-star">★</span>
                <?php endfor; ?>
                <span class="fcod-rating-count">(<?php echo esc_html($rating_count); ?> customer reviews)</span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($product_price) && get_option('fcod_hide_price', 'no') !== 'yes'): ?>
                <div class="fcod-product-price"><?php echo $product_price; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <form id="flexible-cod-form" class="fcod-form" method="POST">
        <?php wp_nonce_field('process_fcod_form', 'fcod_nonce'); ?>
        
        <?php if ($product): ?>
            <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
            
            <?php if ($product->get_type() == 'variable'): ?>
                <div class="fcod-variations">
                    <?php 
                    $attributes = $product->get_variation_attributes();
                    foreach($attributes as $attribute_name => $options) {
                        ?>
                        <div class="fcod-form-row">
                            <label for="<?php echo sanitize_title($attribute_name); ?>">
                                <?php echo wc_attribute_label($attribute_name); ?>
                            </label>
                            <select name="attribute_<?php echo sanitize_title($attribute_name); ?>" 
                                   id="<?php echo sanitize_title($attribute_name); ?>" required>
                                <option value="">Choose an option</option>
                                <?php foreach($options as $option): ?>
                                    <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
                    <input type="hidden" name="variation_id" id="fcod-variation-id" value="">
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php
        // Display custom fields
        foreach($custom_fields as $field):
            // Skip if field is not enabled
            if(!isset($field['enabled']) || !$field['enabled']) continue;
            
            // Set default values if not set
            $field = wp_parse_args($field, array(
                'label' => '',
                'type' => 'text',
                'required' => false,
                'enabled' => true,
                'placeholder' => '',
                'width' => '100%',
                'icon' => ''
            ));
            
            $field_id = sanitize_title($field['label']);
            $placeholder = !empty($field['placeholder']) ? $field['placeholder'] : $field['label'];
        ?>
            <div class="fcod-form-row" style="width: <?php echo esc_attr($field['width']); ?>">
                <label for="fcod_<?php echo esc_attr($field_id); ?>">
                    <?php echo esc_html($field['label']); ?>
                    <?php echo $field['required'] ? ' *' : ''; ?>
                </label>
                
                <div class="fcod-input-wrapper<?php echo !empty($field['icon']) ? ' has-icon' : ''; ?>">
                    <?php if(!empty($field['icon'])): ?>
                        <span class="fcod-input-icon"><?php echo esc_html($field['icon']); ?></span>
                    <?php endif; ?>
                    
                    <?php if($field['type'] === 'textarea'): ?>
                        <textarea 
                            name="fcod_<?php echo esc_attr($field_id); ?>" 
                            id="fcod_<?php echo esc_attr($field_id); ?>"
                            placeholder="<?php echo esc_attr($placeholder); ?>"
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        ></textarea>
                    <?php else: ?>
                        <input 
                            type="<?php echo esc_attr($field['type']); ?>"
                            name="fcod_<?php echo esc_attr($field_id); ?>"
                            id="fcod_<?php echo esc_attr($field_id); ?>"
                            placeholder="<?php echo esc_attr($placeholder); ?>"
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        >
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="fcod-form-row fcod-quantity-row">
            <label for="fcod_quantity">Quantity *</label>
            
            <?php if ($quantity_controls == '1'): ?>
                <div class="fcod-quantity-control">
                    <button type="button" class="fcod-quantity-btn fcod-qty-minus">-</button>
                    <input type="number" name="fcod_quantity" id="fcod_quantity" class="fcod-quantity-input" min="1" value="1" required>
                    <button type="button" class="fcod-quantity-btn fcod-qty-plus">+</button>
                </div>
            <?php else: ?>
                <div class="fcod-input-wrapper">
                    <input type="number" name="fcod_quantity" id="fcod_quantity" min="1" value="1" required>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="fcod-form-row fcod-submit-row">
            <button type="submit" class="fcod-submit-btn">
                <?php echo esc_html($submit_button_text); ?>
            </button>
        </div>
    </form>
</div>

<style>
    .fcod-form-wrapper {
        max-width: 600px;
        margin: 0 auto;
        background-color: <?php echo esc_attr($bg_color); ?>;
        border: <?php echo esc_attr($styles['form_border_width'] ?? '1'); ?>px 
                <?php echo esc_attr($styles['border_style'] ?? 'solid'); ?> 
                <?php echo esc_attr($styles['form_border_color'] ?? '#dddddd'); ?>;
        border-radius: <?php echo esc_attr($styles['form_border_radius'] ?? '4'); ?>px;
        padding: <?php echo esc_attr($padding); ?>px;
        box-shadow: <?php echo esc_attr($box_shadow); ?>;
        direction: rtl; /* For Arabic text */
    }
    
    .fcod-form-title {
        text-align: center;
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 24px;
        color: <?php echo esc_attr($label_color); ?>;
    }
    
    .fcod-product-info {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 1px solid <?php echo esc_attr($border_color); ?>;
        padding-bottom: 20px;
    }
    
    .fcod-product-image-container {
        width: 100px;
        margin-left: 15px;
    }
    
    .fcod-product-image {
        max-width: 100%;
        height: auto;
        border-radius: <?php echo esc_attr($border_radius); ?>px;
    }
    
    .fcod-product-details {
        flex: 1;
    }
    
    .fcod-product-title {
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 18px;
    }
    
    .fcod-product-rating {
        margin-bottom: 8px;
    }
    
    .fcod-star {
        color: #FFD700;
        font-size: 18px;
    }
    
    .fcod-rating-count {
        color: #777;
        font-size: 14px;
        margin-right: 5px;
    }
    
    .fcod-product-price {
        font-size: 20px;
        font-weight: bold;
        color: <?php echo esc_attr($btn_color); ?>;
    }
    
    .fcod-form-row {
        margin-bottom: <?php echo esc_attr($field_spacing); ?>px;
    }
    
    .fcod-form-row label {
        display: block;
        margin-bottom: 5px;
        color: <?php echo esc_attr($label_color); ?>;
        font-size: <?php echo esc_attr($styles['label_font_size'] ?? '14'); ?>px;
    }
    
    .fcod-input-wrapper {
        position: relative;
    }
    
    .fcod-input-wrapper.has-icon input,
    .fcod-input-wrapper.has-icon textarea {
        padding-right: 40px;
    }
    
    .fcod-input-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }
    
    .fcod-form input[type="text"],
    .fcod-form input[type="tel"],
    .fcod-form input[type="email"],
    .fcod-form input[type="number"],
    .fcod-form textarea,
    .fcod-form select {
        width: 100%;
        background-color: <?php echo esc_attr($input_bg_color); ?>;
        color: <?php echo esc_attr($input_text_color); ?>;
        font-size: <?php echo esc_attr($styles['input_font_size'] ?? '16'); ?>px;
        border: <?php echo esc_attr($border_width); ?>px 
                <?php echo esc_attr($styles['border_style'] ?? 'solid'); ?> 
                <?php echo esc_attr($border_color); ?>;
        border-radius: <?php echo esc_attr($border_radius); ?>px;
        padding: <?php echo esc_attr($input_padding_v); ?>px <?php echo esc_attr($input_padding_h); ?>px;
        transition: border-color 0.3s ease;
    }
    
    .fcod-form input:focus,
    .fcod-form textarea:focus,
    .fcod-form select:focus {
        outline: none;
        border-color: <?php echo esc_attr($btn_color); ?>;
        box-shadow: 0 0 0 2px rgba(<?php 
            // Convert hex to rgb for box shadow
            $hex = ltrim($btn_color, '#');
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            echo "$r, $g, $b, 0.2";
        ?>);
    }
    
    .fcod-quantity-control {
        display: flex;
        align-items: center;
    }
    
    .fcod-quantity-btn {
        width: 40px;
        height: 40px;
        background: #f5f5f5;
        border: 1px solid <?php echo esc_attr($border_color); ?>;
        border-radius: <?php echo esc_attr($border_radius); ?>px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .fcod-quantity-btn:hover {
        background-color: #e9e9e9;
    }
    
    .fcod-quantity-input {
        width: 60px !important;
        text-align: center;
        margin: 0 10px;
        height: 40px;
    }
    
    .fcod-submit-btn {
        background: <?php echo esc_attr($btn_color); ?>;
        color: <?php echo esc_attr($btn_text_color); ?>;
        font-size: <?php echo esc_attr($styles['button_font_size'] ?? '16'); ?>px;
        border: none;
        border-radius: <?php echo esc_attr($border_radius); ?>px;
        cursor: pointer;
        width: 100%;
        padding: 15px;
        transition: opacity 0.3s ease;
        font-weight: bold;
    }
    
    .fcod-submit-btn:hover {
        opacity: 0.9;
    }
    
    /* Button animations */
    <?php if ($button_animation === 'shake'): ?>
    @keyframes fcod-shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }
    
    .fcod-submit-btn:hover {
        animation: fcod-shake 0.5s ease-in-out;
    }
    <?php elseif ($button_animation === 'pulse'): ?>
    @keyframes fcod-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .fcod-submit-btn:hover {
        animation: fcod-pulse 1s infinite;
    }
    <?php elseif ($button_animation === 'glow'): ?>
    @keyframes fcod-glow {
        0% { box-shadow: 0 0 5px rgba(<?php 
            echo "$r, $g, $b, 0.5";
        ?>); }
        50% { box-shadow: 0 0 20px rgba(<?php 
            echo "$r, $g, $b, 0.8";
        ?>); }
        100% { box-shadow: 0 0 5px rgba(<?php 
            echo "$r, $g, $b, 0.5";
        ?>); }
    }
    
    .fcod-submit-btn:hover {
        animation: fcod-glow 1.5s infinite;
    }
    <?php endif; ?>

    /* Responsive styles */
    @media (max-width: 768px) {
        .fcod-form-wrapper {
            padding: <?php echo esc_attr($padding / 1.5); ?>px;
        }
        
        .fcod-form-row {
            margin-bottom: <?php echo esc_attr($field_spacing / 1.2); ?>px;
        }
        
        .fcod-product-info {
            flex-direction: column;
            text-align: center;
        }
        
        .fcod-product-image-container {
            margin: 0 auto 15px;
        }
    }

    /* RTL support for Arabic */
    .fcod-form input,
    .fcod-form textarea,
    .fcod-form select,
    .fcod-form label {
        text-align: right;
    }
    
    /* For when form inputs need to be LTR (like phone numbers) */
    .fcod-form input[type="tel"],
    .fcod-form input[type="number"],
    .fcod-form input[type="email"] {
        direction: ltr;
        text-align: right;
    }
</style>

<script>
jQuery(document).ready(function($) {
    // Quantity buttons functionality
    $('.fcod-qty-plus').click(function() {
        var $input = $(this).siblings('.fcod-quantity-input');
        var currentVal = parseInt($input.val());
        $input.val(currentVal + 1).trigger('change');
    });
    
    $('.fcod-qty-minus').click(function() {
        var $input = $(this).siblings('.fcod-quantity-input');
        var currentVal = parseInt($input.val());
        if (currentVal > 1) {
            $input.val(currentVal - 1).trigger('change');
        }
    });
    
    <?php if ($product && $product->get_type() == 'variable'): ?>
    // Variation handling
    var variationData = <?php echo wp_json_encode($product->get_available_variations()); ?>;
    
    $('.fcod-variations select').on('change', function() {
        var selectedValues = {};
        var allSelected = true;
        
        $('.fcod-variations select').each(function() {
            var value = $(this).val();
            if (!value) {
                allSelected = false;
            }
            selectedValues[$(this).attr('name')] = value;
        });
        
        if (allSelected) {
            // Find matching variation
            for (var i = 0; i < variationData.length; i++) {
                var variation = variationData[i];
                var matches = true;
                
                for (var attrName in variation.attributes) {
                    var attrValue = variation.attributes[attrName];
                    var selectValue = selectedValues['attribute_' + attrName];
                    
                    if (attrValue && attrValue !== selectValue) {
                        matches = false;
                        break;
                    }
                }
                
                if (matches) {
                    $('#fcod-variation-id').val(variation.variation_id);
                    
                    // Update price if shown
                    if (variation.price_html) {
                        $('.fcod-product-price').html(variation.price_html);
                    }
                    
                    // Update image if needed
                    if (variation.image && variation.image.thumb_src) {
                        $('.fcod-product-image').attr('src', variation.image.thumb_src);
                    }
                    
                    break;
                }
            }
        }
    });
    <?php endif; ?>
});
</script>