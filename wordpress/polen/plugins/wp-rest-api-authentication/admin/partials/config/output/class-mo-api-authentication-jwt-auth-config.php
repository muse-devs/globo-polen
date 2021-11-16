<?php 

class Mo_API_Authentication_Admin_Jwt_Auth_Config {

	public static function mo_api_auth_configuration_output() {
		?>
	<div id="mo_api_authentication_support_layout" class="mo_api_authentication_support_layout">
		<div>
		<div class="mo_api_authentication_common_div_css">
			<h3>API Reference for JWT Authentication : <u><b><small><a href="https://plugins.miniorange.com/wordpress-rest-api-jwt-authentication-method" target="_blank" rel="noopener" style="color: red;size: 2px;">[SETUP GUIDE]</a></small></b></u></h3>
			<div class="row">
			<div class="mo_api_authentication_box">
					<div class="mo_api_authentication_box_header">
						<h4 class="mo_api_authentication_box_heading"><span class="mo_api_authentication_request_button">post</span> /wp-json/api/v1/token </h4>				
					</div>
					<div class="mo_api_authentication_box_body">
						<div class="mo_api_authentication_box_body_text">
							<p>
								To request the JWT token, you need to send HTTP request in format below.
							</p>
							<p>Note : This token has default validity of <b>1 hour</b>.</p>						
						</div>
						<div class="mo_api_authentication_box_white_section">
							<h5 class="mo_api_authentication_box_white_section_heading">Parameters</h5>
						</div>
						<br>
						<div class="mo_api_authentication_box_body_text">
							<table class="mo_api_authentication_settings_table">
								<thead>
									<th style="text-align:left">Name</th>
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
										<td>username <span style="color:red">* required</span> <br>
										<b>(string)</b>
										</td>
										<td>Your WordPress username</td>
									</tr>
									<tr>
										<td>
											<hr>
										</td>
										<td>
											<hr>
										</td>
									</tr>
									<tr>
										<td>password <span style="color:red">* required</span> <br>
										<b>(string)</b>
										</td>
										<td>Your WordPress password</td>
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
											{<br>
												"token_type":"Bearer",<br>
												"iat":1573547305,<br>
												"expires_in":1573550905,<br>
												"jwt_token":" HEADER . PAYLOAD . SIGNATURE "<br>
											}
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
									<tr>
										<td style="vertical-align:baseline"><b>400</b></td>
										<td>
											<div class="mo_api_authentication_code_output">Bad Request</div>
											<p>Sample response</p>
											<div class="mo_api_authentication_code_output">
											{<br>
												"status":"error",<br>
												"error":"BAD_REQUEST",<br>
												"code":"400",<br>
												"error_description":"Username and password are required."<br>
											}
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
									<tr>
										<td style="vertical-align:baseline"><b>403</b></td>
										<td>
											<div class="mo_api_authentication_code_output">Forbidden response</div>
											<p>Sample response</p>
											<div class="mo_api_authentication_code_output">
											{<br>
												"status":"error",<br>
												"error":"FORBIDDEN",<br>
												"code":"403",<br>
												"error_description":"Invalid Username or Password."<br>
											}
											</div>
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
											curl -d "username=<b>&lt;wordpress_username&gt;</b>&password=<b>&lt;wordpress_password&gt;</b>" -X POST <?php echo esc_url( get_home_url() );?>/wp-json/api/v1/token
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<br>
					</div>
				</div>
				<br>
				<div class="mo_api_authentication_box">
					<div class="mo_api_authentication_box_header">
						<h4 class="mo_api_authentication_box_heading"><span class="mo_api_authentication_request_button">get</span> /wp-json/wp/v2/posts </h4>				
					</div>
					<div class="mo_api_authentication_box_body">
						<div class="mo_api_authentication_box_body_text">
							<p>
								Once you get the JWT token, you can access WP REST API's like below.
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
										<td>Bearer <b>&lt;jwt_token&gt;</b></td>
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
									<tr>
										<td style="vertical-align:baseline"><b>401</b></td>
										<td>
											<div class="mo_api_authentication_code_output">Invalid Signature response</div>
											<p>Sample response</p>
											<div class="mo_api_authentication_code_output">
											{<br>
												"status":"error",<br>
												"error":"INVALID_SIGNATURE",<br>
												"code":"401",<br>
												"error_description":"JWT Signature is invalid."<br>
											}
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
											curl -H "Authorization:Bearer <b>&lt;jwt_token&gt;</b>" -X GET <?php echo esc_url( get_home_url() );?>/wp-json/wp/v2/posts
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
	<?php }
}