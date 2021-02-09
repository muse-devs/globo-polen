<?php

class Tallent {
    
    public function __construct( $static = false ) {
        if( $static ) {
            $this->tallent_slug = 'tallent';

            add_action( 'add_meta_boxes', array( $this, 'choose_tallent_metabox' ) );
            add_filter( 'save_post', array( $this, 'save_seller' ) );
        }
    }

    /**
     * Add choose tallent metabox
     */
    function choose_tallent_metabox(){
        add_meta_box( 'storeselect', __( 'Vendor', 'polen' ), array( $this, 'tallent_select' ), 'product', 'normal', 'core' );
    }

    /**
     * Metabox to select tallent on product edit
     */    
    function tallent_select( $post ) {
        global $user_ID;

        $user_query = new WP_User_Query( array( 'role' => 'tallent' ) );
        $tallents = $user_query->get_results();
        $current_tallent = empty( $post->ID ) ? '' : $post->post_author;
        ?>
        <select name="polen_choose_tallent" id="polen_choose_tallent">
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
    function save_seller( $post_id ){
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if( !current_user_can( 'edit_post' ) ) return;

        if( $_POST['post_type'] == 'product' ){
            $chosen_tallent = isset( $_POST['polen_choose_tallent'] )? $_POST[ 'polen_choose_tallent' ]: '';
            $post_author = sanitize_text_field( $chosen_tallent );

            if ( ! $post_author ) { return; }

            global $wpdb;
            $wpdb->update( 'wp_posts', array( 'post_author' => $post_author ), array( 'ID' => $post_id) );
        }	
    }

}

$tallent = new Tallent( true );