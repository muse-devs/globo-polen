<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Polen\Includes;

use \Polen\Admin\Partials\Occasions\Polen_Admin_Occasions_Display;


class Polen_Occasion_List
{
    
    public function __construct( $static = false ) {
        if( $static ) {
            add_action( 'admin_menu', [ $this, 'menu_occasion_list' ] );
            add_action( 'wp_ajax_get_occasion_description', array( $this, 'get_occasion_description' ) );
            add_action( 'wp_ajax_nopriv_get_occasion_description', array( $this, 'get_occasion_description' ) );
            //add_action( 'wp_ajax_update_occasion_item', array( $this, 'update_occasion_on_cad' ) ); 
        }
    }

    /**
     * Add occasion menu
     */
    public function menu_occasion_list(){
        add_menu_page( 'Categorias de Vídeo - Cadastro', 'Categorias de Vídeos', 'manage_options', 'occasion-list', [ $this, 'list_inserted_occasion' ], 'dashicons-editor-alignright' );
    }

    
    
    /**
     * Screen to insert occasion and description
     */
    public function list_inserted_occasion(){ 
//        require PLUGIN_POLEN_DIR . '/admin/partials/occasions/occasion-list.php';
        $this->handler_post_create_occasion();
        add_action( 'admin_notices', [$this, 'my_error_notice'] );
        $occasion_display = new Polen_Admin_Occasions_Display();
        $occasion_display->prepare_items();
        
        echo '<div class="wrap">';
        echo '<h2>' . translate('Categorias de Vídeo') . '</h2>';
        
        $occasion_display->show_form_create_occasion();
        $occasion_display->show_form_search_occasion();
        $occasion_display->display();
        
        echo '</div>';
    }
    
    public function my_error_notice()
    { ?>
        <div class="error notice">
            <p><?= 'There has been an error. Bummer!'; ?></p>
        </div>
    <?php
    die('asdgfkajhfgdkajdfgkajhdfg');
    }
    
    
    /**
     * Metodo chamado quando uma tentativa de inserir uma Occasion
     */
    public function handler_post_create_occasion()
    {
        $occasion_category = trim( filter_input( INPUT_POST, 'occasion_category', FILTER_FLAG_EMPTY_STRING_NULL ) );
        $occasion_description = trim( filter_input( INPUT_POST, 'occasion_description', FILTER_SANITIZE_STRING ) );
        $_wpnonce = wp_verify_nonce( $_POST['_wpnonce'], 'occasion_new' );
        
        if( !empty($occasion_category) && !empty($occasion_description) && $_wpnonce === 1 ) {
            $this->set_occasion( $occasion_category, $occasion_description );
        }
    }

    /**
     * List all inserted occasions
     */
    public function get_occasion( $_query = null, $_orderby = null, $_order = 'ASC' ){
        global $wpdb;

        $order = ($_order === 'asc') ? 'ASC' : 'DESC';
        $orderby = ($_orderby === 'type') ? " ORDER BY $_orderby $order " : "";
        
        $query = !empty($_query) ? $wpdb->prepare(" AND (type LIKE '%%%s%%') ", $_query) : '';        
        $sql = "SELECT * FROM `" . $wpdb->base_prefix . "occasion_list` WHERE (1=1) {$query} {$orderby}";  

        $results = $wpdb->get_results( $sql );  
        return $results;
    }
    
    
    /**
     * 
     * @global type $wpdb
     * @param string $type
     * @param string $orderby
     * @param string $order
     * @return type
     */
    public function get_occasion_by_type( string $type ){
        global $wpdb;
        
        $sql = "SELECT type, description FROM `" . $wpdb->base_prefix . "occasion_list` WHERE type = %s LIMIT 1" ;
        $sql_prepared = $wpdb->prepare( $sql, trim( $type ) );
        $results = $wpdb->get_results( $sql_prepared );  
        return $results;
    }

    /**
     * Insert occasion
     */
    public function set_occasion( $type, $description ){
        if( !empty( $type ) && !empty( $description ) ){
            global $wpdb;
            $exists = $this->check_already_inserted( $type, $description );
            if( !$exists ){
                $inserted = $wpdb->insert( $wpdb->base_prefix."occasion_list", array( 'type' => trim( $type ), 'description' => trim( $description ) ) );

                if( $inserted > 0 ){
                    return "Cadastrado com sucesso!";
                }else{
                    return "Ocorreu um erro ao tentar cadastrar";
                }
            }else{
                return "Registro já cadastrado";
            } 
        }else{
            return "Está faltando dados";
        }   
    }

    public function check_already_inserted( $type, $description ){
        global $wpdb;
        $sql_prepared = $wpdb->prepare("SELECT COUNT(*) total  FROM `" . $wpdb->base_prefix . "occasion_list` WHERE type = %s AND description = %s;", trim( $type ), trim( $description ));
        $inserted = $wpdb->get_var( $sql_prepared );
        if( (int) $inserted > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function get_occasion_description(){
        if( isset( $_POST['occasion_type'] ) ){
            $occasion = $this->get_occasion_by_type( $_POST['occasion_type'] );

            if( !empty( $occasion ) ){
                echo wp_json_encode( array( 'success' => 1, 'response' => $occasion ) );
                die;
            }
        }
    }

    /*
  CREATE TABLE `db_polen`.`wp_occasion_list` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NULL,
  `description` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));
    */

}
