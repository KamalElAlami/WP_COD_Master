jQuery(document).ready(function($) {
    // Quantity buttons functionality
    $(document).on('click', '.fcod-qty-plus', function() {
        var $input = $(this).siblings('.fcod-quantity-input');
        var currentVal = parseInt($input.val());
        $input.val(currentVal + 1).trigger('change');
    });
    
    $(document).on('click', '.fcod-qty-minus', function() {
        var $input = $(this).siblings('.fcod-quantity-input');
        var currentVal = parseInt($input.val());
        if (currentVal > 1) {
            $input.val(currentVal - 1).trigger('change');
        }
    });
    
    // Handle variations
    $(document).on('change', '.fcod-variations select', function() {
        var $form = $(this).closest('form');
        var allSelected = true;
        var selectedAttributes = {};
        
        // Collect selected variation attributes
        $form.find('.fcod-variations select').each(function() {
            var attributeName = $(this).attr('name');
            var value = $(this).val();
            
            if (!value) {
                allSelected = false;
            }
            
            selectedAttributes[attributeName] = value;
        });
        
        // If all attributes selected, find matching variation
        if (allSelected && typeof fcod_variations !== 'undefined') {
            for (var i = 0; i < fcod_variations.length; i++) {
                var variation = fcod_variations[i];
                var match = true;
                
                // Check if all selected attributes match this variation
                for (var attrName in selectedAttributes) {
                    var selectedValue = selectedAttributes[attrName];
                    var variationValue = variation.attributes[attrName];
                    
                    // If this variation has a value for this attribute and it doesn't match the selected value
                    if (variationValue && variationValue !== selectedValue) {
                        match = false;
                        break;
                    }
                }
                
                // If all attributes match, update form with this variation
                if (match) {
                    // Set the variation ID
                    $form.find('#fcod-variation-id').val(variation.variation_id);
                    
                    // Update price if available
                    if (variation.display_price) {
                        $form.closest('.fcod-form-wrapper').find('.fcod-product-price').html(variation.price_html);
                    }
                    
                    // Update image if available
                    if (variation.image && variation.image.src) {
                        $form.closest('.fcod-form-wrapper').find('.fcod-product-image').attr('src', variation.image.src);
                    }
                    
                    // Trigger event for custom handling
                    $form.trigger('fcod_variation_found', [variation]);
                    
                    break;
                }
            }
        }
    });
    
    // Form validation
    $(document).on('submit', '.fcod-form', function(e) {
        var $form = $(this);
        var valid = true;
        
        // Check required fields
        $form.find('[required]').each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).addClass('fcod-error');
                
                // Add error message if it doesn't exist
                var $field = $(this).closest('.fcod-form-row');
                if ($field.find('.fcod-error-message').length === 0) {
                    $field.append('<div class="fcod-error-message">This field is required</div>');
                }
            } else {
                $(this).removeClass('fcod-error');
                $(this).closest('.fcod-form-row').find('.fcod-error-message').remove();
            }
        });
        
        // Phone validation for phone fields
        $form.find('input[type="tel"]').each(function() {
            var phoneValue = $(this).val();
            if (phoneValue && !isValidPhone(phoneValue)) {
                valid = false;
                $(this).addClass('fcod-error');
                
                var $field = $(this).closest('.fcod-form-row');
                if ($field.find('.fcod-error-message').length === 0) {
                    $field.append('<div class="fcod-error-message">Please enter a valid phone number</div>');
                }
            }
        });
        
        // Email validation for email fields
        $form.find('input[type="email"]').each(function() {
            var emailValue = $(this).val();
            if (emailValue && !isValidEmail(emailValue)) {
                valid = false;
                $(this).addClass('fcod-error');
                
                var $field = $(this).closest('.fcod-form-row');
                if ($field.find('.fcod-error-message').length === 0) {
                    $field.append('<div class="fcod-error-message">Please enter a valid email address</div>');
                }
            }
        });
        
        if (!valid) {
            e.preventDefault();
            // Scroll to first error
            var $firstError = $form.find('.fcod-error').first();
            if ($firstError.length) {
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                }, 500);
            }
        } else {
            // Show loading state
            $form.find('.fcod-submit-btn').prop('disabled', true).html('<span class="fcod-loading-spinner"></span> Processing...');
            
            // You could add AJAX submission here instead of traditional form submit
            // For now, we'll just allow the form to submit normally
        }
    });
    
    // Clear error on input
    $(document).on('input', '.fcod-form [required], .fcod-form input[type="tel"], .fcod-form input[type="email"]', function() {
        $(this).removeClass('fcod-error');
        $(this).closest('.fcod-form-row').find('.fcod-error-message').remove();
    });
    
    // Helper functions for validation
    function isValidPhone(phone) {
        // Basic phone validation - adjust regex based on your needs
        return /^[+]?[\d\s()-]{8,20}$/.test(phone);
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});

// Add loading animation styles dynamically
document.addEventListener('DOMContentLoaded', function() {
    var style = document.createElement('style');
    style.textContent = `
        .fcod-loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: fcod-spin 1s ease-in-out infinite;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        @keyframes fcod-spin {
            to { transform: rotate(360deg); }
        }
        
        .fcod-error {
            border-color: #dc3232 !important;
        }
        
        .fcod-error-message {
            color: #dc3232;
            font-size: 12px;
            margin-top: 5px;
        }
    `;
    document.head.appendChild(style);
});