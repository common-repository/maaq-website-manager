<?php

function ownpowr__error_event()
{
    $error = error_get_last();

    if (!$error) {
        return;
    }

    $type = $error['type'];

    // Do not send event on warnings.
    if ($type == 2) {
        return;
    }

    $message = $error['message'];
    $file = $error['file'];
    $line = $error['line'];

    $hash = md5($message);
    $transient_name = "maaq__error_$hash";

    $transient = get_transient($transient_name);

    // Check if the transient exists so we don't send the same error multiple times.
    if ($transient) {
        return;
    }

    set_transient($transient_name, true, 7200);

    $parameters = [
        "level" => $type,
        "message" => $message,
        "file" => $file,
        "line" => $line,
    ];

    do_action('maaq_send_event', '1WVcGYNe0yOKazJHAi2PJ', $parameters);
}
add_action('shutdown', 'ownpowr__error_event', 1);
