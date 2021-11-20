<?php
namespace Polen\Includes\Products;

use WC_Product;

class Polen_Product_Base implements Polen_IProduct
{
    /**
     * Produto WC_Product que a classe vai representar
     */
    public $product;

    public function __construct( WC_Product $product = null )
    {
        $this->product = $product;
    }

    /**
     * 
     * @param html
     */
    public function get_home_card_badget_icon()
    {
        return '';
    }

    /**
     * 
     * @return string
     */
    public function get_home_card_badget_text()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_home_card_badget()
    {
        return '';
    }

    /**
     * @param hmtl
     */
    public function get_home_card_badget_html()
    {
        return '';
    }

    /**
     * 
     * @return string 
     */
    public function get_home_card_price()
    {
        return '';
    }

    /**
     * 
     * @return string 
     */
    public function get_home_card_image_url()
    {
        return '';
    }

    /**
     * 
     * @return string 
     */
    public function get_home_card_title()
    {
        return '';
    }

    /**
     * 
     * @return string 
     */
    public function get_home_card_subtitle()
    {
        return '';
    }

    /**
     * 
     * @return string 
     */
    public function get_home_card_base_color_rgb_background()
    {
        return '';
    }

    /**
     * 
     * @return string 
     */

    //Pagina de Detalhes
    /**
     * 
     */
    public function get_detail_thumbnail()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_title()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_price()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_image_background()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_button_cart()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_link_business()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_deadline()
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_deadline_formated( string $format = 'd//m/Y' )
    {
        return '';
    }

    /**
     * 
     * @return html
     */
    public function get_detail_review_note()
    {
        return '';
    }
    
    /**
     * 
     * @return html
     */
    public function get_detail_review_link()
    {
        return '';
    }
    
    /**
     * 
     * @return html
     */
    public function get_detail_description()
    {
        return '';
    }
    
    /**
     * 
     * @return html
     */
    public function get_detail_share_facebook_link()
    {
        return '';
    }
    
    /**
     * 
     * @return html
     */
    public function get_detail_share_twitter_link()
    {
        return '';
    }
    
    /**
     * 
     * @return html
     */
    public function get_detail_share_whatsapp_link()
    {
        return '';
    }
    
    /**
     * 
     * @return html
     */
    public function get_detail_how_it_works_html()
    {
        return '';
    }
    
    /**
     * 
     * @return html
     */
    public function get_detail_related_product()
    {
        return '';
    }
}
