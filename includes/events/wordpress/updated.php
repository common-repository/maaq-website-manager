<?php

function maaq__wordpress_updated_event($new_version)
{
    $parameters = [
        "newVersion" => $new_version
    ];

    do_action('maaq_send_event', 'uBy6_upOC1l5OAM-7NbVX', $parameters);
}
add_action('_core_updated_successfully', 'maaq__wordpress_updated_event', 10, 1);
