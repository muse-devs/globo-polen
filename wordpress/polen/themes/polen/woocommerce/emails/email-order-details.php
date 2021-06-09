<?php

/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
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

defined('ABSPATH') || exit;

$item = Polen\Includes\Cart\Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
?>

<div style="margin-bottom: 40px;">
	<table cellspacing="0" cellpadding="0" width="100%">
		<thead>
			<tr>
				<td width="33.3333333%">&nbsp;</td>
				<td width="33.3333333%">&nbsp;</td>
				<td width="33.3333333%">&nbsp;</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<p class="details_title">Valor</p>
					<span class="details_value"><?php echo $order->get_formatted_order_total(); ?></span>
				</td>
				<td>
					<p class="details_title">Tempo estimado</p>
					<span class="details_value">2 min</span>
				</td>
				<td>
					<p class="details_title">Válido por</p>
					<span class="details_value">30:00h</span>
				</td>
			</tr>
			<tr>
				<td>
					<p class="details_title">Vídeo de</p>
					<span class="details_value"><?php echo $item->get_offered_by(); ?></span>
				</td>
				<td valign="center">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/img/email/arrow.png ?>" alt="Seta para a direita">
				</td>
				<td>
					<p class="details_title">Para</p>
					<span class="details_value"><?php echo $item->get_name_to_video(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p class="details_title">Ocasião</p>
					<span class="details_value_small"><?php echo $item->get_video_category(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p class="details_title">e-mail de contato</p>
					<span class="details_value_small"><?php echo $item->get_email_to_video(); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p class="details_title">Instruções</p>
					<span class="details_value_small"><?php echo $item->get_instructions_to_video(); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div>
