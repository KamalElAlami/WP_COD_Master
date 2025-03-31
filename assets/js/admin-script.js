jQuery(document).ready(function($) {
    // Initialize color picker
    if ($.fn.wpColorPicker) {
        $('.fcod-color-picker').wpColorPicker();
    }
    
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
    
    // Template selection handling - direct AJAX call without using variables
    // This is already handled by the script in style-settings.php
    
    // Test Telegram notification
    $('#fcod-test-telegram').on('click', function() {
        var $button = $(this);
        var $result = $('#fcod-test-result');
        var bot_token = $('input[name="fcod_form_telegram_bot_token"]').val();
        var chat_id = $('input[name="fcod_form_telegram_chat_id"]').val();
        
        if (!bot_token || !chat_id) {
            alert('Please enter both Bot Token and Chat ID before testing.');
            return;
        }
        
        $button.prop('disabled', true);
        $result.html('<span style="color: #777;">Sending test notification...</span>').show();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'fcod_test_telegram',
                bot_token: bot_token,
                chat_id: chat_id,
                // Use PHP-generated nonce directly from Telegram settings template
                message: 'This is a test notification from your Flexible COD Form plugin! 🎉'
            },
            success: function(response) {
                if (response.success) {
                    $result.html('<span style="color: green;">✓ Test message sent successfully!</span>');
                } else {
                    $result.html('<span style="color: red;">✗ Error: ' + response.data + '</span>');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr, status, error);
                $result.html('<span style="color: red;">✗ Connection error</span>');
            },
            complete: function() {
                $button.prop('disabled', false);
                setTimeout(function() {
                    $result.fadeOut(500);
                }, 5000);
            }
        });
    });
    
    // Field drag and drop reordering
    if ($.fn.sortable) {
        $('#fcod-fields-container').sortable({
            items: '.fcod-field-item',
            handle: '.fcod-field-drag',
            update: function() {
                updateFieldIndices();
            }
        });
    }
    
    // Add new field
    $('#fcod-add-field').on('click', function() {
        var template = $('#fcod-field-template').html();
        var index = $('.fcod-field-item').length;
        template = template.replace(/\{\{index\}\}/g, index);
        $('#fcod-fields-container').append(template);
        
        // Show settings
        $('.fcod-field-item').last().find('.fcod-field-settings').show();
    });
    
    // Remove field
    $(document).on('click', '.fcod-remove-field', function() {
        if (confirm('Are you sure you want to remove this field?')) {
            $(this).closest('.fcod-field-item').remove();
            updateFieldIndices();
        }
    });
    
    // Toggle field settings
    $(document).on('click', '.fcod-field-header', function() {
        var $fieldItem = $(this).closest('.fcod-field-item');
        $fieldItem.toggleClass('closed');
        $fieldItem.find('.fcod-field-settings').slideToggle(200);
        $fieldItem.find('.fcod-field-toggle').toggleClass('dashicons-arrow-down dashicons-arrow-up');
    });
    
    // Update field label when typing
    $(document).on('input', '.fcod-field-label-input', function() {
        var value = $(this).val() || 'Unnamed Field';
        $(this).closest('.fcod-field-item').find('.fcod-field-title').text(value);
    });
    
    // Function to update field indices
    function updateFieldIndices() {
        $('.fcod-field-item').each(function(index) {
            var $field = $(this);
            $field.attr('data-index', index);
            
            $field.find('input, select, textarea').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var newName = name.replace(/fcod_form_custom_fields\[\d+\]/, 'fcod_form_custom_fields[' + index + ']');
                    $(this).attr('name', newName);
                }
            });
        });
    }
    
    // Initialize field settings
    $('.fcod-field-item').each(function() {
        // Initially show the field settings
        $(this).find('.fcod-field-settings').show();
    });
});