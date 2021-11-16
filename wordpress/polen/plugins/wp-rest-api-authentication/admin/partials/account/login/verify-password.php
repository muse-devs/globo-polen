<?php 

function mo_api_authentication_verify_password_ui() {
		?>
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_api_authentication_verify_customer" />
			<?php wp_nonce_field('mo_api_authentication_verify_customer_form','mo_api_authentication_verify_customer_form_fields'); ?>
			<div class="mo_table_layout">
				<div id="toggle1" class="mo_panel_toggle">
					<h3>Login with miniOrange</h3>
				</div>
				<p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password.<br/> <a href="#mo_api_authentication_forgot_password_link">Click here if you forgot your password?</a></b></p>

				<div id="panel1">
					</p>
					<table class="mo_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_table_textbox" type="email" name="email"
								required placeholder="person@example.com"
								value="<?php echo esc_attr( get_option('mo_api_authentication_admin_email') );?>" /></td>
						</tr>
						<td><b><font color="#FF0000">*</font>Password:</b></td>
						<td><input class="mo_table_textbox" required type="password"
							name="password" placeholder="Choose your password" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit" value="Login"
								class="button button-primary button-large" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</form>

								<input type="button" name="back-button" id="mo_api_authentication_back_button" onclick="document.getElementById('mo_api_authentication_change_email_form').submit();" value="Back" class="button button-primary button-large" />

								<form id="mo_api_authentication_change_email_form" method="post" action="">
									<?php wp_nonce_field('mo_api_authentication_change_email_address_form','mo_api_authentication_change_email_address_form_fields'); ?>
									<input type="hidden" name="option" value="mo_api_authentication_change_email_address" />
								</form></td>
							</td>
						</tr>
					</table>
				</div>
			</div>

		<!-- <form name="f" method="post" action="" id="mo_api_authentication_forgotpassword_form">
			<input type="hidden" name="option" value="mo_api_authentication_forgot_password_form_option"/>
		</form> -->
		<script>
			jQuery("a[href=\"#mo_api_authentication_forgot_password_link\"]").click(function(){
				window.open('https://login.xecurify.com/moas/idp/resetpassword');
				//jQuery("#mo_api_authentication_forgotpassword_form").submit();
			});
		</script>
		<?php
	}