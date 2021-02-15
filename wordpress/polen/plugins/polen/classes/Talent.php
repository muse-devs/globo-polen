<?php

class Talent {
    
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
        }
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

        $user_query = new WP_User_Query( array( 'role' => 'user_talent' ) );
        $tallents = $user_query->get_results();
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
     * Save tallent
     */
    function save_talent_on_product( $post_id ){
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if( !current_user_can( 'edit_post' ) ) return;

        if( isset( $_POST['post_type'] ) && ( $_POST['post_type'] == 'product' ) ){
            $chosen_tallent = isset( $_POST['polen_choose_talent'] )? $_POST[ 'polen_choose_talent' ]: '';
            $post_author = sanitize_text_field( $chosen_tallent );

            if ( ! $post_author ) { return; }

            global $wpdb;
            $wpdb->update( 'wp_posts', array( 'post_author' => $post_author ), array( 'ID' => $post_id) );
        }	
    }

    public function create_talent_product( $user_id ){
        $user_data = get_userdata( $user_id );
        $user_roles = $user_data->roles;
        
        //verify if user is a talent
        if ( in_array( 'user_talent', $user_roles, true ) ) {
            update_user_meta( $user_id, 'talent_enabled', '0' );
            //verify if the talent has a product
            $user_product = new WP_Query( array( 'author' => $user_id ) );
            if ( !$user_product->have_posts() ) {
                $user = get_user_by( 'ID', $user_id );
                $product = new WC_Product_Simple();
                $product->set_name( $user->first_name . ' ' . $user->last_name );
                $product->set_status( 'draft' );
                $product->set_slug( sanitize_title( $user->first_name . ' ' . $user->last_name ) );
                $product->set_virtual( true );
                $product->save();
                $id = $product->get_id();
                
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
}

$tallent = new Talent( true );
