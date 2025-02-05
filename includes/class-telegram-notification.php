<?php
class Telegram_Notification {
    public function __construct() {
        add_action('cod_form_order_created', array($this, 'send_notification'));
    }

    public function send_notification($order) {
        if (!get_option('cod_form_telegram_enabled')) {
            return;
        }

        $bot_token = get_option('cod_form_telegram_bot_token');
        $chat_id = get_option('cod_form_telegram_chat_id');
        
        if (empty($bot_token) || empty($chat_id)) {
            return;
        }

        $message = $this->prepare_message($order);
        $this->send_telegram_message($bot_token, $chat_id, $message);
    }

    private function prepare_message($order) {
        $template = get_option('cod_form_telegram_message_template');
        $items = $order->get_items();
        $first_item = reset($items);
        $product = $first_item->get_product();

        $replacements = array(
            '{product_name}' => $product->get_name(),
            '{price}' => $order->get_total(),
            '{customer_name}' => $order->get_billing_first_name(),
            '{phone}' => $order->get_billing_phone(),
            '{address}' => $order->get_billing_address_1(),
            '{quantity}' => $first_item->get_quantity()
        );

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
            'parse_mode' => 'HTML'
        );

        wp_remote_post($url, array(
            'body' => $params,
            'timeout' => 15
        ));
    }
}
