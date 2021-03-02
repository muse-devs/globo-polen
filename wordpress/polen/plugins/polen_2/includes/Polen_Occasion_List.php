<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Polen\Includes;


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
        require PLUGIN_POLEN_DIR . '/admin/partials/occasions/occasion-list.php';
    }

    /**
     * List all inserted occasions
     */
    public function get_occasion( $order = null ){
        global $wpdb;

        $sql = "SELECT * FROM `" . $wpdb->base_prefix . "occasion_list` ";  
        if( !empty( $order ) ){
            $sql .= " ORDER BY ". $order. " ASC ";
        }   

        $results = $wpdb->get_results( $sql );  
        return $results;
    }

    public function get_occasion_by_type( $type ){
        global $wpdb;

        $sql = "SELECT type, description FROM `" . $wpdb->base_prefix . "occasion_list` WHERE type = '". trim( $type ) ."' LIMIT 1" ;  
        $results = $wpdb->get_results( $sql );  
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
        $inserted = $wpdb->get_results("SELECT * FROM `" . $wpdb->base_prefix . "occasion_list` WHERE type = '". trim( $type ) ." AND description = '". trim( $description ) ."'");

        if( $inserted > 0 ){
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
