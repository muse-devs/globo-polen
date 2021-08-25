<?php

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Polen_Video_Info;
use Polen\Social\Social_Rewrite;
use Polen\Tributes\Tributes_Model;
use Polen\Tributes\Tributes_Rewrite_Rules;

/**
 * Tags Open Graph
 */
if (
	!in_array('all-in-one-seo-pack/all_in_one_seo_pack.php',
	apply_filters('active_plugins',
	get_option('active_plugins')))) {

		add_action('wp_head', function () {

			global $post;
			global $is_video;
			$tribute_app = get_query_var(Tributes_Rewrite_Rules::TRIBUTES_QUERY_VAR_TRUBITES_APP);
			$social_app = get_query_var(Social_Rewrite::QUERY_VARS_SOCIAL_APP);

			$video_hash = get_query_var('video_hash');

			//Header Padrão ----------------
			$headers = array(
				'title' => 'Polen.me - ' . get_the_title(),
				'description' => get_bloginfo('description'),
				'site_name' => get_bloginfo('title'),
				'url' => get_the_permalink(),
				'type' => 'site',
				'image' => '',
				'video' => '',
				'keywords' => 'Vídeos Personalizados',
			);

			if (!empty($post) && $post->post_type == 'product') {

				$thumbnail = get_the_post_thumbnail_url(get_the_ID());
				if (!$thumbnail || is_null($thumbnail) || empty($thumbnail)) {
					$thumbnail = polen_get_custom_logo_url();
				}
				$headers['image'] = $thumbnail;

			} elseif ($is_video === true && !empty($video_hash)) {

				$video_info = Polen_Video_Info::get_by_hash($video_hash);
				$order = wc_get_order($video_info->order_id);
				$item_cart = Polen_Cart_Item_Factory::polen_cart_item_from_order($order);

				$product_id = $item_cart->get_product_id();
				$product = wc_get_product($product_id);
				$headers['title'] = 'Direto, Próximo, Íntimo.';
				$talent_name = $product->get_title();
				$headers['description'] = "Olha esse novo vídeo-polen de {$talent_name}.";
				$headers['url'] = site_url('v/' . $video_info->hash);
				$headers['image'] = $video_info->vimeo_thumbnail;

			} elseif (!empty($post) && $post->post_type == 'page' && $post->post_name == 'v') {

				$video_url = get_the_permalink() . '?' . $_SERVER['QUERY_STRING'];
				$headers['url'] = $video_url;
				$headers['video'] = $video_url;

				$thumbnail = get_the_post_thumbnail_url(get_the_ID());
				if (!$thumbnail || is_null($thumbnail) || empty($thumbnail)) {
					$thumbnail = polen_get_custom_logo_url();
				}
				$headers['image'] = $thumbnail;

			} elseif (!empty($tribute_app) && $tribute_app == '1') {

				$tribute_operation = get_query_var(Tributes_Rewrite_Rules::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION);

				$headers['title'] = 'Colab';
				$headers['site_name'] = 'Colab';

				if ($tribute_operation == Tributes_Rewrite_Rules::TRIBUTES_OPERATION_VIDEOPLAY) {

					$slug_tribute = get_query_var(Tributes_Rewrite_Rules::TRIBUTES_QUERY_VAR_TRIBUTES_VIDEOPLAY);
					$tribute = Tributes_Model::get_by_slug($slug_tribute);

					$headers['description'] = $tribute->welcome_message;
					$headers['url'] = tribute_get_url_final_video($slug_tribute);
					$headers['video'] = $tribute->vimeo_link;
					$headers['image'] = $tribute->vimeo_thumbnail;

				} else {

					$headers['type'] = 'Colab';
					$headers['description'] = 'O Colab te ajuda a criar um vídeo-presente em grupo para você emocionar quem você ama!';
					$headers['url'] = tribute_get_url_base_url();
					$headers['image'] = "' . TEMPLATE_URI . '/tributes/assets/img/logo-to-share.png";

				}

			} elseif (!empty($social_app) && $social_app == '1') {

				$image = social_get_image_by_category(social_get_category_base());

				$headers['image'] = $image;
				$headers['url'] = site_url('social/crianca-esperanca');

			} elseif (event_promotional_is_app()) {

				$headers['title'] = 'Luciano Huck - De porta em porta';
				$headers['description'] = 'Luciano Huck - De porta em porta';
				$headers['url'] = event_promotional_url_home();
				$headers['image'] = '//polen.me/polen/uploads/2021/08/book_cover.png';
				$headers['site_name'] = 'Polen.me - Luciano Huck - De porta em porta';

			} else {

				$headers['image'] = 'https://polen.me/polen/uploads/2021/06/cropped-logo.png';

			}

	?>
			<meta name="title" content="<?php echo $headers['title']; ?>">
			<meta name="description" content="<?php echo $headers['description']; ?>">
			<meta name="keywords" content="<?php echo $headers['keywords']; ?>">
			<meta name="author" content="<?php echo $headers['author']; ?>">

			<meta property="og:title" content="<?php echo $headers['title']; ?>">
			<meta property="og:description" content="<?php echo $headers['description']; ?>">
			<meta property="og:url" content="<?php echo $headers['url']; ?>">
			<meta property="og:image" content="<?php echo $headers['image']; ?>">
			<meta property="og:locale" content="<?php echo get_locale(); ?>">
			<meta property="og:site_name" content="<?php echo $headers['site_name']; ?>">

			<?php if (!empty($headers['type'])) : ?>
				<meta property="og:type" content="<?php echo $headers['type']; ?>">
			<?php endif; ?>

			<?php if (!empty($headers['video'])) : ?>
				<meta property="og:video" content="<?php echo $headers['video']; ?>">
			<?php endif; ?>
	<?php

		});
}
