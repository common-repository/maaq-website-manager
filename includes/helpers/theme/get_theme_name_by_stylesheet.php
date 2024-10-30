<?php

function maaq__get_theme_name($stylesheet)
{
    $theme = wp_get_theme($stylesheet);
    $theme_name = $theme->get('Name');

    return $theme_name ?? $stylesheet;
}
