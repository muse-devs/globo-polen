<?php
namespace Polen\Includes\Cart;

interface Polen_ICart_Item
{
    const META_KEY_ALLOW_VIDEO_ON_PAGE = 'allow_video_on_page';
    const VALUE_ALLOW_VIDEO_ON_PAGE_ON = 'on';
    const VALUE_ALLOW_VIDEO_ON_PAGE_OFF = 'off';


    public function get_color_primery_check_item();
    public function get_information_order_text();
    public function get_information_order_text_edit();
    
    public function get_text_information_video();
    public function get_text_information_video_text_edit();

    public function get_offered_by_html();
    public function get_offered_by();
    public function get_video_to();
    public function get_name_to_video();
    public function get_email_to_video();
    public function get_video_category();
    public function get_instructions_to_video( $context );
    public function get_instructions_to_video_input();
    public function get_instructions_to_video_putput();
    public function get_instructions_to_video_filter();
    public function get_phone();
    public function get_allow_video_on_page();
    public function is_first_order();
    public function get_deadline();
    public function get_deadline_interval();
    public function set_deadline( $deadline );

    public function get_allowed_items();

    public function get_resume_base_color();
    public function get_resume_thumbnail_url();
    public function get_resume_thumbnai_html();
    public function get_resume_title();
    public function get_resume_text_for_price();
    public function get_resume_price();
    public function get_resume_price_html();

    public function get_cart_allow_video_on_page_html();

    public function get_cart_button_title();
    public function get_cart_button_html();

}