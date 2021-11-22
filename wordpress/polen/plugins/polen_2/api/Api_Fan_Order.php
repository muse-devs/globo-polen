<?php
namespace Polen\Api;


class Api_Fan_Order
{

    /**
     * 
     * @param WP_REST_Request
     */
    public function get_items( $request )
    {
		$customer_orders = $this->get_orders_by_user_logged( 1 );
        return $customer_orders;
    }


    private function get_orders_by_user_logged( $current_page )
    {
        $customer_orders = wc_get_orders(
			apply_filters(
				'woocommerce_my_account_my_orders_query',
				array(
					'customer' => get_current_user_id(),
					'page'     => $current_page,
					'paginate' => true,
				)
			)
		);

        return $customer_orders;
    }
}