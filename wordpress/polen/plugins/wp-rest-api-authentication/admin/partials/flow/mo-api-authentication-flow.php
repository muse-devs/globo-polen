<?php

function mo_api_authentication_config_app_settings()
{

	if ($_SERVER['REQUEST_METHOD'] === 'POST' &&  current_user_can('administrator') ) {
		if( ( isset( $_POST['option'] ) and sanitize_text_field( $_POST['option'] ) == 'mo_api_authentication_config_form' ) && isset($_REQUEST['mo_api_authentication_authentication_method_config_fields']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['mo_api_authentication_authentication_method_config_fields'])), 'mo_api_authentication_authentication_method_config') ) {

			if (isset($_POST['action'])){
				if( sanitize_text_field( $_POST['action'] ) == 'Save Configuration' ) {
					if(get_option('mo_save_settings')==0)
					{
						update_option('mo_save_settings', 1);
					}
					if( !empty( $_POST['mo_api_authentication_selected_authentication_method'] ) ) {
						$selected_auth_method = "";
						$selected_auth_method = sanitize_text_field($_POST['mo_api_authentication_selected_authentication_method']);
						update_option( 'mo_api_authentication_selected_authentication_method', $selected_auth_method );
						update_option( 'mo_api_authentication_config_settings_'.$selected_auth_method, true );
						
						if( $selected_auth_method == 'tokenapi' ) {
							if ( get_option( 'mo_api_auth_bearer_token' ) === false ) {
								$bearer_token = stripslashes( wp_generate_password( 32, false, false ) );
								update_option( 'mo_api_auth_bearer_token', $bearer_token );
							}
							update_option( 'mo_api_auth_message', 'API Key authentication is enabled' );
							update_option( 'mo_api_display_popup', get_option('mo_api_display_popup') + 1 );
						} 
						elseif( ( $selected_auth_method == 'basic_auth' ) && (empty( $_POST['mo_api_authentication_authentication_key'] ) ) )
							{
								update_option( 'mo_api_auth_message', 'Select Basic Authentication key type' );
								mo_api_auth_show_error_message();
								return;
							}
						elseif( ( $selected_auth_method == 'basic_auth' ) && (!empty( $_POST['mo_api_authentication_authentication_key'] ) ) ) {
							if( sanitize_text_field( $_POST['mo_api_authentication_authentication_key'] ) == 'cid_secret' ){
								if (get_option( 'mo_api_auth_clientid' ) === false ){
									mo_api_authentication_create_client();
								}
							}
							update_option( 'mo_api_authentication_authentication_key', sanitize_text_field( $_POST['mo_api_authentication_authentication_key'] ) );
							update_option( 'mo_api_auth_message', 'Basic authentication is enabled' );
							update_option( 'mo_api_display_popup', get_option('mo_api_display_popup') + 1 );
						} elseif( $selected_auth_method == 'jwt_auth' ) {
							update_option( 'mo_api_authentication_jwt_client_secret', stripslashes( wp_generate_password( 32, false, false ) ) );
							update_option( 'mo_api_authentication_jwt_signing_algorithm', 'HS256' );
							update_option( 'mo_api_auth_message', 'JWT authentication is enabled' );
							update_option( 'mo_api_display_popup', get_option('mo_api_display_popup') + 1 );
						} else {
							update_option( 'mo_api_auth_message', $selected_auth_method. ' Authentication method is not supported with your version' );
							mo_api_auth_show_error_message();
							return;
						}
						mo_api_auth_show_success_message();
					} else {
						update_option( 'mo_api_auth_message', 'Please select valid Authentication Method' );
						mo_api_auth_show_error_message();
						return;
					} 
				}	elseif( sanitize_text_field( $_POST['action'] ) == 'Reset' ) {
					mo_api_authentication_reset_settings();
					mo_api_auth_show_success_message();
					update_option( 'mo_api_display_popup', get_option('mo_api_display_popup') + 1 );
				}
			} else {
				update_option( 'mo_api_auth_message', 'Something went wrong!! Please try again.' );
				mo_api_auth_show_error_message();
				return;
			}
		} else if ( ( isset( $_POST['option'] ) && sanitize_text_field( $_POST['option'] ) == "mo_api_authentication_protected_apis_form" ) && isset($_REQUEST['ProtectedRestAPI_admin_nonce_fields']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['ProtectedRestAPI_admin_nonce_fields'])), 'ProtectedRestAPI_admin_nonce') ) {
			
			// Catch the routes that should be whitelisted
			$rest_routes = (isset($_POST['rest_routes'])) ?
				array_map('esc_html', wp_unslash($_POST['rest_routes'])) :
				null;

			// If resetting or whitelist is empty, clear the option and exit the function
			if (empty( $rest_routes ) || isset($_POST['reset'])) {
				mo_api_authentication_reset_api_protection();
				add_settings_error('ProtectedRestAPI_notices', 'settings_updated', 'All APIs below are protected.', 'updated');
				return;
			}

			// Save whitelist to the Options table
			update_option('mo_api_authentication_protectedrestapi_route_whitelist', $rest_routes);
			add_settings_error('ProtectedRestAPI_notices', 'settings_updated', 'Whitelist settings saved.', 'updated');

		}  else if ( ( isset( $_POST['option'] ) && sanitize_text_field( $_POST['option'] ) == "mo_api_authentication_postman_file" ) && isset($_REQUEST['mo_api_authentication_postman_fields']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['mo_api_authentication_postman_fields'])), 'mo_api_authentication_postman_config') ) {
			$method = isset( $_POST[ 'file_name' ] ) ? sanitize_text_field( $_POST[ 'file_name' ] ) : '';
			if( $method !== '' ) {
				mo_api_authentication_postman_download( $method );
			} else {
				update_option( 'mo_api_auth_message', 'Something went wrong!! Please try again.' );
				mo_api_auth_show_error_message();
				return;
			}
		}
	}
}

function mo_api_authentication_reset_api_protection() {
    $wp_rest_server     = rest_get_server();
    $all_routes = array_keys( $wp_rest_server->get_routes() );
    $all_routes = array_map('esc_html',$all_routes);
    update_option( 'mo_api_authentication_protectedrestapi_route_whitelist', $all_routes);
}

function mo_api_authentication_create_client() {
	$client_id = stripslashes( wp_generate_password( 12, false, false ) );
	update_option( 'mo_api_auth_clientid ', $client_id );
	$client_secret = stripslashes( wp_generate_password( 24, false, false ) );
	update_option( 'mo_api_auth_clientsecret', $client_secret );
}

function mo_api_authentication_reset_settings() {
	delete_option( 'mo_api_authentication_selected_authentication_method' );
	delete_option( 'mo_api_authentication_config_settings_tokenapi' );
	delete_option( 'mo_api_authentication_config_settings_basic_auth' );
	delete_option( 'mo_api_authentication_config_settings_jwt_auth' );
	delete_option( 'mo_api_auth_bearer_token ');
	delete_option( 'mo_api_auth_clientid ' );
	delete_option( 'mo_api_auth_clientsecret' );
	delete_option( 'mo_api_authentication_authentication_key' );	
	delete_option( 'mo_api_authentication_jwt_client_secret' );
	delete_option( 'mo_api_authentication_jwt_signing_algorithm' );
	update_option( 'mo_api_auth_message', 'Configuration reset successfully' );
}

function mo_api_authentication_export_plugin_config() {
	$config = null;
	$config['Authentication_Method'] = get_option( 'mo_api_authentication_selected_authentication_method' );
	if( $config['Authentication_Method'] == "tokenapi" )
		$config['Authentication_Method'] = "API Key";
	return $config;
}

function mo_api_authentication_postman_download( $method ) {
	$all_files_url = array(
		"api-key" => "https://developers.miniorange.com/static/postman/wp-rest-api-authentication/API_KEY_AUTHENTICATION_REQUEST.zip",
		"basic-username-password" => "https://developers.miniorange.com/static/postman/wp-rest-api-authentication/BASIC_AUTHENTICATION_USERNAME_PASSWORD.zip",
		"basic-client-credentials" => "https://developers.miniorange.com/static/postman/wp-rest-api-authentication/BASIC_AUTHENTICATION_CLIENT_CREDENTIALS.zip",
		"jwt-token" => "https://developers.miniorange.com/static/postman/wp-rest-api-authentication/JWT_AUTHENITCATION_TOKEN_REQUEST.zip",
		"jwt-resource" => "https://developers.miniorange.com/static/postman/wp-rest-api-authentication/JWT_AUTHENTICATION_RESOURCE_REQUEST.zip",
	);

	// Create postman sample folder if not exist 
	$upload_dir = wp_upload_dir();
	if( $upload_dir && isset( $upload_dir['basedir'] ) ) {
		$base_upload_dir = $upload_dir['basedir'];
		$postman_sample_folder = $base_upload_dir.DIRECTORY_SEPARATOR."postman-sample";
		
		if (!file_exists($postman_sample_folder) && !is_dir($postman_sample_folder)) {
			wp_mkdir_p($postman_sample_folder);
		} 
	}
	
	$filepath = $postman_sample_folder . DIRECTORY_SEPARATOR . $method . '.zip' ;

	if( !file_exists( $filepath ) ) {
		// Download file
		$tmp_file = download_url( $all_files_url[ $method ], 500000, false );
		// Copies the file to the final destination and deletes temporary file.
		copy( $tmp_file, $filepath );
		@unlink( $tmp_file );
	}

	$zip = new ZipArchive();
	$zip->open( $filepath );
	$contents = '';
	$filename = '';
	for( $i = 0; $i < $zip->numFiles; $i++ ){ 
		$stat = $zip->statIndex( $i ); 
		$filename = basename( $stat['name'] ); 
		$fp = $zip->getStream($filename);
		while (!feof($fp)) {
			$contents .= fread($fp, 2);
		}
	}
	header('Content-Disposition: attachment; filename ='.$filename);
	header('Content-Type: application/json');
	ob_clean();
	echo $contents;
	exit();
}