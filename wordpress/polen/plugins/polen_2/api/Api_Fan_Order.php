<?php
namespace Polen\Api;

use Exception;
use Order_Class;
use Polen\Includes\Debug;
use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Polen_Order_Review;
use WP_REST_Response;

class Api_Fan_Order extends Api_Order
{

    /**
     * 
     * @param WP_REST_Request
     */
    public function get_items( $request )
    {
        $params = $request->get_params();

		$customer_orders = $this->get_orders_by_user_logged( get_current_user_id(), $params );
        $new_orders = [];
        if( $customer_orders->total > 0 ) {
            foreach( $customer_orders->orders as $order ){
                $new_orders[] = $this->prepare_item_for_response( $order, $request );
            }
        }
        $customer_orders->orders = $new_orders;
        $data = array(
            'items' => $customer_orders->orders,
            'total' => $customer_orders->total,
            'current_page' => $request->get_param('paged') ?? 1,
            'per_page' => 10,
        );
        return api_response( $data );
        // return $customer_orders;
    }


    /**
     * 
     * @param WP_REST_Request
     */
    public function get_item( $request )
    {
        $order_id = intval( $request[ 'id' ] );
        if( empty( $order_id ) ) {
            wp_send_json_error( 'Compra inválida', 404 );
            wp_die();
        }

        $order = wc_get_order( $order_id );
        if( empty( $order ) ) {
            wp_send_json_error( 'Compra inválida', 404 );
            wp_die();
        }

        if( !$this->verify_order_belongs_user_logged( $order ) ) {
            wp_send_json_error( 'Compra inválida', 404 );
            wp_die();
        }

        $order_response = $this->prepare_item_for_response( $order, $request );
        $order_response[ 'status_flow' ] = $this->get_status_flow( $order );

        return api_response( $order_response );
    }


    /**
     * Criar um OrderReview com o usuário logado
     * 
     */
    public function create_order_review( \WP_REST_Request $request )
    {

        $user_id  = get_current_user_id();
        $rate     = filter_var( $request->get_param( 'rate' )    , FILTER_SANITIZE_NUMBER_INT );
        $comment  = filter_var( $request->get_param( 'comment' ), FILTER_SANITIZE_STRING );
        $order_id = filter_var( $request[ 'id' ], FILTER_SANITIZE_NUMBER_INT );
        $approved = '0';

        try {
            if( empty( $rate ) ) {
                throw new Exception( 'A nota é obrigatória', 401 );
            }
            $order = wc_get_order( $order_id );
            $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
            $talent_id = $cart_item->get_talent_id();
        
            $order_review = new Polen_Order_Review();
            $order_review->set_user_id( $user_id );
            $order_review->set_comment_karma( $rate );
            $order_review->set_rate( $rate );
            $order_review->set_comment_content( $comment );
            $order_review->set_order_id( $order_id );
            $order_review->set_comment_approved( $approved );
            $order_review->set_talent_id( $talent_id );

            $order_review->save();
            
            return api_response( 'Comentário criado com sucesso. Em análise', 201 );
        } catch ( \Exception $e ) {
            return api_response( $e->getMessage(), $e->getCode() );
        }
    }


    /**
     * 
     */
    protected function verify_order_belongs_user_logged( $order )
    {
        if( $order->get_customer_id() === wp_get_current_user() ) {
            return true;
        }
        return false;
    }


    /**
     * Pega as orders de um usuário.
     */
    private function get_orders_by_user_logged( $user_id, $param )
    {
        $current_page = intval( $param[ 'paged' ] ) ?? 1;
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
        $post_data[ 'talent_thumbnail_url' ]  = $this->get_talent_thumbnail_url_by_order( $order );
        $post_data[ 'talent_name' ]           = $this->get_product_name_by_order( $order );
        $post_data[ 'talent_slug' ]           = $this->get_product_slug_by_order( $order );
        $post_data[ 'order_status' ]          = $order->get_status();
        $post_data[ 'order_status_name' ]     = wc_get_order_status_name( $order->get_status() );
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


    /**
     * 
     * @param WC_Order
     */
    private function get_product_name_by_order( $order )
    {
        $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
        $product = $cart_item->get_product();
        if( empty( $product ) ){ 
            return '';
        }
        return $product->get_title();
    }


    /**
     * 
     * @param WC_Order
     */
    private function get_product_slug_by_order( $order )
    {
        $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
        $product = $cart_item->get_product();
        if( empty( $product ) ){ 
            return '';
        }
        return $product->get_sku();
    }

    /**
     * 
     */
    public function check_permission_get_items( $request )
    {
        if( 0 == get_current_user_id() || empty( get_current_user_id() ) ) {
            return false;
        }
        return true;
    }

    /**
     * 
     */
    public function check_permission_get_item( $request )
    {
        $order_id = $request[ 'id' ];
        $order = wc_get_order( $order_id );
        if( empty( $order ) ) {
            return false;
        }
        if( ( 0 == get_current_user_id() || empty( get_current_user_id() ) ) 
            || $order->get_customer_id() !== get_current_user_id() ) {
            return false;
        }
        return true;
    }
}