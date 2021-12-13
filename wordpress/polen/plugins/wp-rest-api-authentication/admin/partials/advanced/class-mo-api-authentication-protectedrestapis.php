<?php
	
	class Mo_API_Authentication_Admin_ProtectedRestAPIs {
	
		public static function mo_api_authentication_protectedrestapis() {
			self::protect_wp_rest_apis();
		}

		public static function protect_wp_rest_apis(){
			$democss = "width: 350px; height:35px;";
		?>
            <style>
                #protectedrestapi_container ul li {
                    padding-left: 20px;
                }

                #protectedrestapi_container em {
                    font-size: 0.8em;
                }
            </style>

            <script>
                function protectedrestapi_namespace_click(namespace, id) {
                    if (jQuery('#protectedrestapi_namespace_' + id).is(":checked")) {
                        jQuery("input[data-namespace='" + namespace + "']").prop('checked', true);
                    } else {
                        jQuery("input[data-namespace='" + namespace + "']").prop('checked', false);
                    }
                };
            </script>

            <div class="mo_table_layout">
                <h1>Restrict Public Access to WP REST APIs</h1>
                <?php settings_errors( 'ProtectedRestAPI_notices' ); ?>
                <p>All the REST APIs listed below are protected from public access. You can uncheck the checkboxes to make it publicly accessible.</p>

                <form method="post" action="" id="ProtectedRestAPI_form">
                    <input type="hidden" name="option" value="mo_api_authentication_protected_apis_form">
                    <?php wp_nonce_field( '' ); ?>
					<?php wp_nonce_field('ProtectedRestAPI_admin_nonce','ProtectedRestAPI_admin_nonce_fields'); ?>

                    <div id="protectedrestapi_container"><?php self::ProtectedRestAPI_display_route_checkboxes(); ?></div>
                    <?php submit_button(); ?>
                    <input type="submit" name="reset" value="Reset Settings">
                </form>
                <h4>To authenticate third-party plugin/Custom API endpoints <a href="admin.php?page=mo_api_authentication_settings&tab=licensing" rel="noopener noreferrer">Click here</a> to Upgrade Now.</h4>
            </div>

		<?php
		}

        public static function ProtectedRestAPI_display_route_checkboxes() {
            $wp_rest_server     = rest_get_server();
            $all_namespaces     = $wp_rest_server->get_namespaces();
            $all_routes         = array_keys( $wp_rest_server->get_routes() );
            if(!get_option('mo_api_authentication_init_protected_apis')) {
                mo_api_authentication_reset_api_protection();
                update_option('mo_api_authentication_init_protected_apis', 'true');
            }
            $whitelisted_routes = is_array( get_option( 'mo_api_authentication_protectedrestapi_route_whitelist' ) ) ? get_option( 'mo_api_authentication_protectedrestapi_route_whitelist' ) : array();
            error_log("whitelisted: ".print_r($whitelisted_routes,true));

            $loopCounter       = 0;
            $current_namespace = '';

            foreach ( $all_routes as $route ) {
                $is_route_namespace = in_array( ltrim( $route, "/" ), $all_namespaces );
                $checkedProp        = self::ProtectedRestAPI_get_route_checked_prop( $route, $whitelisted_routes );
                $route_disabled     = (self::checkRouteIsWPStandardOrNot( $route ) || get_option('mo_rest_api_protect_migrate')) ? "" : "disabled";
                if ( $is_route_namespace || "/" == $route ) {
                    $current_namespace = $route;
                    if ( 0 != $loopCounter ) {
                        echo "</ul>";
                    }

                    $route_for_display = ( "/" == $route ) ? "/" . esc_html__( "REST API ROOT", "disable-json-api" )  : esc_html( $route );

                    echo "<h2><label><input name='rest_routes[]' value='" . esc_attr($route) . "' type='checkbox' id='protectedrestapi_namespace_" . esc_attr( $loopCounter ) ."' onclick='protectedrestapi_namespace_click(\"". esc_attr($route) ."\", esc_attr( $loopCounter ) )' " . esc_attr( $checkedProp ) ." " . esc_attr( $route_disabled ) . " >&nbsp;".esc_html($route_for_display)."</label></h2><ul>";

                    if ( "/" == $route ) {
                        echo "<li>" . sprintf( esc_html__( "On this website, the REST API root is %s", "disable-json-api" ), "<strong>" . esc_url( rest_url() ) . "</strong>" ) . "</li>";
                    }
                } else {
                    echo "<li><label><input name='rest_routes[]' value='". esc_attr( $route ) ."' type='checkbox' ' data-namespace='". esc_attr( $current_namespace ) ."' " . esc_attr( $checkedProp ) ." " . esc_attr( $route_disabled ) . ">&nbsp;" . esc_html( $route ) . "</label></li>";
                }

                $loopCounter ++;
            }
            echo "</ul>";
        }

        public static function checkRouteIsWPStandardOrNot( $route ) {
            if (stripos($route, '/wp/v2') === false){
                return false;
            } else {
                return true;
            }
        }


        public static function ProtectedRestAPI_get_route_checked_prop( $route, $whitelisted_routes ) {

            if ( self::checkRouteIsWPStandardOrNot( $route ) || get_option('mo_rest_api_protect_migrate')) {
                error_log("checking for ".esc_html($route));
    
                $is_route_checked = in_array( esc_html( $route ), $whitelisted_routes, true );
                return checked( $is_route_checked, true, false );
            } else {
                return false;
            }

        }
	}