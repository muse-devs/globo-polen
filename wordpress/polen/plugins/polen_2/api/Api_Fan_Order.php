<?php
namespace Polen\Api;

use Polen\Includes\Debug;
use Polen\Includes\Cart\Polen_Cart_Item_Factory;


class Api_Fan_Order
{

    /**
     * 
     * @param WP_REST_Request
     */
    public function get_items( $request )
    {
        if( empty( $current_page ) ) {
            $current_page = 1;
        }
		$customer_orders = $this->get_orders_by_user_logged( get_current_user_id(), $current_page );
        Debug::def($customer_orders);
        $new_orders = [];
        if( $customer_orders->total > 0 ) {
            foreach( $customer_orders->orders as $order ){
                $new_orders[] = $this->prepare_item_for_response( $order, $request );
            }
        }
        $customer_orders->orders = $new_orders;
        return $customer_orders;
    }


    /**
     * 
     */
    private function get_orders_by_user_logged( $user_id, $current_page = 1 )
    {
        $customer_orders = wc_get_orders(
			apply_filters(
				'woocommerce_my_account_my_orders_query',
				array(
					'customer' => $user_id,
					'page'     => $current_page,
					'paginate' => true,
				)
			)
		);

        return $customer_orders;
    }



    /**
     * 
     * @param WC_Order
     * @param WP_REST_Request
     */
    public function prepare_item_for_response( \WC_Order $order, $request )
    {
        $post_data = array();

        $post_data[ 'order_id' ]              = $order->get_id();
        $post_data[ 'product_thumbnail_url' ] = $this->get_talent_thumbnail_url_by_order( $order );
        $post_data[ 'order_status' ]          = $order->get_status();
        $post_data[ 'price' ]                 = $order->calculate_totals();
        $post_data[ 'data' ]                  = $order->get_date_created()->date('d/m/Y');

        return $post_data;
    }


    /**
     * 
     * @param WC_Product
     */
    private function get_talent_thumbnail_url_by_order( $order )
    {
        $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
        $product = $cart_item->get_product();
        if( empty( $product) ) {
            return '';
        }
        $thumb = wp_get_attachment_image_url( $product->get_image_id() );
        return $thumb;
    }
}