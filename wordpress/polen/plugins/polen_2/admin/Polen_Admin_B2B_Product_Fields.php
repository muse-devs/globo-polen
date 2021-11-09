<?php
namespace Polen\Admin;

class Polen_Admin_B2B_Product_Fields
{
    const ACTION_NAME = 'polen_custom_fields_b2b';
    const TAB_NAME = 'polen_b2b_tab';
    const TAB_CONTENT_NAME = 'polen_b2b_tab_data';
    const FIELD_NAME_IS_B2B = 'polen_is_b2b';
    const FIELD_NAME_ENABLED_B2B = 'polen_enabled_b2b';

    public function __construct( bool $static = false )
    {
        if( $static ) {
            add_filter( 'woocommerce_product_data_tabs', array( $this, 'tabs' ) );
            add_filter( 'woocommerce_product_data_panels', array( $this, 'tab_content' ) );
            
            //Action Customizada pois é preciso desabilitar a action padrao
            //woocommerce_update_product pois ela entra em Loop
            //por isso dessa action: Includes\Polen_Woocommerce
            add_action( self::ACTION_NAME, array( $this, 'on_product_save' ) );
        }
    }


    public function tabs( $array ){
        $array[ self::TAB_NAME ] = array(
            'label'    => 'B2B',
            'target'   => self::TAB_CONTENT_NAME,
            'class'    => array(),
            'priority' => 100,
        );
        return $array;
    }



    public function tab_content() {
        global $product_object; ?>
        <div id="<?= self::TAB_CONTENT_NAME; ?>" class="panel woocommerce_options_panel hidden">
            <div class='options_group'>
            <?php
                woocommerce_wp_checkbox(
                    array(
                        'id'          => self::FIELD_NAME_IS_B2B,
                        'value'       => $product_object->get_meta( self::FIELD_NAME_IS_B2B ) == 'yes' ? 'yes' : 'no',
                        'label'       => 'Destaque para Empresas',
                        'description' => 'Perfil do talento vai aparecer na página /empresas',
                        'desc_tip'    => true,
                        'cbvalue'     => 'yes',
                    )
                );
            ?>
            </div>
            <div class='options_group'>
            <?php
                woocommerce_wp_checkbox(
                    array(
                        'id'          => self::FIELD_NAME_ENABLED_B2B,
                        'value'       => $product_object->get_meta( self::FIELD_NAME_ENABLED_B2B ) == 'yes' ? 'yes' : 'no',
                        'label'       => 'Disponível para Empresas',
                        'description' => 'O botão de pedir vídeo para empresa vai estar habilitado.',
                        'desc_tip'    => true,
                        'cbvalue'     => 'yes',
                    )
                );
            ?>
            </div>
        </div> <?php
    }


    public function on_product_save( $product_id )
    {
        if( is_admin() ) {
            $screen = get_current_screen();
            if ( $screen->base == 'post' && $screen->post_type == 'product' ) {
                $product     = wc_get_product( $product_id );
                $is_b2b      = strip_tags( $_POST[ self::FIELD_NAME_IS_B2B ] );
                $enabled_b2b = strip_tags( $_POST[ self::FIELD_NAME_ENABLED_B2B ] );
                
                $this->save_meta($product, $is_b2b,      self::FIELD_NAME_IS_B2B );
                $this->save_meta($product, $enabled_b2b, self::FIELD_NAME_ENABLED_B2B );

                remove_action( self::ACTION_NAME, array( $this, 'on_product_save' ) );
                $product->save();
                add_action( self::ACTION_NAME, array( $this, 'on_product_save' ) );
            }
        }
    }


    private function save_meta( &$product, $value, $key )
    {
        if( !empty( $value ) ) {
            $product->update_meta_data( $key, $value );
        } else {
            $product->delete_meta_data( $key );
        }
    }
}