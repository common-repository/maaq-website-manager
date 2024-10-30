<?php

function maaq__on_deactivation()
{
    as_unschedule_all_actions('maaq_sync_updates');
}
