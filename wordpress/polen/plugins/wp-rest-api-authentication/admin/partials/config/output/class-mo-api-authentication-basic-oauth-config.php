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

class Mo_API_Authentication_Admin_Basic_Auth_Config {
	
	public static function mo_api_auth_configuration_output() {
		$basic_auth_key = get_option( 'mo_api_authentication_authentication_key' );
		if( $basic_auth_key == 'cid_secret' ) {
		?>
		<div id="mo_api_authentication_support_layout" class="mo_api_authentication_support_layout">
		<div id="mo_api_authentication_support_tokenapi" class="mo_api_authentication_common_div_css">
			<div>
				<h3>Create User Specific Client Credentials - <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank" rel="noopener noreferrer" style="font-size: small">[PREMIUM]</a>: </h3>
				<h4>Select the User from your WordPress database to create as client ID and client Secret.</h4>
				<table class="mo_api_authentication_settings_table">
					<tr>
						<td style="vertical-align:top"><h4 style="font-size:16px;margin-top:15px;">WordPress Username : </h4></td>
						<td>	
							<?php $users = get_users();?>
							<select readonly style="width:100%;margin-top:15px">
							<?php foreach($users as $user){
							?>
								<option><?php echo esc_attr( $user->user_login ); ?></option>
							<?php
							} ?>
							</select>
							<br>
							<button disabled style="margin-top:15px; width:170px;" class="button button-primary button-large">Create API Key</button>
					</td>
					</tr>
				</table>
			</div>
		</div>
		</div>
		<br>
		<?php } ?>
		<div id="mo_api_authentication_support_layout" class="mo_api_authentication_support_layout">
		<div>
		<?php
		if( $basic_auth_key == 'cid_secret' ) {
		?>
		<div id="mo_api_authentication_support_tokenapi" class="mo_api_authentication_common_div_css">
			<div>
				<h3>Client Details : </h3>
				<table class="mo_api_authentication_settings_table">
					<tr>
						<td></td>
						<td>
							<p id="generate_token_success_message" style="color:green;display:none">New Client Credentials are generated</p>
						</td>
					</tr>
					<tr>
						<td>Client ID : </td>&nbsp;
						<td><input readonly id="mo_api_auth_client_id_value" style="width:350px; border: 0;" type="textbox" value="<?php echo esc_attr( get_option('mo_api_auth_clientid') ); ?>"></td>
					</tr>
					<tr>
						<td>Client Secret : </td>&nbsp;
						<td><input readonly id="mo_api_auth_client_secret_value" style="width:350px; border: 0;" type="textbox" value="<?php echo esc_attr( get_option('mo_api_auth_clientsecret') ); ?>"></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<a id="regeneratetoken" name="action" style="cursor:pointer;font-size:14px" ><b>Generate New Client Credentials</b></a> <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank" rel="noopener noreferrer" style="font-size: small">[UPGRADE]</a>
						</td>
					</tr>
				</table>
			</div>
		</div>&nbsp;
		<script type="text/javascript">
		function regenerateNewClientCredentials(){
			var data = {
				'action': 'regenerate_client_credentials',
			};			

			jQuery.post(ajaxurl, data, function(response) {	
				document.getElementById('generate_token_success_message').style.display = "block";
				document.getElementById("mo_api_auth_client_id_value").value = response['client_id'];
				document.getElementById("mo_api_auth_client_secret_value").value = response['client_secret'];
			});
		}
		</script>
		<?php } ?>
		<div id="mo_api_authentication_support_basicoauth" class="mo_api_authentication_common_div_css">
			<h3>API Reference for Basic Auth : <u><b><small><a href="https://plugins.miniorange.com/wordpress-rest-api-basic-authentication-method" target="_blank" rel="noopener" style="color: red">[SETUP GUIDE]</a></small></b></u></h3>
			<table class="mo_api_authentication_settings_table">
				<tr>
					After you save the Basic Authentication Configuration, you can access WP REST API's like below.<br><br>
				</tr>
			</table>
			<?php 
			if( $basic_auth_key == 'uname_pass' )
				$bauth_key = "< username:password >";
			elseif( $basic_auth_key == 'cid_secret' ) 
				$bauth_key = "< client-id:client-secret >";
			?>
			<div class="row">
				<div class="mo_api_authentication_box">
					<div class="mo_api_authentication_box_header">
						<h4 class="mo_api_authentication_box_heading"><span class="mo_api_authentication_request_button">get</span> /wp-json/wp/v2/posts </h4>				
					</div>
					<div class="mo_api_authentication_box_body">
						<div class="mo_api_authentication_box_body_text">
							<p>
								Request all WordPress Posts
							</p>
						</div>
						<div class="mo_api_authentication_box_white_section">
							<h5 class="mo_api_authentication_box_white_section_heading">Header</h5>
						</div>
						<br>
						<div class="mo_api_authentication_box_body_text">
							<table class="mo_api_authentication_settings_table">
								<thead>
									<th style="text-align:left">Name</th>
									<th style="text-align:left">Value</th>
								</thead>
								<tbody>
									<tr>
										<td>
											<hr>
										</td>
										<td>
											<hr>
										</td>
									</tr>
									<tr>
										<td>Authorization</td>
										<td>Basic base64encoded<?php echo $bauth_key; ?>
										<?php if( $basic_auth_key == 'uname_pass' ){?>
										<br>
										<br>
										Example &nbsp;&nbsp;&nbsp; <b><i>username : testuser</i></b> &nbsp;&nbsp; <b><i>password : password@123</i></b> <br>
										<b>base64encoded < testuser : password@123 > : </b> dGVzdHVzZXI6cGFzc3dvcmRAMTIz <br><br>
										<?php } ?>
									</td>
									</tr>
								</tbody>
							</table>
						</div>
						<br>
						<div class="mo_api_authentication_box_white_section">
							<h5 class="mo_api_authentication_box_white_section_heading">Responses</h5>
						</div>
						<br>
						<div class="mo_api_authentication_box_body_text">
							<table class="mo_api_authentication_settings_table">
								<thead>
									<th style="text-align:left">Code</th>
									<th style="text-align:left">Description</th>
								</thead>
								<tbody>
									<tr>
										<td>
											<hr>
										</td>
										<td>
											<hr>
										</td>
									</tr>
									<tr>
										<td style="vertical-align:baseline"><b>200</b></td>
										<td>
											<div class="mo_api_authentication_code_output">Successful response</div>
											<p>Sample response</p>
											<div class="mo_api_authentication_code_output">
											[{<br>
												"id":1,<br>
												"slug":"hello-world",<br>
												"status":"publish",<br>
												"type":"post",<br>
												"link":"http://< wp_base_url >/hello-world/",<br>
												"title":{<br>
													"rendered":"Hello World"<br>
													},<br>
												"content":{<br>
													"rendered":"\nWelcome to WordPress. This is your first post. Edit or delete it, then start writing!\n",<br>
													"protected":false<br>
													},<br>
													...<br>												
											}]
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<hr>
										</td>
										<td>
											<hr>
										</td>
									</tr>
									<?php if( $basic_auth_key == 'uname_pass' ){?>
									<tr>
										<td style="vertical-align:baseline"><b>400</b></td>
										<td>
											<div class="mo_api_authentication_code_output">Invalid username response</div>
											<p>Sample response</p>
											<div class="mo_api_authentication_code_output">
											{<br>
												"status":"error",<br>
												"error":"INVALID_USERNAME",<br>
												"code":"400",<br>
												"error_description":"Username Does not exist."<br>
											}
											</div>
										</td>
									</tr>
									<?php } elseif( $basic_auth_key == 'cid_secret' ){ ?>
									<tr>
										<td style="vertical-align:baseline"><b>400</b></td>
										<td>
											<div class="mo_api_authentication_code_output">Invalid client credentials</div>
											<p>Sample response</p>
											<div class="mo_api_authentication_code_output">
											{<br>
												"status":"error",<br>
												"error":"INVALID_CLIENT_CREDENTIALS",<br>
												"code":"400",<br>
												"error_description":"Invalid client ID or client sercret."<br>
											}
											</div>
										</td>
									</tr>
									<?php } ?>
									<tr>
										<td>
											<hr>
										</td>
										<td>
											<hr>
										</td>
									</tr>
									<tr>
										<td style="vertical-align:baseline"><b>401</b></td>
										<td>
											<div class="mo_api_authentication_code_output">Missing Authorization Header</div>
											<p>Sample response</p>
											<div class="mo_api_authentication_code_output">
											{<br>
												"status":"error",<br>
												"error":"MISSING_AUTHORIZATION_HEADER",<br>
												"code":"401",<br>
												"error_description":"Authorization header not received. Either authorization header was not sent or it was removed by your server due to security reasons. Check more details for the error on"<br>
											}
											</div>
											<p><b>NOTE:</b> This error may occur because of server environment,If Apache server then put the below line in your <b>htaccess</b> file after the RewriteBase.</p>
											<code class="mo_api_authentication_code_output">
												RewriteCond %{HTTP:Authorization} ^(.*)<br>
												RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
											</code>
											<p>If NGINX server then put the below line in your <b>conf</b> file.</p>
											<code class="mo_api_authentication_code_output">
												add_header Access-Control-Allow-Headers "Authorization";
											</code>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<br>
						<div class="mo_api_authentication_box_white_section">
							<h5 class="mo_api_authentication_box_white_section_heading">Example</h5>
						</div>
						<br>
						<div class="mo_api_authentication_box_body_text">
							<table class="mo_api_authentication_settings_table">
								<thead>
									<th style="text-align:left">Request</th>
									<th style="text-align:left">Format</th>
								</thead>
								<tbody>
									<tr>
										<td>
											<hr>
										</td>
										<td>
											<hr>
										</td>
									</tr>
									<tr>
										<td><b>Curl</b></td>
										<td>
											<?php if( $basic_auth_key == 'uname_pass' ){?>
												curl -H "Authorization:Basic <b>base64encoded&lt;username:password&gt;</b>" -X GET <?php echo esc_url( get_home_url() );?>/wp-json/wp/v2/posts
											<?php } elseif( $basic_auth_key == 'cid_secret' ){
												$client_id = get_option('mo_api_auth_clientid') ? get_option('mo_api_auth_clientid') : '';
												$client_secret = get_option('mo_api_auth_clientsecret') ? get_option('mo_api_auth_clientsecret') : '';
												$str = $client_id.':'.$client_secret;
												?>
												curl -H "Authorization:Basic <?php echo esc_html( base64_encode($str) ); ?>" -X GET <?php echo esc_url( get_home_url() );?>/wp-json/wp/v2/posts
											<?php 
											} ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<br>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}