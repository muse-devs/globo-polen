<?php
defined( 'ABSPATH' ) || die;


echo '<pre>';var_dump('social_is_in_social_app:',social_is_in_social_app());echo '</pre>';
echo '<pre>';var_dump('social_get_category_base:',social_get_category_base());;echo '</pre>';
echo '<pre>';var_dump('social_get_products_by_category_slug:',social_get_products_by_category_slug(social_get_category_base()));;echo '</pre>';
echo '<br>ASD';