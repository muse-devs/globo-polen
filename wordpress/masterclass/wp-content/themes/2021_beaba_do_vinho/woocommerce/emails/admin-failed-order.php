<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%" style='color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: Poppins, "Helvetica Neue", Helvetica, Arial, sans-serif;'>
    <tr>
        <td align="center">
            <h2>Pagamento Rejeitado!</h2>
        </td>
    </tr>
    <tr>
        <td align="center">
            <img src="<?php echo TEMPLATE_URI.'/assets/img/email/failed.png'?>" style="width: 200px;"></img>
        </td>
    </tr>
    <tr>
        <td align="left">
            <p>
                Olá,<br>
                Houve um problema com seu pagamento e não conseguimos confirmar a sua vaga na masterclass Beabá do Vinho com Ronnie Von.<br>
                Não quer perder essa aula? <a href="https://polen.me/masterclass" target="_blank">Clique Aqui!</a> para realizar uma nova compra:<br>
            </p>
        </td>
    </tr>
</table>

<?php
/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
