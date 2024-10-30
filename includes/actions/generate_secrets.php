<?php

function maaq__generate_secrets()
{
    update_option("maaq_secret_token", wp_generate_password(wp_rand(32, 38)));
    update_option("maaq_secret_path", wp_generate_password(wp_rand(16, 24), false));
}
add_action("maaq_generate_secrets", "maaq__generate_secrets");
