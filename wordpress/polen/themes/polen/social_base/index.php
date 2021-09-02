<?php

defined('ABSPATH') || die;

use Polen\Social_Base\Social_Base_Product;
use Polen\Social_Base\Social_Base_Rewrite;

$social_slug = $GLOBALS[ Social_Base_Rewrite::QUERY_VARS_SOCIAL_SLUG ];

$products = Social_Base_Product::get_all_products_by_slug_campaing( $social_slug );

get_header();
?>

<main id="primary" class="site-main">

	<?php sa_get_modal(); ?>

	<?php polen_front_get_banner_with_carousel(true);
	?>

	<?php polen_front_get_news(social_get_products_by_category_slug(social_get_category_base()), "Os artistas que apoiam essa causa", null, true);
	?>

	<?php polen_front_get_tutorial(); ?>

	<?php //polen_front_get_suggestion_box();
	?>

</main><!-- #main -->

<?php
get_footer();
