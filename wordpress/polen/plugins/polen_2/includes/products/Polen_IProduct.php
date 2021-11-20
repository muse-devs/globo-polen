<?php

namespace Polen\Includes\Products;

interface Polen_IProduct
{
    /*
    public function is_social();
    public function get_social_name();
    public function is_campaign();
    public function get_campaign_name();
    */

    //Home Cards
    public function get_home_card_badget_text();
    public function get_home_card_badget_icon();
    public function get_home_card_badget_html();
    public function get_home_card_price();
    public function get_home_card_image_url();
    public function get_home_card_title();
    public function get_home_card_subtitle();
    public function get_home_card_base_color_rgb_background();

    //Pagina de Detalhes
    public function get_detail_thumbnail();
    public function get_detail_title();
    public function get_detail_price();
    public function get_detail_image_background();
    public function get_detail_button_cart();
    public function get_detail_link_business();
    public function get_detail_deadline();
    public function get_detail_deadline_formated( string $format = 'd//m/Y' );
    public function get_detail_review_note();
    public function get_detail_review_link();
    public function get_detail_description();
    public function get_detail_share_facebook_link();
    public function get_detail_share_twitter_link();
    public function get_detail_share_whatsapp_link();
    public function get_detail_how_it_works_html();
    public function get_detail_related_product();
}
