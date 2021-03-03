<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

use Polen\Includes\Polen_Update_Fields;

global $post;
$Talent_Fields = new Polen_Update_Fields();
$Talent_Fields = $Talent_Fields->get_vendor_data( $post->post_author );
$terms = wp_get_object_terms( get_the_ID(), 'product_cat' );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<!-- Cabeçalho do Artista -->
	<div class="row m-4">
		<div class="col-4">
			<h2><?php the_title(); ?></h2>
			<?php 
			$terms_ids = array();
			if( count( $terms ) > 0 ) {
				foreach( $terms as $k => $term ) {
					$terms_ids[] = $term->term_id;
					echo '<span style="background-color: #dedede; color: #303030; padding: 3px; margin: 5px;">' . $term->name . '</span>';
				}
			}
			?>
		</div>
		<div class="col">
			Responde em<br>
			<?php echo $Talent_Fields->tempo_resposta; ?>
		</div>
		<div class="col">
			Avaliações<br>
			5
		</div>
		<div class="col-3" style="text-align: right;">
			<?php echo woocommerce_template_single_add_to_cart(); ?>
		</div>
	</div>

	<!-- Vídeos -->
	<div class="row">
		<div class="col-5" style="height: 300px; background-color: #dedede; vertical-align: middle; text-align: center; padding: 15% 0 15% 0;">
			Vídeo 1
		</div>
		<div class="col" style="height: 300px; background-color: #dedede; vertical-align: middle; text-align: center; padding: 15% 0 15% 0; margin-left: 5px;">
			Vídeo 2
		</div>
		<div class="col" style="height: 300px; background-color: #dedede; vertical-align: middle; text-align: center; padding: 15% 0 15% 0; margin-left: 5px;">
			Vídeo 3
		</div>
	</div>

	<!-- Descrição? -->
	<div class="row">
		<div class="col m-5">
			<h3>Descrição</h3>
		</div>
	</div>

	<div class="row">
		<p><?php echo $Talent_Fields->descricao; ?></p>
	</div>

	<!-- Como funciona? -->
	<div class="row">
		<div class="col m-5 text-center">
			<h1>Como funciona?</h1>
		</div>
	</div>

	<div class="row">
		<p>Presenteie e surpreenda com vídeos personalizados.</p>
	</div>

	<div class="row" style="color: #000 !important;">
		<div class="col-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Conecte-se aos ídolos</h5>
					<p class="card-text">Peça um vídeo personalizado com o seu ídolo para celebar ocasiões especiais.</p>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Receba sua encomenda</h5>
					<p class="card-text">Ídolo recebe o seu pedido, atende e entrega pela plataforma.</p>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Mande para todo mundo</h5>
					<p class="card-text">Você pode enviar o vídeo para os amigos ou postar nas redes.</p>
				</div>
			</div>
		</div>
	</div>

	<p>&nbsp;</p>
	<!-- Artistas Relacionados -->
	<div class="row">
		<div class="col m-5 text-center">
			<h1>Talentos relacionados</h1>
		</div>
	</div>

	<div class="row" style="color: #000 !important;">
		<div class="col">
			<?php
			if( count( $terms_ids ) > 0 ) {
				$others = get_objects_in_term( $terms_ids, 'product_cat' );
				if( count( $others ) ) {
					foreach( $others as $k => $id ) {
						$product = wc_get_product( $id );
			?>
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"><?php echo $product->get_title(); ?></h5>
					<p class="card-text">Você pode enviar o vídeo para os amigos ou postar nas redes.</p>
				</div>
			</div>
			<?php
					}
				}
			}
			?>
		</div>
	</div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
