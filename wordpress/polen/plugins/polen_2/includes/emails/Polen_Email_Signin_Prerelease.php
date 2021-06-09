<?php

namespace Polen\Includes\Emails;

class Polen_Email_Signin_Prerelease extends \WC_Email
{

    public function __construct()
    {
        // Email slug we can use to filter other data.
        $this->id          = 'polen_signin_prelounch';
        $this->title       = 'Email cadastro de prelançamento';
        $this->description = 'Email para cadastro de prelançamento';
        // For admin area to let the user know we are sending this email to customers.
        $this->customer_email = true;
        $this->heading     = 'Prelançamento';
        // translators: placeholder is {blogname}, a variable that will be substituted when email is sent out
        $this->subject     = sprintf( 'Você está na lista de espera da %s' , '{blogname}' );
        $this->email_type = 'html';
        // Template paths.
		$this->template_html  = 'emails/Polen_Signin_Prerelease.php';
		$this->template_plain = 'emails/plain/Polen_Signin_Prerelease.php';
		$this->template_base  = TEMPLATEPATH . 'woocommerce/';

        // $this->recipient = $email;
        $this->placeholders   = array(
            '{site_title}'              => 'rodolfro',
        );

        parent::__construct();
    }


	function trigger( $email )
    {
        $this->recipient = $email;
        $this->setup_locale();
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }

    public function get_content_html()
    {
        $body = wc_get_template_html( 'emails/email-header.php', array(
			'sent_to_admin' => false,
			'plain_text'    => false,
            'email_heading'      => $this->get_heading(),
            'additional_content' => $this->get_additional_content(),
		), '', $this->template_base );
		$body .= wc_get_template_html( $this->template_plain, array(
			'sent_to_admin' => false,
			'plain_text'    => false,
            'email_heading'      => $this->get_heading(),
            'additional_content' => $this->get_additional_content(),
		), '', $this->template_base );
		$body .= wc_get_template_html( 'emails/email-footer.php', array(
			'sent_to_admin' => false,
			'plain_text'    => false,
            'email_heading'      => $this->get_heading(),
            'additional_content' => $this->get_additional_content(),
		), '', $this->template_base );
        return $body;
	}

	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => true,
			'email'			=> $this
		), '', $this->template_base );
	}    

}

new Polen_Email_Signin_Prerelease();