<?php
defined('ABSPATH') || exit;

$logged_user = wp_get_current_user();
if (in_array('user_talent',  $logged_user->roles)) {
	require get_template_directory() . '/woocommerce/myaccount/orders-talent.php';
} else {
	do_action('woocommerce_before_account_orders', $has_orders); ?>

	<?php if ($has_orders) : ?>

		<?php
		foreach ($customer_orders->orders as $customer_order) {
			$order      = wc_get_order($customer_order); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$item_count = $order->get_item_count() - $order->get_item_count_refunded();
			$product_name = '';
			$product_id = '';
			foreach ($order->get_items() as $item_id => $item) {
				$product_name = $item->get_name();
				$product_id = $item->get_product_id();
				break;
			}
		?>
			<div class="row mt-4">
				<div class="col-12">
					<div class="talent-card alt">
						<div class="row px-3">
							<div class="col-12">
								<div class="row d-flex justify-content-start">
									<div>
										<div class="image-cropper">
											<?php
											$thumbnail = get_the_post_thumbnail_url($product_id, 'post-thumbnail');
											?>
											<img src="<?php echo $thumbnail; ?>" class="profile-pic">
										</div>
									</div>
									<div class="col">
										<div class="order-title"><?php echo  $item->get_name(); ?></div>
										<div class="cat">
											<?php
											global $post;
											$terms = get_the_terms($product_id, 'product_cat');
											if (isset($terms[0]->name)) {
												echo $terms[0]->name;
											}
											?>
										</div>
										<div class="status mt-3">
											<?php echo wc_get_order_status_name($order->get_status()); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 text-center mt-4 mb-3">
								<div class="row">
									<?php 
									if( $order->get_status() == 'completed' ){ ?>
										<a href="/my-account/watch-video" class="btn btn-primary btn-lg btn-block">
											Visualizar vídeo
										</a>
									<?php
									}else{	?>
										<a href="<?php echo $order->get_view_order_url(); ?>" class="btn btn-primary btn-lg btn-block">
											Acompanhar pedido
										</a>
									<?php	
									}
									?>
								</div>
							</div>
							<div class="col-12 text-center">
								<p class="order-number">Número do pedido: <strong><?php echo $order->get_order_number(); ?></strong></p>
							</div>
						</div>
					</div>
				</div>
			</div>


			<!--tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr($order->get_status()); ?> order">
					<?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>">
							<?php if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) : ?>
								<?php do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order); ?>

							<?php elseif ('order-number' === $column_id) : ?>
								<a href="<?php echo esc_url($order->get_view_order_url()); ?>">
									<?php echo esc_html(_x('#', 'hash before order number', 'woocommerce') . $order->get_order_number()); ?>
								</a>

							<?php elseif ('order-date' === $column_id) : ?>
								<time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time>

							<?php elseif ('order-status' === $column_id) : ?>
								<?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>

							<?php elseif ('order-total' === $column_id) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
								echo wp_kses_post(sprintf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'), $order->get_formatted_order_total(), $item_count));
								?>

							<?php elseif ('order-actions' === $column_id) : ?>
								<?php
								$actions = wc_get_account_orders_actions($order);

								if (!empty($actions)) {
									foreach ($actions as $key => $action) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
										echo '<a href="' . esc_url($action['url']) . '" class="woocommerce-button button ' . sanitize_html_class($key) . '">' . esc_html($action['name']) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr-->
		<?php
		}
		?>

		<?php do_action('woocommerce_before_account_orders_pagination'); ?>

		<?php if (1 < $customer_orders->max_num_pages) : ?>
			<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
				<?php if (1 !== $current_page) : ?>
					<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page - 1)); ?>"><?php esc_html_e('Previous', 'woocommerce'); ?></a>
				<?php endif; ?>

				<?php if (intval($customer_orders->max_num_pages) !== $current_page) : ?>
					<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page + 1)); ?>"><?php esc_html_e('Next', 'woocommerce'); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	<?php else : ?>
		<div class="row">
			<div class="col-12 text-center mt-3">
				<?php polen_box_image_message(TEMPLATE_URI . "/assets/img/list.png", __('No order has been made yet.', 'woocommerce')); ?>
				<a class="woocommerce-Button btn btn-outline-light btn-lg btn-block mt-3" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>"><?php esc_html_e('Browse products', 'woocommerce'); ?></a>
			</div>
		</div>
	<?php endif; ?>

	<?php do_action('woocommerce_after_account_orders', $has_orders); ?>
<?php
}//fim do else da verificação do perfil
