<?php

namespace Polen\Includes;

use Polen\Includes\Emails\Polen_WC_Completed_Order;
use Polen\Includes\Emails\Polen_WC_Customer_New_Account;
use Polen\Includes\Emails\Polen_WC_Processing;

if( ! defined( 'ABSPATH' ) ) {
    die( 'Silence is golden.' );
}

class Polen_Emails {

    public function __construct( bool $static = false ) {
        if( $static ) {
            add_filter( 'woocommerce_email_classes', array( $this, 'register_emails' ), 99, 1 );
        }
    }

    public function register_emails( $emails )
    {

        //Nova conta no checkout
        $emails[ 'WC_Email_Customer_New_Account' ] = new Polen_WC_Customer_New_Account();

        //Limpando as Actions
        // remove_action( 'woocommerce_order_status_completed_notification', array( $emails[ 'WC_Email_Customer_Completed_Order' ], 'trigger' ), 10 );
        $emails[ 'WC_Email_Customer_Completed_Order' ] = new Polen_WC_Completed_Order();

        //Limpando as Actions
        // remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $emails['WC_Email_Customer_Processing_Order'], 'trigger' ), 10 );
        // remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $emails['WC_Email_Customer_Processing_Order'], 'trigger' ), 10 );
        // remove_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', array( $emails['WC_Email_Customer_Processing_Order'], 'trigger' ), 10 );
        $emails['WC_Email_Customer_Processing_Order'] = new Polen_WC_Processing();

		require_once PLUGIN_POLEN_DIR . '/includes/emails/Polen_WC_Payment_Approved.php';
		$emails['Polen_WC_Payment_Approved'] = new Polen_WC_Payment_Approved();

        require_once PLUGIN_POLEN_DIR . '/includes/emails/Polen_WC_Payment_In_Revision.php';
		$emails['Polen_WC_Payment_In_Revision'] = new Polen_WC_Payment_In_Revision();

        require_once PLUGIN_POLEN_DIR . '/includes/emails/Polen_WC_Payment_Rejected.php';
		$emails['Polen_WC_Payment_Rejected'] = new Polen_WC_Payment_Rejected();

        require_once PLUGIN_POLEN_DIR . '/includes/emails/Polen_WC_Talent_Accepted.php';
		$emails['Polen_WC_Talent_Accepted'] = new Polen_WC_Talent_Accepted();

        require_once PLUGIN_POLEN_DIR . '/includes/emails/Polen_WC_Talent_Rejected.php';
		$emails['Polen_WC_Talent_Rejected'] = new Polen_WC_Talent_Rejected();

        require_once PLUGIN_POLEN_DIR . '/includes/emails/Polen_WC_Order_Expired.php';
		$emails['Polen_WC_Order_Expired'] = new Polen_WC_Order_Expired();

		return $emails;
	}

}