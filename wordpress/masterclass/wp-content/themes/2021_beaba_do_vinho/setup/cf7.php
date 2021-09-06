<?php

/**
 * Remove a tag <span> dos inputs do formulário
 */
add_filter('wpcf7_form_elements', function ($content)
{
    $content = preg_replace(
        '/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2',
        $content
    );

    return str_replace('<br />', '', $content);
});

add_filter('wpcf7_autop_or_not', '__return_false');
