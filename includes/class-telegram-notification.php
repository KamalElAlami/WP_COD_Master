<?php
class Telegram_Notification {
    public function __construct() {
        add_action('fcod_order_created', array($this, 'send_notification'));
    }

    public function send_notification($order) {
        if (!get_option('fcod_form_telegram_enabled')) {
            return;
        }

        $bot_token = get_option('fcod_form_telegram_bot_token');
        $chat_id = get_option('fcod_form_telegram_chat_id');
        
        if (empty($bot_token) || empty($chat_id)) {
            return;
        }

        $message = $this->prepare_message($order);
        $this->send_telegram_message($bot_token, $chat_id, $message);
    }

    private function prepare_message($order) {
        $template = get_option('fcod_form_telegram_message_template', 
            "🛒 *New Order!*\n\n" .
            "🏷️ Product: {product_name}\n" .
            "💰 Price: {price}\n" .
            "👤 Customer: {customer_name}\n" .
            "📱 Phone: {phone}\n" .
            "🏠 Address: {address}\n" .
            "🔢 Quantity: {quantity}\n" .
            "📅 Date: {date}"
        );
        
        $items = $order->get_items();
        $first_item = reset($items);
        
        // Default values in case some are missing
        $product_name = '-';
        $quantity = '-';
        $price = '-';
        
        if ($first_item) {
            $product = $first_item->get_product();
            if ($product) {
                $product_name = $product->get_name();
                $quantity = $first_item->get_quantity();
            }
        }

        $replacements = array(
            '{order_id}' => $order->get_id(),
            '{product_name}' => $product_name,
            '{price}' => $order->get_formatted_order_total(),
            '{customer_name}' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            '{phone}' => $order->get_billing_phone(),
            '{address}' => $order->get_billing_address_1(),
            '{city}' => $order->get_billing_city(),
            '{quantity}' => $quantity,
            '{date}' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), time())
        );
        
        // Get any custom fields saved as meta
        $meta_data = $order->get_meta_data();
        foreach ($meta_data as $meta) {
            if (strpos($meta->key, '_fcod_field_') === 0) {
                $field_name = str_replace('_fcod_field_', '', $meta->key);
                $replacements['{' . $field_name . '}'] = $meta->value;
            }
        }

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );
    }

    private function send_telegram_message($bot_token, $chat_id, $message) {
        $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
        $params = array(
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown'
        );

        $response = wp_remote_post($url, array(
            'body' => $params,
            'timeout' => 15
        ));
        
        // Log error if needed
        if (is_wp_error($response)) {
            error_log('Telegram notification error: ' . $response->get_error_message());
        }
    }
}