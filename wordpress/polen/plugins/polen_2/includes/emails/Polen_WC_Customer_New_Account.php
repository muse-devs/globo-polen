<?php
namespace Polen\Includes\Emails;

use Polen\Includes\Polen_Checkout_Create_User;
use WC_Email_Customer_New_Account;
use WP_User;

class Polen_WC_Customer_New_Account extends WC_Email_Customer_New_Account
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->id                      = 'customer_new_account';
        $this->customer_email          = true;
        $this->title_checkout          = 'Conta Criada';
        $this->description_checkout    = __( 'Customer "new account" emails are sent to the customer when a customer signs up via checkout or account pages.', 'woocommerce' );
        $this->template_html           = 'emails/customer-new-account.php';
        $this->template_html_checkout  = 'emails/customer-new-account-checkout.php';
        $this->template_plain          = 'emails/plain/customer-new-account.php';
    }

    /**
     * Get email subject.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_subject_checkout() {
        return 'Conta Criada';
    }


    /**
     * Get email heading.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_heading_checkout() {
        return 'Sua conta foi criada na Polen.me!';
    }

    /**
     * Trigger.
     *
     * @param int    $user_id User ID.
     * @param string $user_pass User password.
     * @param bool   $password_generated Whether the password was generated automatically or not.
     */
    public function trigger( $user_id, $user_pass = '', $password_generated = false ) {
        $this->setup_locale();

        if( strpos(php_sapi_name(), 'cli' ) !== false ) {
            return '';
        }

        if ( $user_id ) {
            $this->object = new WP_User( $user_id );

            $this->user_pass          = $user_pass;
            $this->user_login         = stripslashes( $this->object->user_login );
            $this->user_email         = stripslashes( $this->object->user_email );
            $this->recipient          = $this->user_email;
            $this->password_generated = $password_generated;
        }

        if ( $this->is_enabled() && $this->get_recipient() ) {
            $checkout_create_user = new Polen_Checkout_Create_User();
            if( $checkout_create_user->send_password_in_email_new_user() ) {

                $user_new_password = wp_generate_password( 5, false ) . random_int( 0, 99 );
                $this->password_generated = $user_new_password;
                wp_set_password( $user_new_password, $user_id );
                $this->send( $this->get_recipient(),
                             $this->get_default_subject_checkout(),
                             $this->get_content_html_checkout(),
                             $this->get_headers(),
                             $this->get_attachments() );
                             
            } else {
                $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
            }
        }

        $this->restore_locale();
    }



    /**
     * Get content html.
     *
     * @return string
     */
    public function get_content_html_checkout() {
        return wc_get_template_html(
            $this->template_html_checkout,
            array(
                'email_heading'      => $this->get_default_heading_checkout(),
                'additional_content' => $this->get_additional_content(),
                'user_login'         => $this->user_login,
                'user_pass'          => $this->user_pass,
                'blogname'           => $this->get_blogname(),
                'password_generated' => $this->password_generated,
                'sent_to_admin'      => false,
                'plain_text'         => false,
                'email'              => $this,
            )
        );
    }
}