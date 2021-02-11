<?php

class Polen_Update_Fields {

    private $table_vendor;

    public function __construct( $static = false ) {
        if( $static ) {
            /* New User */
            add_action( 'user_new_form',  array( $this, 'fields' ), 10, 1 );
            add_action( 'user_register', array( $this, 'updateVendorProfile' ) );
            
            /* Edit or Profile User */
            add_action( 'show_user_profile',  array( $this, 'vendorFields' ), 10, 1 );
            add_action( 'edit_user_profile', array( $this, 'vendorFields' ), 10, 1 );
            add_action( 'edit_user_profile_update', array( $this, 'updateVendorProfile' ) );
            
            if( is_admin() && isset( $_REQUEST['user_id'] ) ) {
                require_once ABSPATH . '/wp-includes/pluggable.php';
                $user = get_user_by( 'id', $_REQUEST['user_id'] );
                if( isset( $user->caps['seller'] ) && $user->caps['seller'] == true ) {
                    add_filter( 'woocommerce_customer_meta_fields', array( $this, 'removeWooCommerceFields' ), 9999 );
                }
            }

            add_action( 'woocommerce_edit_account_form_start', array( $this, 'add_cpf_to_form' ) );
            add_action( 'woocommerce_edit_account_form_start', array( $this, 'add_phone_to_form' ) );
            add_filter( 'woocommerce_save_account_details', array( $this, 'c9_save_account_details' ) );

            add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'add_cpf_and_phone_to_checkout') );
            //add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'add_is_gift_to_checkout') );
            //add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'add_gift_fields_to_checkout') );
            add_action( 'woocommerce_checkout_shipping', array( $this, 'add_is_gift_to_checkout') );
            add_action( 'woocommerce_checkout_shipping', array( $this, 'add_gift_fields_to_checkout') );
            add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_order_meta_from_checkout' ) );
        }

        global $wpdb;
        $this->table_vendor = $wpdb->base_prefix . 'c9_vendor';
    }

    public function remove_woocommerce_fields() {
        return [];
    }

    public function fields( $user ) {
        // if( isset( $user->caps['seller'] ) && $user->caps['seller'] == true ) {
            require_once PLUGIN_CUBO9_MARKETPLACE_DIR . '/assets/php/metabox-seller-fields.php';
        // }
    }

    public function get_vendor_data( $user_id ) {
        global $wpdb;
        $sql = "SELECT * FROM `" . $this->table_vendor . "` WHERE `user_id`=" . intval( $user_id );
        $res = $wpdb->get_results( $sql );
        if( count( $res ) > 0 ) {
            return $res[0];
        }
    }

    public function update_vendor_profile( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }

        $args = array();
        $args['user_id'] = $user_id;

        // Segmento principal da Loja
        $store_category = (string) strip_tags( trim( $_POST['store_category'] ) );
        if( ! empty( $store_category ) ) {
            wp_set_object_terms( $user_id, $store_category, 'store_category', false );
        }

        // Aba "Dados da Loja"
        $natureza_juridica = (string) strip_tags( trim( $_POST['natureza_juridica'] ) );
        if( $natureza_juridica != 'PJ' && $natureza_juridica != 'PF' ) {
            $natureza_juridica = null;
        }
        $args['natureza_juridica'] = $natureza_juridica;

        if( $natureza_juridica == 'PJ' ) {
            $razao_social = (string) strip_tags( trim( $_POST['razao_social'] ) );
            $args['razao_social'] = $razao_social;

            $nome_fantasia = (string) strip_tags( trim( $_POST['nome_fantasia'] ) );
            $args['nome_fantasia'] = $nome_fantasia;

            $cnpj = (string) strip_tags( trim( $_POST['cnpj'] ) );
            $args['cnpj'] = $cnpj;
        }

        if( $natureza_juridica == 'PF' ) {
            $nome = (string) strip_tags( trim( $_POST['nome'] ) );
            $args['nome'] = $nome;

            $cpf = (string) strip_tags( trim( $_POST['cpf'] ) );
            $args['cpf'] = $cpf;
        }

        if( strip_tags( trim( $_POST['reter_iss'] ) ) == 'S' ) {
            $reter_iss = 'S';
        } else {
            $reter_iss = 'N';
        }
        $args['reter_iss'] = $reter_iss;

        // Aba "Informações de Contato"
        $email = (string) strip_tags( trim( $_POST['store_email'] ) );
        $args['email'] = $email;

        $telefone = (string) strip_tags( trim( $_POST['telefone'] ) );
        $args['telefone'] = $telefone;

        $celular = (string) strip_tags( trim( $_POST['celular'] ) );
        $args['celular'] = $celular;

        $whatsapp = (string) strip_tags( trim( $_POST['whatsapp'] ) );
        $args['whatsapp'] = $whatsapp;
        
        // Aba "Redes Sociais"
        $facebook = (string) strip_tags( trim( $_POST['facebook'] ) );
        $args['facebook'] = $facebook;

        $instagram = (string) strip_tags( trim( $_POST['instagram'] ) );
        $args['instagram'] = $instagram;

        $twitter = (string) strip_tags( trim( $_POST['twitter'] ) );
        $args['twitter'] = $twitter;

        $pinterest = (string) strip_tags( trim( $_POST['pinterest'] ) );
        $args['pinterest'] = $twitter;

        $linkedin = (string) strip_tags( trim( $_POST['linkedin'] ) );
        $args['linkedin'] = $linkedin;

        $youtube = (string) strip_tags( trim( $_POST['youtube'] ) );
        $args['youtube'] = $youtube;

        // Aba "Dados Bancários"
        $banco = strip_tags( trim( $_POST['banco'] ) );
        list( $codigo_banco, $nome_banco ) = explode( ":", $banco );
        $args['codigo_banco'] = $codigo_banco;
        $args['banco'] = $nome_banco;
        
        $agencia = (string) strip_tags( trim( $_POST['agencia'] ) );
        if( (int) $agencia == (int) 0 ) {
            $args['agencia'] = null;
        } else {
            $args['agencia'] = str_pad( $agencia, 4, "0", STR_PAD_LEFT );
        }
        
        $conta = (string) strip_tags( trim( $_POST['conta'] ) );
        if( empty( $conta ) ) {
            $args['conta'] = null;
        } else {
            $args['conta'] = $conta;
        }

        $tipo_conta = (string) strip_tags( trim( $_POST['tipo_conta'] ) );
        if( empty( $tipo_conta ) ) {
            $args['tipo_conta'] = null;
        } else {
            $args['tipo_conta'] = $tipo_conta;
        }

        // Aba "Configurações Financeiras"
        $subordinate_merchant_id = (string) strip_tags( trim( $_POST['subordinate_merchant_id'] ) );
        $mdr = (float) strip_tags( trim( $_POST['mdr'] ) );
        $fee = (int) strip_tags( trim( $_POST['fee'] ) );

        if( empty( $subordinate_merchant_id ) ) {
            $subordinate_merchant_id = null;
        }

        if( ! $mdr > 0 ) {
            $mdr = null;
        }

        if( ! $fee > 0 ) {
            $fee = null;
        }
    
        $args['subordinate_merchant_id'] = $subordinate_merchant_id;
        $args['mdr'] = $mdr;
        $args['fee'] = $fee;

        // Estático por enquanto
        $args['pais'] = 'BR';
        $args['store_alias'] = sanitize_title( $email );
        $args['store_url'] = get_bloginfo('url') . '/loja/' . sanitize_title( $email );

        global $wpdb;
        $vendorData = $this->getVendorData( $user_id );
        if( $vendorData && ! is_null( $vendorData ) && ! empty( $vendorData ) && isset( $vendorData->ID ) ) {
            $statusDate = new DateTime();
            $timeZone = new DateTimeZone( get_option('timezone_string') );
            $statusDate->setTimezone( $timeZone );
            $updated = $statusDate->format( 'Y-m-d H:i:s' );
            $args['updated'] = $updated;

            unset( $args['user_id'] );
            $where = array(
                'ID' => $vendorData->ID,
                'user_id' => $vendorData->user_id
            );
            $update = $wpdb->update(
                $this->table_vendor,
                $args,
                $where
            );

            
        } else {
            $args['updated'] = null;
            $insert = $wpdb->insert(
                $this->table_vendor,
                $args
            );
        }
    }


    /**
     * Add CPF to user account form
     */
    public function add_cpf_to_form() {
        $user = wp_get_current_user();

        if( is_account_page() ) {
            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="billing_cpf">
                    <?php _e( 'CPF', 'cubo9-marketplace' ); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_cpf" id="billing_cpf" value="<?php echo esc_attr( $user->billing_cpf ); ?>" />
                <div class="error-message"></div>
            </p>
        <?php
        } else {
            if( ! empty( $user->billing_cpf ) ) {
            ?>
                <input 	type="hidden" class="woocommerce-Input input-text" name="billing_cpf" id="billing_cpf" value="<?php echo esc_attr( $user->billing_cpf ); ?>" />
                <div class="error-message"></div>
            <?php
            } else {
                ?>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="billing_cpf"><?php _e( 'CPF', 'woocommerce' ); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_cpf" id="billing_cpf" value="<?php echo esc_attr( $user->billing_cpf ); ?>" />
                    <div class="error-message"></div>
                </p>
            <?php
            }
        }   
    }

    /**
     * Adicionar o campo de CPF no Checkout para caso o usuário não possua.
     */
    public function add_cpf_and_phone_to_checkout( $checkout ) {
        $billing_cpf = get_user_meta( get_current_user_id(), 'billing_cpf', true );
        if( ! $billing_cpf || is_null( $billing_cpf ) || empty( $billing_cpf ) || strlen( $billing_cpf ) != 14 ) {
            $args = array(
                "type"        => "text",
                "required"    => true,
                "class"       => array( "form-row-wide", "input-cpf" ),
                "label"       => "CPF",
                "label_class" => array( 'title-on-checkout-notes' ),
                "placeholder" => "Informe seu CPF",
                "maxlength"   => 14,
            );
            woocommerce_form_field( 'billing_cpf', $args, $checkout->get_value( 'billing_cpf' ) );
        }

        $billing_phone = get_user_meta( get_current_user_id(), 'billing_phone', true );
        if( ! $billing_phone || is_null( $billing_phone ) || empty( $billing_phone ) || strlen( $billing_phone ) != 14 ) {
            $args = array(
                "type"        => "text",
                "required"    => true,
                "class"       => array( "form-row-wide", "input-cpf" ),
                "label"       => "Telefone",
                "label_class" => array( 'title-on-checkout-notes' ),
                "placeholder" => "Informe seu Telefone",
                "maxlength"   => 14,
            );
            woocommerce_form_field( 'billing_phone', $args, $checkout->get_value( 'billing_phone' ) );
        }

    }

    /**
     * Salvar o campo de CPF do usuário no Checkout para caso o usuário não possua.
     */
    public function save_order_meta_from_checkout( $order_id ) {
        $billing_cpf = get_user_meta( $_customer_user, 'billing_cpf', true );
        if( ( ! $billing_cpf || is_null( $billing_cpf ) || empty( $billing_cpf ) || strlen( $billing_cpf ) != 14 )
            && ( isset( $_POST['billing_cpf'] ) && ! empty( trim( $_POST['billing_cpf'] ) ) && strlen( trim( $_POST['billing_cpf'] ) ) == '14' ) 
        ) {
            $_customer_user = get_post_meta( $order_id, '_customer_user', true );
            update_user_meta( $_customer_user, 'billing_cpf', trim( $_POST['billing_cpf'] ) );
            update_post_meta( $order_id, 'billing_cpf', trim( $_POST['billing_cpf'] ) );
        } else if( $billing_cpf && ! is_null( $billing_cpf ) && ! empty( $billing_cpf ) && strlen( $billing_cpf ) == 14 ) {
            update_post_meta( $order_id, 'billing_cpf', $billing_cpf );
        }
    }

    /**
     * Adicionar o campo de "É um presente", "Nome do destinatário" e "Mensagem para o destinatário".
     */
    public function add_is_gift_to_checkout() {
        $WC_Cubo9_MktplaceReduxSettings = get_option( 'WC_Cubo9_MktplaceReduxSettings' );
        $enable_gifts = ( (int) $WC_Cubo9_MktplaceReduxSettings['enable_gifts'] == (int) 1 ) ? true : false;
        $enable_gifts_custom_message = ( (int) $WC_Cubo9_MktplaceReduxSettings['enable_gifts_custom_message'] == (int) 1 ) ? true : false;
        
        if( $enable_gifts ) {
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <span class="woocommerce-input-wrapper">
                <label class="checkbox">
                    <input type="checkbox" class="woocommerce-Input input-checkbox" name="c9_is_gift" id="c9_is_gift" value="1" /> &nbsp; <?php _e( 'É um presente?', 'cubonove' ); ?>
                </label>
            </span>
        </p>
    <?php
        }
    }

    public function add_gift_fields_to_checkout( $checkout ) {
        $WC_Cubo9_MktplaceReduxSettings = get_option( 'WC_Cubo9_MktplaceReduxSettings' );
        $enable_gifts = ( (int) $WC_Cubo9_MktplaceReduxSettings['enable_gifts'] == (int) 1 ) ? true : false;
        $enable_gifts_custom_message = ( (int) $WC_Cubo9_MktplaceReduxSettings['enable_gifts_custom_message'] == (int) 1 ) ? true : false;
        
        if( $enable_gifts ) {
            $args = array(
                "type"     => "chekbox",
                "required" => false,
                "class"    => array( "form-control", "form-row", "input-is-gift" ),
                // 'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
                'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
                "label"    => __( 'É um presente', 'cubonove'),
                "value"    => '1',
            );
            woocommerce_form_field( 'c9_is_gift', $args );
        }
    }

    public function add_phone_to_form() {
        $user = wp_get_current_user();

        if( is_account_page() ) {
            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="billing_phone"><?php _e( 'Celular', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $user->billing_phone ); ?>" />
                <div class="error-message"></div>
            </p>
        <?php
        } else {
            if( ! empty( $user->billing_phone ) ) {
            ?>
                <input 	type="hidden" class="woocommerce-Input input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $user->billing_phone ); ?>" />
                <div class="error-message"></div>
            <?php
            } else {
                ?>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="billing_phone"><?php _e( 'Celular', 'woocommerce' ); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $user->billing_phone ); ?>" />
                    <div class="error-message"></div>
                </p>
            <?php
            }
        }   
    }

    public function save_account_details( $user_id ) {
        update_user_meta( $user_id, 'billing_cpf', sanitize_text_field( $_POST['billing_cpf'] ) );
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
}

new Polen_Update_Fields( true );