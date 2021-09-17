<?php
namespace Polen\Includes\Order;

class Polen_Order_Data_Store_CPT extends \WC_Order_Data_Store_CPT
{
    /**
	 * Get valid WP_Query args from a WC_Order_Query's query variables.
	 *
	 * @since 3.1.0
	 * @param array $query_vars query vars from a WC_Order_Query.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {

		// Map query vars to ones that get_wp_query_args or WP_Query recognize.
		$key_mapping = array(
			'customer_id'    => 'customer_user',
			'status'         => 'post_status',
			'currency'       => 'order_currency',
			'version'        => 'order_version',
			'discount_total' => 'cart_discount',
			'discount_tax'   => 'cart_discount_tax',
			'shipping_total' => 'order_shipping',
			'shipping_tax'   => 'order_shipping_tax',
			'cart_tax'       => 'order_tax',
			'total'          => 'order_total',
			'page'           => 'paged',
		);

		foreach ( $key_mapping as $query_key => $db_key ) {
			if ( isset( $query_vars[ $query_key ] ) ) {
				$query_vars[ $db_key ] = $query_vars[ $query_key ];
				unset( $query_vars[ $query_key ] );
			}
		}

		// Add the 'wc-' prefix to status if needed.
		if ( ! empty( $query_vars['post_status'] ) ) {
			if ( is_array( $query_vars['post_status'] ) ) {
				foreach ( $query_vars['post_status'] as &$status ) {
					$status = wc_is_order_status( 'wc-' . $status ) ? 'wc-' . $status : $status;
				}
			} else {
				$query_vars['post_status'] = wc_is_order_status( 'wc-' . $query_vars['post_status'] ) ? 'wc-' . $query_vars['post_status'] : $query_vars['post_status'];
			}
		}

		$wp_query_args = parent::get_wp_query_args( $query_vars );

		if ( ! isset( $wp_query_args['date_query'] ) ) {
			$wp_query_args['date_query'] = array();
		}
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array();
		}

		$date_queries = array(
			'date_created'   => 'post_date',
			'date_modified'  => 'post_modified',
			'date_completed' => '_date_completed',
			'date_paid'      => '_date_paid',
		);
		foreach ( $date_queries as $query_var_key => $db_key ) {
			if ( isset( $query_vars[ $query_var_key ] ) && '' !== $query_vars[ $query_var_key ] ) {

				// Remove any existing meta queries for the same keys to prevent conflicts.
				$existing_queries = wp_list_pluck( $wp_query_args['meta_query'], 'key', true );
				$meta_query_index = array_search( $db_key, $existing_queries, true );
				if ( false !== $meta_query_index ) {
					unset( $wp_query_args['meta_query'][ $meta_query_index ] );
				}

				$wp_query_args = $this->parse_date_for_wp_query( $query_vars[ $query_var_key ], $db_key, $wp_query_args );
			}
		}

		if ( isset( $query_vars['customer'] ) && '' !== $query_vars['customer'] && array() !== $query_vars['customer'] ) {
			$values         = is_array( $query_vars['customer'] ) ? $query_vars['customer'] : array( $query_vars['customer'] );
			$customer_query = $this->get_orders_generate_customer_meta_query( $values );
			if ( is_wp_error( $customer_query ) ) {
				$wp_query_args['errors'][] = $customer_query;
			} else {
				$wp_query_args['meta_query'][] = $customer_query;
			}
		}

		if ( isset( $query_vars['anonymized'] ) ) {
			if ( $query_vars['anonymized'] ) {
				$wp_query_args['meta_query'][] = array(
					'key'   => '_anonymized',
					'value' => 'yes',
				);
			} else {
				$wp_query_args['meta_query'][] = array(
					'key'     => '_anonymized',
					'compare' => 'NOT EXISTS',
				);
			}
		}

		if ( ! isset( $query_vars['paginate'] ) || ! $query_vars['paginate'] ) {
			$wp_query_args['no_found_rows'] = true;
		}

		return apply_filters( 'woocommerce_order_data_store_cpt_get_orders_query', $wp_query_args, $query_vars, $this );
	}
}