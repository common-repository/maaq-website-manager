<?php

function maaq__theme_switched_event($new_name, $new_theme)
{
    $parameters = [
        "stylesheet" => $new_theme->get_stylesheet(),
        "themeName" => $new_name,
    ];

    do_action('maaq_send_event', 'RpZjscVFybAE5moyot_3W', $parameters);
}
add_action('switch_theme', 'maaq__theme_switched_event', 10, 2);
