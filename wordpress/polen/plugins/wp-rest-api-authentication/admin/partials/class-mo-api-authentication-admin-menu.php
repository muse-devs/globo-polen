<?php

require( 'support/class-mo-api-authentication-support.php' );
require( 'support/class-mo-api-authentication-faq.php' );
require( 'config/class-mo-api-authentication-config.php' );
require( 'license/class-mo-api-authentication-license.php' );
require( 'account/class-mo-api-authentication-account.php' );
require( 'demo/class-mo-api-authentication-demo.php' );
require( 'postman/class-mo-api-authentication-postman.php' );
require( 'advanced/class-mo-api-authentication-advancedsettings.php' );
require ( 'advanced/class-mo-api-authentication-protectedrestapis.php' );
require( 'custom-api-integration/class-mo-api-authentication-custom-api-integration.php' );

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       miniorange
 * @since      1.0.0
 *
 * @package    Miniorange_api_authentication
 * @subpackage Miniorange_api_authentication/admin/partials
 */

function mo_api_show_popup(){
	?>
	<!-- <div class="mo_api_main" id="mo_api_main" >
	<div class="mo_api_pop_up" id="mo_api_pop_up" >
	<div class="mo_api_close" id="mo_api_close" >&times;</div>
	
	<div class="mo_api_banner">

				<div class="mo_api_banner_heading">Get Premium</div>
				<br>
				<div class="mo_api_banner_subheading">Unlock All-Features starting at just <span style="color:#1B2A4A">$149</span><span style="color: black">*</span></div>
				<br>
				<div>
				<a href="admin.php?page=mo_api_authentication_settings&tab=licensing"_blank"><button type="button" style="background-color:#F56E38;border: 2px solid;margin:15px; width:140px;height: 40px;" class="button button-primary button-large" ><strong>Upgrade Now ></strong></button></a>
			</div>
				<h2><a href="admin.php?page=mo_api_authentication_settings&tab=requestfordemo" style="color: black">Click Here</a><?php echo ' to request Demo and On-Premise trial for premium Features'; ?></h2>
				</h2>
				
	</div>
</div>
</div> -->
<div class="mo-popup-overlay"></div>
<div class="mo-popup-card-div">
	<div class="mo-card-pop">
		<button onclick="window.location.href='admin.php?page=mo_api_authentication_settings&mra=true';" class="mo-popup-card-close">
			<span class="mdi mdi-close"></span>
		</button>
		<span class="mo-popup-card-icon mdi mdi-gift"></span>
		<h2>Pro Features</h2>
		<ul>
			<li class="mdi mdi-check">Secure APIs using OAuth 2.0 method</li>
			<li class="mdi mdi-check">WordPress Role based APIs access</li>
			<li class="mdi mdi-check">Time base Tokens</li>
			<li class="mdi mdi-check">Signature Validation for JWT Token</li>
			<li class="mdi mdi-check">Protect custom and third-party plugin API endpoints</li>
			<li class="mdi mdi-check">Time based Tokens/ custom token expiry</li>
			<li class="mdi mdi-check">Secure API using external provider's token</li>
		</ul>
		<h3>Unlock All-Features starting at just <span style="color:#1B2A4A">$149</span><span style="color: black">*</span></h3>
		<button onclick="window.location.href='admin.php?page=mo_api_authentication_settings&tab=licensing';" class="mo-pop-up-card-download">Upgrade Now</button>
		<p><a href="admin.php?page=mo_api_authentication_settings&tab=requestfordemo" style="color: black">Click Here</a> to request Demo and On-Premise trial for premium Features</p>
	</div>
</div>
<?php 
}

function mo_api_authentication_main_menu() {

	$currenttab = "";
	if( isset( $_GET['tab'] ) )
		$currenttab = sanitize_text_field( $_GET['tab'] );

	if(!get_option('mo_save_settings'))
	{
		update_option('mo_save_settings',0);
	}
	?>

	<div>
	<?php

	$mo_rest_api_today = date("Y-m-d H:i:s");
	$mo_rest_api_date = "2021-11-26 23:59:59";

	if ( $mo_rest_api_today <= $mo_rest_api_date )
			Mo_API_Authentication_Admin_Menu::show_bfs_note();

	if( get_option('mo_rest_api_show_popup') && 'licensing' !== $currenttab ) {
		mo_api_show_popup();
	}

	if(get_option('mo_save_settings')==1){
		update_option('mo_save_settings', 2);
	}
	?>
	<div>
	<?php 
	Mo_API_Authentication_Admin_Menu::mo_api_auth_show_menu( $currenttab );
	echo '
	<div id="mo_api_authentication_settings">';
		echo '
		<div class="miniorange_container">';
		echo '
		<table style="width:100%;">
			<tr>
				<td style="vertical-align:top;width:65%;" class="mo_api_authentication_content">';
					Mo_API_Authentication_Admin_Menu::mo_api_auth_show_tab( $currenttab );
				// echo '</td><td style="vertical-align:top;padding-left:1%;" class="mo_api_authentication_sidebar">';
					Mo_API_Authentication_Admin_Menu::mo_api_auth_show_support_sidebar( $currenttab );
				echo '</tr>
		</table>
		<div class="mo_api_authentication_tutorial_overlay" id="mo_api_authentication_tutorial_overlay" hidden></div>
		</div>'; ?>
	</div>

<script type="text/javascript">
	
	jQuery(document).ready(function(){

function mo_api_showWindow() {
	jQuery('#mo_api_main').show();
}

mo_api_showWindow();

function mo_api_hideWindow(){
	jQuery('#mo_api_main').hide();
	
}
 
jQuery('#mo_api_close').click(function(){
	mo_api_hideWindow();
})

})
</script>

<?php
}

class Mo_API_Authentication_Admin_Menu {
	
	public static function show_bfs_note()
	{
        ?>
            <form name="f" method="post" action="" id="mo_rest_api_bfs_note_form">
            	<?php wp_nonce_field('mo_rest_api_bfs_note_form','mo_rest_api_bfs_note_form_field'); ?>
				<input type="hidden" name="option" value="mo_rest_api_bfs_note_message"/>
                <div class="notice notice-info"style="padding-right: 38px;position: relative;border-color:red; background-color: #0c082f;
					transform: scaleX(1);
					background-image: url('<?php echo esc_attr(dirname(plugin_dir_url( __FILE__ )));?>/images/3px-tile.png');"><h4><center><i class="fa fa-gift" style="font-size:50px;color:red;"></i>&nbsp;&nbsp;
				<big><font style="color:white; font-size:30px;"><b>BLACK FRIDAY & CYBER MONDAY SALE: </b><b style="color:yellow;">UPTO 50% OFF!</b></font> <br><br></big><font style="color:white; font-size:20px;">Contact us at oauthsupport@xecurify.com for more details.</font></center></h4>
				<p style="text-align: center; font-size: 60px; margin-top: 0px; color:white;" id="demo"></p>
				</div>
			</form>
		<script>
			var countDownDate = <?php echo esc_attr(strtotime('Nov 26, 2021 23:59:59')) ?> * 1000;
			var now = <?php echo esc_attr(time()) ?> * 1000;
			var x = setInterval(function() {
				now = now + 1000;
				var distance = countDownDate - now;
				var days = Math.floor(distance / (1000 * 60 * 60 * 24));
				var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);
				document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
					minutes + "m " + seconds + "s ";
				if (distance < 0) {
					clearInterval(x);
					document.getElementById("demo").innerHTML = "EXPIRED";
				}
			}, 1000);
		</script>
		<?php
	}


	public static function mo_api_auth_show_menu( $currenttab ) { 
		 ?>

		<div class="wrap">
			<div>
				<img style="float:left;" src="<?php echo dirname( plugin_dir_url( __FILE__ ) );?>/images/logo.png">
			</div>
		</div>
		<div class="wrap">
	       	<h1>
	            miniOrange API Authentication&nbsp
				<a class="add-new-h2" href="admin.php?page=mo_api_authentication_settings&tab=licensing"  style="background: #ffac11;color: #212121;border-color:white">Premium Plans</a>
	           	<a class="add-new-h2" href="https://forum.miniorange.com/" target="_blank" rel="noopener">Ask questions on our forum</a>
				<a class="add-new-h2" href="https://faq.miniorange.com/" target="_blank" rel="noopener">FAQ</a>
				<a class="add-new-h2" href="https://plugins.miniorange.com/wordpress-rest-api-authentication" target="_blank" rel="noopener" style="background-color: #A61407;color:white;border-color:white">Learn more</a>
				<a class="add-new-h2" href="admin.php?page=mo_api_authentication_settings&tab=postman" style="background-color: #ff6c37;color:white;border-color:white">Postman Samples</a>
	       	</h1>
       	</div>
        <style>
            .add-new-hover:hover{
                color: white !important;
            }
        </style>

	

		<div id="tab">
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab <?php if( $currenttab == '' || $currenttab == 'config' ) echo 'nav-tab-active';?>" href="admin.php?page=mo_api_authentication_settings&tab=config">Configure API Authentication</a>
                <a class="nav-tab <?php if( $currenttab == 'protectedrestapis' ) echo 'nav-tab-active';?>" href="admin.php?page=mo_api_authentication_settings&tab=protectedrestapis">Protected REST APIs</a>
                <a class="nav-tab <?php if( $currenttab == 'advancedsettings' ) echo 'nav-tab-active';?>" href="admin.php?page=mo_api_authentication_settings&tab=advancedsettings">Advanced Settings</a>
				<a class="nav-tab <?php if( $currenttab == 'custom-integration' ) echo 'nav-tab-active';?>" href="admin.php?page=mo_api_authentication_settings&tab=custom-integration">Custom API Authentication</a>
				<a class="nav-tab <?php if($currenttab == 'requestfordemo') echo 'nav-tab-active';?>" href="admin.php?page=mo_api_authentication_settings&tab=requestfordemo">Request For Demo/Trial</a>
				<a class="nav-tab <?php if($currenttab == 'account') echo 'nav-tab-active';?>" href="admin.php?page=mo_api_authentication_settings&tab=account">Account Setup</a>
			</h2>
		</div> 
	</div>
</div>
	<?php } 
	
	public static function mo_api_auth_show_tab( $currenttab ) { 
		if($currenttab == 'account') {
			if (get_option ( 'mo_api_authentication_verify_customer' ) == 'true') {
				Mo_API_Authentication_Admin_Account::verify_password();
			} else if (trim ( get_option ( 'mo_api_authentication_email' ) ) != '' && trim ( get_option ( 'mo_api_authentication_admin_api_key' ) ) == '' && get_option ( 'mo_api_authentication_new_registration' ) != 'true') {
				Mo_API_Authentication_Admin_Account::verify_password();
			}
			else {
				Mo_API_Authentication_Admin_Account::register();
			}
		} elseif( $currenttab == '' || $currenttab == 'config') 
    		Mo_API_Authentication_Admin_Config::mo_api_authentication_config();
		elseif( $currenttab == 'protectedrestapis')
            Mo_API_Authentication_Admin_ProtectedRestAPIs::mo_api_authentication_protectedrestapis();
    	elseif( $currenttab == 'advancedsettings') 
			Mo_API_Authentication_Admin_AdvancedSettings::mo_api_authentication_advancedsettings();
		elseif( $currenttab == 'custom-integration' )
			Mo_API_Authentication_Admin_CustomAPIIntegration::mo_api_authentication_customintegration();			
    	elseif( $currenttab == 'requestfordemo') 
    		Mo_API_Authentication_Admin_RFD::mo_api_authentication_requestfordemo();
    	elseif( $currenttab == 'faq') 
    		Mo_API_Authentication_Admin_FAQ::mo_api_authentication_faq();
    	elseif( $currenttab == 'licensing')
			Mo_API_Authentication_Admin_License::mo_api_authentication_licensing_page();
		elseif( $currenttab == 'postman')
			Mo_API_Authentication_Postman::mo_api_authentication_postman_page();
		
	} 
	public static function mo_api_auth_show_support_sidebar( $currenttab ) { 
		if( $currenttab != 'licensing' ) { 
			echo '<td style="vertical-align:top;padding-left:1%;" class="mo_api_authentication_sidebar">';
			echo Mo_API_Authentication_Admin_Support::mo_api_authentication_support();
			echo '<br>';
			echo Mo_API_Authentication_Admin_Support::mo_api_authentication_advertise();
			echo '</td>';
		}
	}
		
}