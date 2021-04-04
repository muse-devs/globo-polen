<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Polen
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="container site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'polen' ); ?></a>

	<header id="masthead" class="row pt-4 pb-3">
		<div class="col-6 col-sm-6 d-flex align-items-center">
			<?php the_custom_logo(); ?>
		</div>
		<div class="col-6 col-sm-6 d-flex justify-content-end align-items-center">
			<?php //get_search_form(); ?>
			<div class="ml-2">
				<div class="dropdown">
					<?php
					if( is_user_logged_in() ){
						$user_name = wp_get_current_user();
					?>
						<a class="dropbtn text-truncate">
							<i class="bi bi-person-circle"></i>
							<?php echo $user_name->display_name;?>
						</a>
						<div class="dropdown-content">
							<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">Minha conta</a>
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">Meus pedidos</a>
							<a href="<?php echo esc_url( wc_customer_edit_account_url() );?>">Meus dados</a>
							<a href="<?php echo esc_url( wp_logout_url() );?>">Sair</a>
						</div>
					<?php
					}
					else{ ?>
						<a class="btn btn-outline-light" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
							Login
						</a>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->
