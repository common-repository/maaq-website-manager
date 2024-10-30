<?php

function maaq__woocommerce_new_order_event($order_id, $order)
{
    $parameters = [
        "orderId" => $order_id,
        "total" => $order->get_formatted_order_total(),
    ];

    do_action('maaq_send_event', '3W8PUu5dNaVYdq9sGL9YU', $parameters);
}
add_action('woocommerce_new_order', 'maaq__woocommerce_new_order_event', 10, 2);
