<?php

function maaq__post_status_changed_event($new_status, $old_status, $post)
{
    if (wp_is_post_revision($post->ID) || 'nav_menu_item' === get_post_type($post->ID) || ('auto-draft' === $new_status || ('new' === $old_status && 'inherit' === $new_status))) {
        return;
    }

    if ('auto-draft' === $old_status && ('auto-draft' !== $new_status && 'inherit' !== $new_status)) {
        $parameters = [
            "type" => $post->post_type,
            "title" => $post->post_title,
            "status" => $post->post_status,
        ];

        return do_action('maaq_send_event', 'teuacsgzF0WtG9auyQX2Q', $parameters);
    }

    if ('trash' === $new_status) {
        $parameters = [
            "type" => $post->post_type,
            "title" => $post->post_title,
        ];

        return do_action('maaq_send_event', 'XKx8oALZLA3MFUtVA0Lga', $parameters);
    }

    if ('trash' === $old_status) {
        $parameters = [
            "type" => $post->post_type,
            "title" => $post->post_title,
        ];

        return do_action('maaq_send_event', 'V_SVGOmG149ZBFFKRCT50', $parameters);
    }

    $parameters = [
        "type" => $post->post_type,
        "title" => $post->post_title,
        "status" => $post->post_status,
    ];

    do_action('maaq_send_event', 'q3sLyo0DMUNf3nmjLXCYd', $parameters);
}
add_action('transition_post_status', 'maaq__post_status_changed_event', 10, 3);
