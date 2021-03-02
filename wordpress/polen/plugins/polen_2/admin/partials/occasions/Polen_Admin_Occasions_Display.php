<?php

namespace Polen\Admin\Partials\Occasions;

use \Polen\Includes\Polen_Occasion_List;
class Polen_Admin_Occasions_Display extends \WP_List_Table
{

    public function prepare_items()
    {
        $search = trim( filter_input( INPUT_GET, 's' ) );
        $occasion_list_repository = new Polen_Occasion_List();
        $occasion_list = $occasion_list_repository->get_occasion( $search, $this->get_orderby(), $this->get_order() );
        $this->items = $occasion_list;
        
        $this->_column_headers = array( 
            $this->get_columns(),           // columns
            array(),                        // hidden
            $this->get_sortable_columns(),  // sortable
        );
    }
    
    public function get_columns()
    {
        return [
            'type' => 'Tipo',
            'description' => 'Descrição'
        ];
    }
    
    private function get_orderby()
    {
        $orderby = trim( filter_input( INPUT_GET, 'orderby' ) );
        return 'type';
    }
    
    private function get_order()
    {
        $order = trim( filter_input( INPUT_GET, 'order' ) );
        return $order;
    }
    
    public function column_type($param)
    {
        return $param->type;
    }
    
    public function column_description($param)
    {
        return $param->description;
    }
    
    public function get_sortable_columns()
    {
        return [
            'type' => [ 'type', true ]
        ];
    }
    

    /**
     * Mostra o form de adicao de uma Occasion
     */
    public function show_form_create_occasion()
    { ?>
        <form action="" method="post">
            <?php wp_nonce_field('occasion_new', '_wpnonce', true, true); ?>
            <table class="wp-list-table widefat fixed striped table-view-list toplevel_page_occasion-list">
                <tr>
                    <td>Categoria <input type="text" name="occasion_category" value="" required></td>
                    <td>Descrição <input type="text" name="occasion_description" value="" required></td>
                    <td><input type="submit" value="cadastrar" class="button-primary"></td>
                </tr> 
            </table>
        </form>
        <div>&nbsp;</div>
    <?php
    }
    
    
    
    /**
     * Metodo que apresenta o search_box da WP_List_Table
     * @param Polen_Admin_Occasions_Display $occasion_display
     */
    public function show_form_search_occasion()
    {
        $page = esc_attr( $_REQUEST['page'] );
        
        echo '<form action="" method="GET">';
        echo "<input type=\"hidden\" name=\"page\" value=\"{$page}\"/>";
        $this->search_box( 'Occasions Search', 'search_occasion' );
        echo '</form>';
    }
    
    
}
