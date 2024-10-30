<?php

function maaq__forms_new_submission_event()
{
    do_action('maaq_send_event', 'S5UTIb8l0p7x_IRgkAtSq');
}
add_action('wpforms_process_complete', 'maaq__forms_new_submission_event');
add_action('ninja_forms_after_submission', 'maaq__forms_new_submission_event');
add_action('gform_after_submission', 'maaq__forms_new_submission_event');
add_action('wpcf7_mail_sent', 'maaq__forms_new_submission_event');
add_action('elementor_pro/forms/form_submitted', 'maaq__forms_new_submission_event');
