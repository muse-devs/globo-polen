<?php

namespace Polen\Includes;

use Polen\Includes\Emails\Polen_WC_Processing;

if( ! defined( 'ABSPATH' ) ) {
    die( 'Silence is golden.' );
}

class Polen_Emails {

    public function __construct() {
        add_filter( 'woocommerce_email_classes', array( $this, 'register_emails' ), 99, 1 );
    }

    public function register_emails( $emails ) {

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