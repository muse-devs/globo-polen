<?php
/**
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p class="img_wrap">
	<img src="<?php echo get_template_directory_uri() . "/assets/img/email/boas-vindas.png"; ?>" alt="Menina segurando celular">
</p>

<?php
//Quando o usuário não existe e é criado no checkout
//é setada uma nova senha e enviado no email
$http_referer = filter_input( INPUT_POST, '_wp_http_referer' );
if( '/?wc-ajax=update_order_review' == $http_referer ) { ?>

<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), '' ); ?></p>
<p>Sua conta foi criada com sucesso!<br>
Por isso, viemos te desejar boas-vindas à Polen.</p>
<p>Agora você pode visualizar seus dados e acompanhar o status do seu pedido fazendo login com as credenciais abaixo.</p>
<p>Recomendamos que você altere a sua senha após o primeiro login para se manter protegido.</p>
<?php
	$user_id = $email->object->ID;
	if( $user_id ) {
			$user_new_password = wp_generate_password( 5, false ) . random_int( 0, 99 );
			wp_set_password( $user_new_password, $user_id )
			?>
<p>Email: <?= $user_login; ?><br>
Senha Provisória: <?=$user_new_password;?></p>
				
			<?php 
	}
} else {
?>

	<?php /* translators: %s: Customer username */ ?>
	<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $user_login ) ); ?></p>
	<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
	<p><?php printf( esc_html__( 'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'woocommerce' ), esc_html( $blogname ), '<strong>' . esc_html( $user_login ) . '</strong>', make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>


	<?php
}
?>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

?>
	<p class="btn_wrap">
		<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="btn" target="_blank">Ir para o Minha conta</a>
	</p>
<?php

do_action( 'woocommerce_email_footer', $email );
