<?php
namespace Polen\Includes\Checkout;

interface Polen_ICheckout
{
    public function get_cupom_html();
    
    public function get_resume_thumbnail_url();
    public function get_resume_thumbnail();
    public function get_resume_title();
    public function get_resume_text_for_price();
    public function get_resume_price();
    public function get_resume_price_html();
    public function get_resume_value();
    public function get_resume_discount();
    public function get_resume_discount_html();
    public function get_resume_total();
    public function get_resume_total_html();

    public function get_resume_offered_by_html();
    public function get_resume_offered_by();
    public function get_resume_video_to();
    public function get_resume_name_to_video();
    public function get_resume_email_to_video();
    public function get_resume_video_category();
    public function get_resume_video_category_html();
    public function get_resume_instructions_to_video();
    public function get_resume_instructions_to_video_html();
    public function get_resume_allow_video_on_page();
    public function get_resume_allow_video_on_page_html();

    public function get_terms_conditions();
    public function get_terms_conditions_html();
    
    public function get_button_finish_order_text();
    public function get_button_finish_order_html();

}