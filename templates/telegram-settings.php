<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<h3>Telegram Notification Settings</h3>
<p>Configure automatic notifications for new orders via Telegram.</p>

<table class="form-table">
    <tr>
        <th>Enable Telegram Notifications</th>
        <td>
            <input type="checkbox" 
                   name="fcod_form_telegram_enabled"
                   value="1"
                   <?php checked(get_option('fcod_form_telegram_enabled'), 1); ?>>
            <span class="description">Enable automatic notifications for new orders</span>
        </td>
    </tr>
    <tr>
        <th>Bot Token</th>
        <td>
            <input type="text" 
                   name="fcod_form_telegram_bot_token"
                   value="<?php echo esc_attr(get_option('fcod_form_telegram_bot_token')); ?>"
                   class="regular-text">
            <p class="description">Enter your Telegram Bot Token (get it from <a href="https://t.me/BotFather" target="_blank">@BotFather</a>)</p>
        </td>
    </tr>
    <tr>
        <th>Chat ID</th>
        <td>
            <input type="text" 
                   name="fcod_form_telegram_chat_id"
                   value="<?php echo esc_attr(get_option('fcod_form_telegram_chat_id')); ?>"
                   class="regular-text">
            <p class="description">Enter the Chat ID where notifications should be sent</p>
            <p class="description">Tip: Talk to <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a> to get your personal Chat ID</p>
        </td>
    </tr>
    <tr>
        <th>Message Template</th>
        <td>
            <textarea name="fcod_form_telegram_message_template" 
                      rows="10" 
                      class="large-text code"><?php echo esc_textarea(get_option('fcod_form_telegram_message_template', "🛒 *New Order!*\n\n🏷️ Product: {product_name}\n💰 Price: {price}\n👤 Customer: {customer_name}\n📱 Phone: {phone}\n🏠 Address: {address}\n🔢 Quantity: {quantity}\n📅 Date: {date}")); ?></textarea>
            <p class="description">
                <strong>Available variables:</strong>
                <br><code>{order_id}</code> - Order ID
                <br><code>{product_name}</code> - Product name
                <br><code>{price}</code> - Total price
                <br><code>{customer_name}</code> - Customer's name
                <br><code>{phone}</code> - Customer's phone number
                <br><code>{address}</code> - Customer's address
                <br><code>{city}</code> - Customer's city
                <br><code>{quantity}</code> - Ordered quantity
                <br><code>{date}</code> - Order date and time
                <br>* You can also use any custom field name as a variable (e.g. <code>{email}</code>)
            </p>
            <p class="description">Tip: You can use Markdown formatting in your message. Use * for bold and _ for italic text.</p>
        </td>
    </tr>
    <tr>
        <th>Test Notification</th>
        <td>
            <button type="button" id="fcod-test-telegram" class="button button-secondary">Send Test Notification</button>
            <span id="fcod-test-result" style="margin-left: 10px; display: none;"></span>
        </td>
    </tr>
</table>

<script>
jQuery(document).ready(function($) {
    $('#fcod-test-telegram').on('click', function() {
        var $button = $(this);
        var $result = $('#fcod-test-result');
        
        $button.prop('disabled', true);
        $result.html('<span style="color: #777;">Sending test notification...</span>').show();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'fcod_test_telegram',
                bot_token: $('input[name="fcod_form_telegram_bot_token"]').val(),
                chat_id: $('input[name="fcod_form_telegram_chat_id"]').val(),
                nonce: '<?php echo wp_create_nonce('fcod_admin_nonce'); ?>',
                message: 'This is a test notification from your Flexible COD Form plugin! 🎉'
            },
            success: function(response) {
                if (response.success) {
                    $result.html('<span style="color: green;">✅ Test notification sent successfully!</span>');
                } else {
                    $result.html('<span style="color: red;">❌ Error: ' + response.data + '</span>');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr, status, error);
                $result.html('<span style="color: red;">❌ An error occurred while sending the test notification.</span>');
            },
            complete: function() {
                $button.prop('disabled', false);
                setTimeout(function() {
                    $result.fadeOut(500);
                }, 5000);
            }
        });
    });
});
</script>