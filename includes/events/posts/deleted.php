<?php

function maaq__post_deleted_event($post_id, $post)
{
    if (wp_is_post_revision($post_id) || 'nav_menu_item' === get_post_type($post->ID) || in_array($post->post_status, ['auto-draft', 'inherit'])) {
        return;
    }

    $parameters = [
        "type" => $post->post_type,
        "title" => $post->post_title,
    ];

    do_action('maaq_send_event', 'iwZXeN-svwSNLWzFNJ6rn', $parameters);
}
add_action('deleted_post', 'maaq__post_deleted_event', 10, 2);
