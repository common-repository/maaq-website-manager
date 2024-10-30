<?php

function maaq__theme_deleted_event($stylesheet, $deleted)
{
    if (!$deleted) {
        return;
    }

    $theme_name = maaq__get_theme_name($stylesheet);

    $parameters = [
        "stylesheet" => $stylesheet,
        "themeName" => $theme_name,
    ];

    do_action('maaq_send_event', 'mQ9J32pXyYzU2ZwtDgW8s', $parameters);
}
add_action('deleted_theme', 'maaq__theme_deleted_event', 10, 2);
