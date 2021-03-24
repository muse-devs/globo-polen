<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Polen\Includes;


class Polen_Talent
{
    
    public function __construct( $static = false ) {
        if( $static ) {
            $this->tallent_slug = 'talent';
            add_action( 'add_meta_boxes', array( $this, 'choose_talent_metabox' ) );
            add_filter( 'save_post', array( $this, 'save_talent_on_product' ) );
            add_action( 'rest_api_init', array( $this, 'tallent_rest_itens' ) );   
            add_action( 'user_register', array( $this, 'create_talent_product' ) );
            add_action( 'admin_menu', array( $this, 'talent_submenu' ) );
            add_filter( 'manage_users_columns', array( $this, 'talent_filter_column' ),10, 1 );
            add_filter( 'manage_edit-product_columns', array( $this, 'talent_filter_product_column' ),10, 1 );
            add_filter( 'manage_users_custom_column', array( $this, 'talent_custom_users_value' ), 10, 3 );
            add_action( 'init', array( $this, 'talent_taxonomy' ) );
            add_filter( 'manage_edit-shop_order_columns', array( $this, 'show_talent_order_column' ), 20 );
            add_action( 'manage_shop_order_posts_custom_column', array( $this, 'talent_column_content' ), 20, 2 );

            /**
             * Modifica a URL do Talento (Usuário)
             */
            // add_action( 'init', array( $this, 'rewrites' ) );

            /**
             * Modifcar o texto do botão comprar
             */
            add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'change_single_add_to_cart_text' ) );  // Single de produtos
            add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'change_custom_product_add_to_cart_text' ) ); // Archive (listagem) de produtos

            /**
             * Define que o usuário só pode comprar um produto por vez
             */
            add_filter( 'woocommerce_is_sold_individually', array( $this, 'sold_individually' ), 10, 2 );

            /**
             * Remove o notice ao adicionar o produto no carrinho.
             */
            add_filter( 'wc_add_to_cart_message_html', '__return_false' );
            
            /**
             * No login do talento redirecionamos para my-account/orders/ e não para o wp_admin
             */
            add_filter( 'login_redirect', array( $this, 'login_redirect' ), 11, 3 );

            /**
             * Busca por talento
             */
            add_filter( 'pre_get_posts', array( $this, 'searchTalent' ) );

        }
    }

    public function rewrites() {
        global $wp_rewrite;
        $wp_rewrite->author_base = $this->tallent_slug ;
        add_rewrite_rule( $this->tallent_slug . '/([^/]+)/?$', 'index.php?' . $this->tallent_slug . '=$matches[1]', 'top' );
        add_rewrite_rule( $this->tallent_slug . '/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?' . $this->tallent_slug . '=$matches[1]&paged=$matches[2]', 'top' );
    }

    /**
     * Add choose tallent metabox
     */
    function choose_talent_metabox(){
        add_meta_box( 'storeselect', __( 'Talento', 'polen' ), array( $this, 'talent_select' ), 'product', 'normal', 'core' );
    }

    /**
     * Metabox to select tallent on product edit
     */    
    function talent_select( $post ) {
        global $user_ID;

        $tallents = get_users( array( 'role' => 'user_talent' ) );
        $current_tallent = empty( $post->ID ) ? '' : $post->post_author;
        ?>
        <select name="polen_choose_talent" id="polen_choose_talent">
            <?php 
            foreach ( $tallents as $key => $user): 
                $selected = '';
                if( $current_tallent == $user->ID ){
                    $selected = "selected='selected'";
                }
            ?>
                <option value="<?php echo esc_attr( $user->ID ) ?>" <?php echo $selected;?> ><?php echo $user->display_name; ?></option>
            <?php 
            endforeach ?>
        </select>
        <?php
    }

    /**
     * Save tallent as product author
     */
    function save_talent_on_product( $post_id ){
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if( !current_user_can( 'edit_post' ) ) return;

        if( isset( $_POST['post_type'] ) && ( $_POST['post_type'] == 'product' ) ){
            $chosen_tallent = isset( $_POST['polen_choose_talent'] )? $_POST[ 'polen_choose_talent' ]: '';
            $post_author = sanitize_text_field( $chosen_tallent );

            if ( ! $post_author ) { return; }

//            global $wpdb;
//            $wpdb->update( 'wp_posts', array( 'post_author' => $post_author ), array( 'ID' => $post_id) );
            $this->set_product_to_talent( $post_id, $post_author );
        }	
    }

    public function create_talent_product( $user_id ){
        $user_data = get_userdata( $user_id );
        $user_roles = $user_data->roles;
        
        $polen_update_field = new Polen_Update_Fields();
        
        //verify if user is a talent
        if ( in_array( 'user_talent', $user_roles, true ) ) {
            update_user_meta( $user_id, 'talent_enabled', '0' );
            $vendor_data = $polen_update_field->get_vendor_data( $user_id );
            $sku = $vendor_data->talent_alias ?? null;
            //verify if the talent has a product
            $user_product = new \WP_Query( array( 'author' => $user_id ) );
            if ( !$user_product->have_posts() ) {
                $user = get_user_by( 'ID', $user_id );
                $product = new \WC_Product_Simple();
                $product->set_name( $user->first_name . ' ' . $user->last_name );
                $product->set_status( 'draft' );
                $product->set_slug( sanitize_title( $user->first_name . ' ' . $user->last_name ) );
                $product->set_sku( $sku );
                $product->set_virtual( true );
                $product->set_sold_individually( 'yes' );
                $product->save();
                $id = $product->get_id();
                
                $this->set_product_to_talent($id, $user_id);
                
                if( $id <= 0 ){
                    trigger_error( "Falha ao criar produto do usuário" );
                }
            }
        }
    }

    public function tallent_rest_itens() {
		register_rest_field( 'talent_category', 'meta', 
			array(
				'get_callback' => function( $object ) { 
					return get_term_meta( $object['id'] );
				},
				'schema' => null,
			)
		);
    }
     
    public function talent_filter_product_column( $columns ) {
        unset( $columns[ 'is_in_stock' ] );
        return $columns;
    }
     
    public function talent_filter_column( $columns ) {
        unset( $columns[ 'posts' ] );
        $columns[ 'status' ] = 'Status';
        return $columns;
    }

    public function talent_submenu() {
        if( current_user_can( 'list_users' ) ){
            add_submenu_page( 'users.php', 'Talento', 'Talento', 'manage_options', 'users.php?role=user_talent'  );
            add_submenu_page( 'users.php', 'Categoria Talento', 'Categoria Talento', 'manage_options', 'edit-tags.php?taxonomy=talent_category' );
        }
    }

    public function talent_custom_users_value( $value, $column, $user_id ) {
        switch( $column ) {
            case 'status' :
                $talent_enabled = get_the_author_meta( 'talent_enabled', $user_id );
                $str_status = '-';
                if( $talent_enabled == '0' ){
                    $str_status = 'Desativado';
                }else if( $talent_enabled == '1' ){
                    $str_status = 'Ativo';
                }
                return $str_status;
            default:
        }
        return $value;
    }

    public function talent_taxonomy(){
        if( ! taxonomy_exists( 'talent_category' ) ) {
            register_taxonomy(
                'talent_category',
                'user',
                array( 
                    'public' => true,
                    'show_ui' => true,
                    'show_in_menu' => true, 
                    'query_var' => true,
                    'show_in_rest' => true, 
                    'labels' => array(
                        'name'		=> __( 'Categoria de Talento', 'polen' ),
                        'singular_name'	=>  __( 'Categoria de Talento', 'polen' ),
                        'menu_name'	=>  __( 'Categoria de Talento', 'polen' ),
                        'search_items'	=> __( 'Pesquisar Categoria de Talento', 'polen' ),
                        'all_items'	=>  __( 'Todas Categorias de Talento', 'polen' ),
                        'edit_item'	=>  __( 'Editar Categoria de Talento', 'polen' ),
                        'update_item'	=>  __( 'Atualizar Categoria de Talento', 'polen' ),
                        'add_new_item'	=>  __( 'Nova Categoria de Talento', 'polen' ),
                    ),
                    'update_count_callback' => function() {
                        return;
                    }
                )
            );
        }
    }
    
    public function set_product_to_talent($product_id, $talent_id)
    {
        global $wpdb;
        
        $result = $wpdb->update(
            'wp_posts',
            [ 'post_author' => $talent_id ],
            [ 'ID' => $product_id ]
        );
        
        if ( is_wp_error( $result ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Modifcar o texto do botão comprar na Single
     */
    public function change_single_add_to_cart_text() {
        global $post;
        $product = wc_get_product( $post->ID );
        $label = __( 'Pedir vídeo R$ ', 'polen' ) . number_format( (float) $product->get_price(), 2, ',', '.' );
        return $label;
    }

    /**
     * Modifcar o texto do botão comprar no archive (listagem)
     */
    public function change_custom_product_add_to_cart_text() {
        global $post;
        $product = wc_get_product( $post->ID );
        $label = __( 'Pedir vídeo R$ ', 'polen' ) . number_format( (float) $product->get_price(), 2, ',', '.' );
        return $label;
    }

    /**
     * Define que o usuário só pode comprar um produto por vez
     */
    public function sold_individually( $return, $product ) {
        $return = true;
        return $return;
    }

    public function show_talent_order_column( $columns ){
        $new_columns = array();
        foreach( $columns as $key => $column){
            $new_columns[$key] = $column;
            if( $key ==  'order_date' ){
                $new_columns['talent_product'] = __( 'Talento', 'polen');
            }
        }
        return $new_columns;
    }
    
    public function talent_column_content( $column, $post_id )
    {
        switch ( $column )
        {
            case 'talent_product' :
                $order = wc_get_order( $post_id );                  
                if ( $order ) {
                    foreach ( $order->get_items() as $item_id => $item ) {
                        echo $name = $item->get_name();
                    }
                }else{
                    echo '-';
                }
                break;
        }
    }

    public function get_talent_orders( $talent_id, $status = false ){
        if( $talent_id ) {
            global $wpdb;

            $sql_product = " SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'product' and post_author = ".$talent_id;
            $talent_products = $wpdb->get_results( $sql_product );

            if( is_countable( $talent_products ) && count( $talent_products ) > 0 ){ 
                $first_product = reset($talent_products);

                if( is_object( $first_product ) && isset( $first_product->ID ) ){
                    $sql = " SELECT order_items.order_id
                        FROM {$wpdb->prefix}woocommerce_order_items as order_items
                        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
                        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
                        WHERE posts.post_type = 'shop_order'
                            AND posts.post_status IN ( 'wc-on-hold' )
                            AND order_items.order_item_type = 'line_item'
                            AND order_item_meta.meta_key = '_product_id'
                            AND order_item_meta.meta_value = '$first_product->ID'";
                    $order_list = $wpdb->get_results( $sql );

                    if( is_countable( $order_list ) && count( $order_list ) == 0 ){
                        return false;
                    }else{
                        $obj = array();
                        foreach( $order_list as $obj_order ):
                            $obj['order_id'] = $obj_order->order_id;
                            $order = wc_get_order( $obj_order->order_id );

                            $obj['total'] = $order->get_formatted_order_total();
                            foreach ( $order->get_items() as $item_id => $item ) {
                                $obj['email'] = $item->get_meta( 'email_to_video', true );
                                $obj['instructions'] = $item->get_meta( 'instructions_to_video', true );
                                $obj['name'] = $item->get_meta( 'name_to_video', true );
                                $obj['from'] = $item->get_meta( 'offered_by', true );
                                $obj['category'] = $item->get_meta( 'video_category', true );
                            }

                            $robj[] = $obj;
                        endforeach;
                        return $robj;    
                    }        
                }

            }
            
        return false;
        }
    }
    
    
    /**
     * Se um talento tentar logar pelo wp-login.php sera redirecionado para /my-account/orders/
     * 
     * @param string $redirect_to
     * @param string $requested_redirect_to
     * @param WP_User | WP_Error $user
     * @return string
     */
    public function login_redirect( $redirect_to, $requested_redirect_to, $user )
    {
        if( is_wp_error( $user ) ) {
            return $requested_redirect_to;
        }
        
        if( $this->is_user_talent( $user ) ) {
            $redirect_to = site_url( '/my-account/orders/' );
        }
        
        return $redirect_to;
    }
    
    
    public function is_user_talent( \WP_User $user )
    {
        $roles = $user->roles;
        if( array_search( 'user_talent', $roles ) !== false ) {
            return true;
        }
        return false;
    }

    public function searchTalent( $query ) {
        if ($query->is_search && !is_admin() ) {
            $query->set( 'post_type', array('product') );
        }
        return $query;
    }
}
