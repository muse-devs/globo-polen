<?php
	
	class Mo_API_Authentication_Admin_CustomAPIIntegration {
	
		public static function mo_api_authentication_customintegration() {
			self::custom_api_integration();
		}

		public static function custom_api_integration(){
			$democss = "width: 350px; height:35px;";
		?>
			<div class="mo_table_layout">
			    <h3> Custom/Third Party Plugin API Authentication <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank" rel="noopener noreferrer" style="font-size: small;color: red">[UPGRADE]</a> : </h3>
			    <p></p> 	
			    	<table cellpadding="4" cellspacing="4">
						<tr>
							<p><b>If you are using any custom API provider and looking for protecting the REST APIs we can customize the plugin according to that. </b></p>
						</tr>
                        <tr>
						  	<td><input type="checkbox" disabled></td>
							<td> <img src="<?php echo plugin_dir_url( __FILE__ ).'../../images/woocommerce-circle.png'; ?>" width="50px"> </td>
							<td> WooCommerce </td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled></td>
							<td> <img src="<?php echo plugin_dir_url( __FILE__ ).'../../images/buddypress.png'; ?>" width="50px"> </td>
							<td> BuddyPress </td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled></td>
							<td> <img src="<?php echo plugin_dir_url( __FILE__ ).'../../images/gravityform.jpg'; ?>" width="50px"> </td>
							<td> Gravity Form </td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled></td>
							<td> <img src="<?php echo plugin_dir_url( __FILE__ ).'../../images/learndash.png'; ?>" width="50px"> </td>
							<td> Learndash API Endpoints </td>
						</tr>
						<tr>
						  	<td><input type="checkbox" disabled></td>
							<td> <img src="<?php echo plugin_dir_url( __FILE__ ).'../../images/logo.png'; ?>" width="50px"> </td>
							<td> Custom Built REST Endpoints in WordPress</td>
						</tr>
			    	</table>
			</div>
		<?php
		}
	}