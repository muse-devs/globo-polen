<?php

/**
 * Saber se o dispositivo é mobile
 * @return bool
 */
function polen_is_mobile() {
    $detect = new Mobile_Detect();
    return $detect->isMobile();
}
