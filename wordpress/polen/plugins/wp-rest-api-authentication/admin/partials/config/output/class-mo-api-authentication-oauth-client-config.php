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

class Mo_API_Authentication_Admin_OAuth_Client_Config {

	public static function mo_api_auth_configuration_output() {
		?>
	<div id="mo_api_authentication_support_layout" class="mo_api_authentication_support_layout">
		<div>
	<div id="mo_api_authentication_support_tokenapi" class="mo_api_authentication_support_layout">
		<div>
			<h4>Configure your Application with below data : </h4>
			<table class="mo_api_authentication_settings_table">
				<tr>
					<td>Client ID : </td>&nbsp;
					<td><input readonly style="width:350px; border: 0;" type="textbox" value="<?php echo get_option('mo_api_auth_clientid'); ?>"></td>
				</tr>
				<!-- <tr>
					<td>Client Secret : </td>&nbsp;
					<td><input readonly style="width:350px; border: 0;" type="textbox" value="<?php //echo get_option('mo_api_auth_clientsecret'); ?>"></td>
				</tr> -->
				<tr>
					<td>Token Endpoint : </td>&nbsp;
					<td><input readonly style="width:350px; border: 0; " type="textbox" value="<?php echo get_home_url();?>/wp-json/api/v1/token"></td>
				</tr><td>&nbsp;</td>
			</table>
		</div>
	</div>&nbsp;
		<div class="mo_api_authentication_support_layout">
			<h3>Request / Response Format for OAuth 2.0 : </h3>
			<table class="mo_api_authentication_settings_table">
				<h4> Password Grant : </h4>
				<tr>
					<h4> Step 1 : Configure the App</h4>
					<ol>
						<li>Select OAuth 2.0 App</li>
						<li>Select  Authentication Method : Password Grant</li>
						<li>Select Token Type of your choice or based on your client application. <br>
							<b>Access Token : </b><br>
							<b>JWT Token : </b></li>
						<li>Click on Save Configuration.</li>
					</ol>
					<h4> Step 2 : Get the Token</h4>
					<ol>
						<li>After saving above configuration, you will get the Client ID & Token Endpoint</li>
						<li>To get the token, you need to send a token request as shown below<br>
							<b>POST</b> /wp-json/api/v1/token <br>
							grant_type = < password ><br>
							&username = < wordpress username ><br>
							&password = < wordpress password ><br>
							&client_id = < client id ><br>
						</li>

					</ol>
					<h4> Step 3 : Send API Request</h4>
					<ol>
						<li>Once you get the access_token / id_token, you can use it to request the access to the WordPress site as shown below.<br><br>
						<b>GET</b> /wp-json/api/v1/token <br>
						<b>Authorization Header : </b> Bearer < access_token / id_token ><br><br></li>
					</ol>
					<b>Note : </b>Above token is valid for 1 min. Users have to create a token each time they want to request the API access.
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
		</div>
		<?php
	}
}
	