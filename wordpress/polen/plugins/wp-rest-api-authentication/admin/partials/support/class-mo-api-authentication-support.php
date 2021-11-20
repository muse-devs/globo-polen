<?php

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

class Mo_API_Authentication_Admin_Support {
	
	public static function mo_api_authentication_support(){
	?>
		<div id="mo_api_authentication_support_layout" class="mo_api_authentication_support_layout">
			<div>
				<h3>Contact Us</h3>
				<p>Need any help? Want to learn more or couldn't find what you are looking for ?<br>Just send us a query so we can help you.</p>
				<form method="post" action="">
					<input type="hidden" name="option" value="mo_api_authentication_contact_us_query_option" />
			        <?php wp_nonce_field('mo_api_authentication_contact_us_query_form','mo_api_authentication_contact_us_query_form_fields'); ?>
					<table class="mo_api_authentication_settings_table">
						<tr>
							<td><input type="email" class="mo_table_textbox" style="width:90%" required name="mo_api_authentication_contact_us_email" placeholder="Enter email here"
							value="<?php echo esc_attr( get_option('mo_api_authentication_admin_email') );?>"></td>
						</tr>
						<tr>
							<td><input type="tel" id="contact_us_phone" style="width:90%" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" placeholder="Enter phone here" class="mo_table_textbox" name="mo_api_authentication_contact_us_phone" value="<?php echo esc_attr( get_option('mo_api_authentication_admin_phone') );?>"></td>
						</tr>
						<tr>
							<td><textarea class="mo_table_textbox" style="width:90%" placeholder="Enter your query here" required name="mo_api_authentication_contact_us_query" rows="4" style="resize: vertical;"></textarea></td>
						</tr>
						<tr>
							<td><small style="color:#666"><input type="checkbox" name="mo_api_authentication_send_plugin_config" id="mo_api_authentication_send_plugin_config" checked>&nbsp;Include Plugin Configuration</small></td>
						</tr>
					</table>
					<div style="text-align:center;">
						<input type="submit" name="submit" style="margin-left: 5px; width:100px;" class="button button-primary button-large" />
					</div>
					<p>If you want custom features in the plugin, just drop an email at <a href="mailto:info@xecurify.com">info@xecurify.com</a>.</p>
				</form>
			</div>
		</div>
		<script>
			jQuery("#contact_us_phone").intlTelInput();
			// function mo_api_authentication_valid_query(f) {
			// 	!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
			// 			/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
			// }
		</script>
	<?php
	}

	public static function mo_api_authentication_advertise(){
		?>
		<div id="mo_api_authentication_support_layout" class="mo_api_authentication_support_layout">
			<div>
				<h1 style="text-align: center">
					External APIs Integration
				</h1>
				<div class="mo_api_advertise"> 
				<img src="<?php echo esc_url(plugin_dir_url(dirname(__DIR__)).'images/api_integrate.png');?>" height=100px; width=100px; >
				</div>
				<div class="mo_api_advertise" style="font-size: 12px;">
				<h3 style="color: #8a8978">
					We provide the solution for integrating External/Custom provider's API in Wordpress, it will provide the functionality to fetch/update the data in WordPress database or third-party platforms through the External API call based on some event like any third-party plugin's form submission, Payment gateway's payment status or any WordPress event. These solutions will be compatile with third-party plugins like Woocommerce.For more information please contact us on <u><a>oauthsupport@xecurify.com</a></u>.
				</h3>
				</div>
			</div>
			<br>
		</div>
			<?php
	}

}