<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<h3>Telegram Settings</h3>
<table class="form-table">
    <tr>
        <th>Enable Telegram Notifications</th>
        <td>
            <input type="checkbox" 
                   name="cod_form_telegram_enabled"
                   <?php checked(get_option('cod_form_telegram_enabled'), true); ?>>
        </td>
    </tr>
    <tr>
        <th>Bot Token</th>
        <td>
            <input type="text" 
                   name="cod_form_telegram_bot_token"
                   value="<?php echo esc_attr(get_option('cod_form_telegram_bot_token')); ?>"
                   class="regular-text">
            <p class="description">Enter your Telegram Bot Token (get it from @BotFather)</p>
        </td>
    </tr>
    <tr>
        <th>Chat ID</th>
        <td>
            <input type="text" 
                   name="cod_form_telegram_chat_id"
                   value="<?php echo esc_attr(get_option('cod_form_telegram_chat_id')); ?>"
                   class="regular-text">
            <p class="description">Enter the Chat ID where notifications should be sent</p>
        </td>
    </tr>
    <tr>
        <th>Message Template</th>
        <td>
            <textarea name="cod_form_telegram_message_template" 
                      rows="5" 
                      class="large-text"><?php echo esc_textarea(get_option('cod_form_telegram_message_template', "New Order!\n\nProduct: {product_name}\nPrice: {price}\nCustomer: {customer_name}\nPhone: {phone}")); ?></textarea>
            <p class="description">Available variables: {product_name}, {price}, {customer_name}, {phone}, {address}, {quantity}</p>
        </td>
    </tr>
</table>