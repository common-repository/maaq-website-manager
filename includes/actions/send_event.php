<?php

function maaq__send_event($event_id, $parameters = null)
{
    $body = [
        'eventId' => $event_id,
    ];

    if ($parameters) {
        $body['parameters'] = $parameters;
    }

    $args = [
        '/websites/events',
        'POST',
        $body,
    ];

    as_enqueue_async_action('maaq_send_api_request', $args, 'maaq-website-manager');
}
add_action('maaq_send_event', 'maaq__send_event', 10, 2);
