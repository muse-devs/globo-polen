<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Polen
 */

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Polen_Video_Info;
use Polen\Tributes\Tributes_Model;
use Polen\Tributes\Tributes_Rewrite_Rules;

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function polen_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'polen_body_classes' );


function polen_get_header_objects()
{
	?>
	<script>
		var polenObj = {
			base_url: '<?= site_url(); ?>',
			template_uri: '<?php echo TEMPLATE_URI; ?>',
			developer: <?php echo DEVELOPER ? 1 : 0; ?>,
			ajax_url: "/wp-admin/admin-ajax.php",
			COOKIES: <?php echo json_encode(POL_COOKIES); ?>
		};
		if (!polenObj.developer) {
			console = {
				debug: function() {},
				error: function() {},
				info: function() {},
				log: function() {},
				warn: function() {},
			};
		}
	</script>
	<?php
}

/**
 * Responsible to return a link for all talents
 *
 * @return string link
 */
function polen_get_all_new_talents_url()
{
	return polen_get_all_talents_url() . '?orderby=date';
}

/**
 * Retorna a URL de todos os talentos
 *
 * @return string link
 */
function polen_get_all_talents_url()
{
	return get_permalink( wc_get_page_id( 'shop' ) );
}


/**
 * Responsible to return a link for all categories
 *
 * @return string link
 */
function polen_get_all_categories_url()
{
	return site_url( get_option( 'category_base', null ) );
}


/**
 * Get a URL para assistir video passando a $order_id
 * @param int $order_id
 */
function polen_get_link_watch_video_by_order_id( $order_id )
{
	return wc_get_account_endpoint_url('watch-video') . "{$order_id}";
}


/**
 * Funcao para pegar a URL do My-Account
 */
function polen_get_url_my_account()
{
	return get_permalink( get_option('woocommerce_myaccount_page_id') );
}

/**
 * Funcao para pegar a URL dos Pedidos (Talento)
 */
function polen_get_url_my_orders()
{
	return polen_get_url_my_account() . "orders";
}


/**
 * Pegar a URL da categoria pela CategoriaID
 */
function polen_get_url_category_by_term_id( $term_id )
{
	return get_term_link( $term_id, 'product_cat' );
}


/**
 * Pegar a URL da categoria pelo ProductID
 */
function polen_get_url_category_by_product_id ( $product_id )
{
	$cat_terms = wp_get_object_terms( $product_id, 'product_cat' );
    $cat_link = '';
	$cat = array_pop( $cat_terms );
    if ( !empty($cat) ) {
        $cat_link = get_term_link($cat->term_id);
    }
	return $cat_link;
}


/**
 * Pegar a URL da categoria pelo OrderID
 */
function polen_get_url_category_by_order_id ( $order_id )
{
	$order = wc_get_order( $order_id );
	$car_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
	$category_ids = $car_item->get_product()->get_category_ids();
	$category_id = array_pop( $category_ids );
	$cat_terms = wp_get_object_terms( $category_id, 'product_cat' );
    $cat_link = '';
	$cat = array_pop( $cat_terms );
    if ( !empty($cat) ) {
        $cat_link = get_term_link($cat->term_id);
    }
	return $cat_link;
}


function polen_get_url_review_page()
{
	return './reviews/';
}

/**
 * Pegar a URL da Custom Logo
 */
function polen_get_custom_logo_url() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if( $custom_logo_id && ! is_null( $custom_logo_id ) && ! empty( $custom_logo_id ) ) {
		$image_url = wp_get_attachment_image_url( $custom_logo_id, 'full', true );
		$protocol = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) ? 'https:' : 'http:';
		return $protocol . $image_url;
	}
}


/**
 * Pegar a URL da Custom Logo
 * a unica funcao dessa function é corrigir um erro que está dando em producao
 * tem que ser removido e corrigido.
 * o problema é que está apresentado https:https://polen.m
 */
function polen_get_custom_logo_url_() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if( $custom_logo_id && ! is_null( $custom_logo_id ) && ! empty( $custom_logo_id ) ) {
		$image_url = wp_get_attachment_image_url( $custom_logo_id, 'full', true );
		$protocol = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) ? 'https:' : 'http:';
		$protocol = '';
		return $protocol . $image_url;
	}
}


/**
 * Pegar as duas logos para thema claro e escuro
 */
function polen_get_theme_logos() {
	$logo_dark = get_theme_mod( 'custom_logo' );
	$logo_dark = wp_get_attachment_image_url( $logo_dark, 'full' );
	$logo_light = get_theme_mod( 'logo_theme_white' );

	// Provisório CRIESP
	$logo_criesp_dark = TEMPLATE_URI . '/assets/img/criesp/logo-criesp.png';
	$logo_criesp_light = TEMPLATE_URI . '/assets/img/criesp/logo-criesp-color.png';

	$html =  '<a href="' . get_site_url() . '" class="custom-logo-link" rel="home" aria-current="page">';

	if(is_front_page() || social_is_in_social_app()) {
		$html .= 	'<img width="67" height="40" src="'. $logo_dark . '" class="custom-logo" alt="Polen">';
	} else {
		$html .= 	'<img width="67" height="40" src="'. $logo_dark . '" class="custom-logo dark" alt="Polen">';
		$html .= 	'<img width="67" height="40" src="'. $logo_light . '" class="custom-logo light" alt="Polen">';
	}
	$html .= '</a>';

	if(is_tribute_app())
	{
		return $html;
	}

	// Provisório CRIESP
	$html .= '<a href="' . social_get_criesp_url() . '">';
	if(is_front_page() || social_is_in_social_app()) {
		$html .= 	'<img width="106" height="31" src="'. $logo_criesp_dark . '" class="custom-logo custom-logo-criesp" alt="Logo Criança Esperança">';
	} else {
		$html .= 	'<img width="106" height="31" src="'. $logo_criesp_dark . '" class="custom-logo custom-logo-criesp dark" alt="Logo Criança Esperança">';
		$html .= 	'<img width="106" height="31" src="'. $logo_criesp_light . '" class="custom-logo custom-logo-criesp light" alt="Logo Criança Esperança">';
	}
	$html .= '</a>';

	return $html;
}

function polen_the_theme_logos() {
	echo polen_get_theme_logos();
}

/**
 * Funcao que pegar a URL de login e completa com ?redirect= se estiver no cart ou checkout
 */
function polen_get_login_url() {
	$complement = '';
	if( is_cart() || is_checkout() ) {
		$url_complement = is_cart() ? urlencode( wc_get_cart_url() ) : urlencode( wc_get_checkout_url() );
		$complement = '?redirect_to=' . $url_complement;
	}
	return polen_get_url_my_account() . $complement;
}


/**
 *
 */
function polen_get_querystring_redirect()
{
	$redirect_to = urlencode( filter_input( INPUT_GET, 'redirect_to' ) );
	if( !empty( $redirect_to ) ) {
		return "?redirect_to={$redirect_to}";
	}
	return null;
}


/**
 * Se o email que será enviado for para um Talento
 * será mostrado o Valor Total sem desconto só é tratado nessa funcao
 * emails Polen\Includes\Polen_WC_Payment_Approved
 *
 * @param WC_Order
 * @param \WC_Email
 */
function polen_get_total_order_email_detail_to_talent( $order, $email )
{
	if ( ( 'Polen\Includes\Polen_WC_Payment_Approved' === get_class( $email ) )
		&& !empty( $email->get_recipient_talent())
	) {
		$total_order = floatval( $order->get_total() );
		$discount = floatval( $order->get_discount_total() );
		return polen_apply_polen_part_price( ( $total_order + $discount ) );
	}
	return $order->get_total();
}


function polen_get_videos_by_talent($talent, $json = false)
{
	$items = array();
	$items_raw = Polen\Includes\Polen_Video_Info::select_by_talent_id($talent->user_id);
	foreach ($items_raw as $item) {
		$order = wc_get_order($item->order_id);
		$cart_item = \Polen\Includes\Cart\Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
		$items[] = [
			'title' => '',
			'name' => $talent->nome,
			'thumb' => polen_get_avatar($talent->user_id, 'polen-square-crop-lg'),
			'cover' =>  $item->vimeo_thumbnail,
			'video' => $item->vimeo_file_play,
			'hash' => $item->hash,
			'first_order' => $item->first_order,
			'initials' => polen_get_initials_name($cart_item->get_name_to_video()),
		];
	}

	return $json ? json_encode($items) : $items;
}


/**
 * Aplica a parte da polen no valor de entrada (valor produto)
 * 25%
 *
 * @param float $full_price
 */
function polen_apply_polen_part_price( $full_price )
{
    return ( floatval( $full_price ) * 0.75 );
}



/**
 * Tags Open Graph
 */
if ( ! in_array( 'all-in-one-seo-pack/all_in_one_seo_pack.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'wp_head', function() {
		global $post;
		global $is_video;
		$tribute_app = get_query_var( Tributes_Rewrite_Rules::TRIBUTES_QUERY_VAR_TRUBITES_APP );

		$video_hash = get_query_var( 'video_hash' );
		if( !empty( $post ) && $post->post_type == 'product' ) {
			echo "\n\n";
			echo "\t" . '<meta property="og:title" content="' . get_the_title() . '">' . "\n";
			echo "\t" . '<meta property="og:description" content="' . get_the_excerpt() . '">' . "\n";
			echo "\t" . '<meta property="og:url" content="' . get_the_permalink() . '">' . "\n";
			echo "\t" . '<meta property="og:locale" content="' . get_locale() . '">' . "\n";
			echo "\t" . '<meta property="og:site_name" content="' . get_bloginfo( 'title' ) . '">' . "\n";
			$thumbnail = get_the_post_thumbnail_url( get_the_ID() );
			if( $thumbnail && ! is_null( $thumbnail ) && ! empty( $thumbnail) ) {
				echo "\t" . '<meta property="og:image" content="' . $thumbnail . '">' . "\n";
			} else {
				echo "\t" . '<meta property="og:image" content="' . polen_get_custom_logo_url() . '">' . "\n";
			}
			echo "\n";
		} elseif ( $is_video === true && !empty( $video_hash ) ) {
			$video_info = Polen_Video_Info::get_by_hash( $video_hash );
			$order = wc_get_order( $video_info->order_id );
			$item_cart = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );

			$product_id = $item_cart->get_product_id();
			$product = wc_get_product( $product_id );
			$title = 'Direto, Próximo, Íntimo.';//$item_cart->get_name_to_video();
			$talent_name = $product->get_title();
			$description = "Olha esse novo vídeo-polen de {$talent_name}.";
			$url = site_url( 'v/' . $video_info->hash );
			$thumbnail = $video_info->vimeo_thumbnail;

			echo "\n\n";
			echo "\t" . '<meta property="og:title" content="' . $title . '">' . "\n";
			echo "\t" . '<meta property="og:description" content="' . $description . '">' . "\n";
			echo "\t" . '<meta property="og:url" content="' . $url . '">' . "\n";
			echo "\t" . '<meta property="og:locale" content="' . get_locale() . '">' . "\n";
			echo "\t" . '<meta property="og:site_name" content="' . get_bloginfo( 'title' ) . '">' . "\n";
			echo "\t" . '<meta property="og:image" content="' . $thumbnail . '">' . "\n";
			// echo "\t" . '<meta property="og:type" content="video">' . "\n";
			echo "\n";
		} elseif( !empty( $post ) && $post->post_type == 'page' && $post->post_name == 'v' ) {
			echo "\n\n";
			echo "\t" . '<meta property="og:title" content="' . get_the_title() . '">' . "\n";
			echo "\t" . '<meta property="og:description" content="' . get_the_excerpt() . '">' . "\n";
			echo "\t" . '<meta property="og:url" content="' . get_the_permalink() . '?' . $_SERVER['QUERY_STRING'] . '">' . "\n";
			echo "\t" . '<meta property="og:locale" content="' . get_locale() . '">' . "\n";
			echo "\t" . '<meta property="og:site_name" content="' . get_bloginfo( 'title' ) . '">' . "\n";
			echo "\t" . '<meta property="og:video" content="' . get_the_permalink() . '?' . $_SERVER['QUERY_STRING'] . '">' . "\n";
			$thumbnail = get_the_post_thumbnail_url( get_the_ID() );
			if( $thumbnail && ! is_null( $thumbnail ) && ! empty( $thumbnail) ) {
				echo "\t" . '<meta property="og:image" content="' . $thumbnail . '">' . "\n";
			} else {
				echo "\t" . '<meta property="og:image" content="' . polen_get_custom_logo_url() . '">' . "\n";
			}
			echo "\n";
		} elseif( !empty( $tribute_app ) && $tribute_app == '1' ) {
			$tribute_operation = get_query_var( Tributes_Rewrite_Rules::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION );
			if( $tribute_operation == Tributes_Rewrite_Rules::TRIBUTES_OPERATION_VIDEOPLAY ) {
				$slug_tribute = get_query_var( Tributes_Rewrite_Rules::TRIBUTES_QUERY_VAR_TRIBUTES_VIDEOPLAY );
				$tribute = Tributes_Model::get_by_slug( $slug_tribute );
				echo "\n\n";
				echo "\t" . '<meta property="og:title" content="Colab">' . "\n";
				echo "\t" . '<meta property="og:description" content="' . $tribute->welcome_message . '">' . "\n";
				echo "\t" . '<meta property="og:url" content="' . tribute_get_url_final_video( $slug_tribute ) . '">' . "\n";
				echo "\t" . '<meta property="og:locale" content="' . get_locale() . '">' . "\n";
				echo "\t" . '<meta property="og:site_name" content="Colab">' . "\n";
				echo "\t" . '<meta property="og:video" content="' . $tribute->vimeo_link . '">' . "\n";
				echo "\t" . '<meta property="og:image" content="' . $tribute->vimeo_thumbnail . '">' . "\n";
				echo "\n";
			} else {
				echo "\n\n";
				echo "\t" . '<meta property="og:title" content="Colab">' . "\n";
				echo "\t" . '<meta property="og:type" content="Colab">' . "\n";
				echo "\t" . '<meta property="og:description" content="O Colab te ajuda a criar um vídeo-presente em grupo para você emocionar quem você ama!">' . "\n";
				echo "\t" . '<meta property="og:url" content="' . tribute_get_url_base_url() . '">' . "\n";
				echo "\t" . '<meta property="og:locale" content="' . get_locale() . '">' . "\n";
				echo "\t" . '<meta property="og:site_name" content="Colab">' . "\n";
				echo "\t" . '<meta property="og:image" content="' . TEMPLATE_URI . '/tributes/assets/img/logo-to-share.png">' . "\n";
				echo "\n";
			}
		} else {
			echo "\n\n";
			echo "\t" . '<meta property="og:title" content="' . get_bloginfo( 'title' ) . '">' . "\n";
			echo "\t" . '<meta property="og:type" content="site">' . "\n";
			echo "\t" . '<meta property="og:description" content="' . get_bloginfo( 'description' ) . '">' . "\n";
			echo "\t" . '<meta property="og:url" content="' . get_bloginfo( 'url' ) . '">' . "\n";
			echo "\t" . '<meta property="og:image" content="https://polen.me/polen/uploads/2021/06/cropped-logo.png">' . "\n";
			echo "\t" . '<meta property="og:locale" content="' . get_locale() . '">' . "\n";
			echo "\t" . '<meta property="og:site_name" content="' . get_bloginfo( 'title' ) . '">' . "\n";
			echo "\n";
		}
	} );
}
