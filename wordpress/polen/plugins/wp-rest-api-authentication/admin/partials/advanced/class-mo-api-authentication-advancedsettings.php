<?php
	
	class Mo_API_Authentication_Admin_AdvancedSettings {
	
		public static function mo_api_authentication_advancedsettings() {
			self::role_based_restriction();
		}

		public static function role_based_restriction(){
			$democss = "width: 350px; height:35px;";
		?>
		<div class="mo_table_layout">
			    <h3> Custom Header <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank" rel="noopener noreferrer" style="font-size: small;color: red">[UPGRADE]</a>: </h3>
			    <p>If you want to authenticate the WordPress REST APIs in a more secure way, you can set a custom <b>Header</b>.</p>	
			    	<table cellpadding="4" cellspacing="4">
                        <tr>
						  	<td><h4>Custom Header</h4></td>
							<td><input type="text" disabled placeholder="Custom Header" value="Authorization"></td>
						</tr>
						<tr>
							<td>
								<button class="button-primary" disabled>Save Settings</button>
							</td>
						</tr>
			    	</table>
			</div>
			<div class="mo_table_layout">
			    <h3> Role based Restriction <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank" rel="noopener noreferrer" style="font-size: small;;color: red">[UPGRADE]</a>: </h3>
			    <p>User having below roles can access Admin Dashboard on <b><?php echo esc_url( site_url() );?></b> site.</p> 	
			    	<table cellpadding="4" cellspacing="4">
                        <tr>
						  	<td><input type="checkbox" disabled checked></td>
							<td>Administrator</td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled checked></td>
							<td>Editor</td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled checked></td>
							<td>Author</td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled checked></td>
							<td>Contributor</td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled checked></td>
							<td>Subscriber</td>
						</tr> 	
			    	</table>
			    	<p>No user is not allowed to access any REST APIs on this WordPress site without Authentication.</p>
			</div>
			<div class="mo_table_layout">
			    <h3> Token Expiry Configuration <span style="font-size: small"> [Eligible for OAuth 2.0 and JWT Authentication]</span> <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank" rel="noopener noreferrer" style="font-size: small;color: red">[UPGRADE]</a>: </h3>
			    <p>JWT Token and the OAuth Access Token will be expired on the given time</p> 	
			    	<table cellpadding="4" cellspacing="4">
                        <tr>
						  	<td>Access Token Expiry Time (In minutes) : </td>
							<td><input type="text" disabled placeholder="Custom Token Expiry" value="3600" ></td>
						</tr>
						<tr>
							<td>Refresh Token Expiry Time : 
								<select id="mo_api_refresh_token_expiry_option" name="mo_api_refresh_token_expiry_option" onchange="showTokenExp()">
									<option selected="" value="days">Days</option>
									<option value="hours">Hours</option>
								</select>
							</td>  
							<td><input type="text" disabled placeholder="Custom Token Expiry" value="14" ></td>
						</tr>
						<tr>
							<td>
								<button class="button-primary" disabled>Save Settings</button>
							</td>
						</tr>
			    	</table>
			</div>
			<div class="mo_table_layout">
				<h3> Exclude REST APIs <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank" rel="noopener noreferrer" style="font-size: small;color: red">[UPGRADE]</a>: </h3>
				<p>Given APIs will be publicly accessible from the all users.</p>
			    	<table cellpadding="4" cellspacing="4">
                        <tr>
							<input type="text" disabled placeholder="Enter REST API Pattern" style="width:50%">
							<button class="button button-default" disabled style="margin-left:20px">Add more</button>
						</tr>
						<br>
						<br>
						<tr>
							<td>
								<button class="button-primary" disabled>Save Settings</button>
							</td>
						</tr>
			    	</table>
			</div>
		<?php
		}
	}