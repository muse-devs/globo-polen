<?php

namespace Polen\Api;

use Automattic\WooCommerce\Client;
use DateTime;
use Exception;
use Polen\Includes\Debug;
use Polen\Includes\Polen_Checkout_Create_User;
use Polen\Includes\Polen_Order;
use WC_Coupon;

class Api_Checkout
{

    private $woocommerce;
    const ORDER_METAKEY = 'hotsite';
    const USER_METAKEY  = 'hotsite';

    public function __construct()
    {
        $this->auth();
    }
    // TODO: Criar campo no redux para gerenciar essas chaves
    public function auth()
    {
        $this->woocommerce = new Client(
            site_url(),
            'ck_58c13313d131b1f4d68cf59f9bf318078e2681af',
            'cs_6cde6f9554eb2a668a160695779be5071064b681',
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );
    }

    /**
     * Criação de uma order completa, seguindo os passos:
     *
     * 1- Verificar se os campos obrigatorios foram passados
     * 2- Verificar se o CPF é valido
     * 3- Criar um novo usuario caso cliete esteja deslogado
     * 4- Verificar status do cupom
     * 5- Registrar order no woocommerce
     * 6- Adicionar meta dados de acordo com o sistema
     * 7- Fazer requisição para o TUNA
     * 8- Atualizar status de acordo com o response do TUNA
     *
     * @param $request
     * @return array|void
     */
    public function create_order($request)
    {
        try {
            $tuna = new Api_Gateway_Tuna();
            $fields = $request->get_params();
            $required_fields = $this->required_fields();
            $errors = array();

            foreach ($required_fields as $key => $field) {
                if (!isset($fields[$key]) && !empty($field)) {
                    $errors[] = "O campo {$field} é obrigatório";
                }
                $data[$key] = sanitize_text_field($fields[$key]);
            }

            if (!empty($errors)) {
                wp_send_json_error( $errors, 422 );
                wp_die();
            }

            if (!$this->CPF_validate($fields['cpf'])) {
                throw new Exception( 'CPF Inválido', 422 );
            }

            $product = wc_get_product( $fields['product_id'] );
            if( empty( $product ) ) {
                throw new Exception( 'Produto inválido', 422 );
            }
            if (!$product->is_in_stock()) {
                throw new Exception( 'Produto sem estoque', 422 );
            }

            $user = $this->create_new_user( $data );

            $coupon = null;
            if (isset($fields['coupon'])) {
                $this->coupon_rules($fields['coupon']);
                $coupon = sanitize_text_field($fields['coupon']);
            }

            $order_woo = $this->order_payment_woocommerce($user['user_object']->data, $fields['product_id'], $coupon);
            
            $this->add_meta_to_order($order_woo->id, $data);
            $payment = $tuna->process_payment($order_woo->id, $user, $fields);
            if ( $payment['order_status'] != 200 ) {
                throw new Exception($payment['message']);
            }
            wp_send_json_success( $payment, 201 );

        } catch (\Exception $e) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
            wp_die();
        }
    }
    

    /**
     * Criar usuario
     *
     * @param array $data
     * @return \WP_User
     */
    private function create_new_user( array $data ): array
    {
        $userdata = array(
            'user_login' => $data['email'],
            'user_email' => $data['email'],
            'user_pass' => wp_generate_password(),
            'first_name' => $data['name'],
            'nickname' => $data['name'],
            // 'role' => 'customer',
        );

        $user['new_account'] = false;
        $user_wp = get_user_by( 'email', $userdata['user_email'] );
        if( false === $user_wp ) {
            $userId = wc_create_new_customer( $userdata['user_email'], $userdata['user_email'], $userdata['user_pass'], $userdata );
            $user_wp = get_user_by( 'ID', $userId );
            $user['new_account'] = true;
            update_user_meta( $userId, self::USER_METAKEY, 'polen_galo' );
            update_user_meta( $userId, Polen_Checkout_Create_User::META_KEY_CREATED_BY, 'checkout' );
        }

        $user['user_object'] = $user_wp;

        $address = array(
            'billing_email' => $data['email'],
            'billing_cpf' => preg_replace('/[^0-9]/', '', $data['cpf']),
            'billing_country' => 'BR',
            'billing_phone' => preg_replace('/[^0-9]/', '', $data['phone']),
            'billing_cellphone' => preg_replace('/[^0-9]/', '', $data['phone']),
        );

        foreach ($address as $key => $value) {
            update_user_meta($user['user_object']->ID, $key, $value);
        }
        return $user;
    }

    /**
     * Criar uma order no woocommerce
     *
     * @param WP_User $user
     * @param int $product_id
     * @param string $coupon
     */
    public function order_payment_woocommerce($user, $product_id, $coupon = null)
    {
        $data = [
            'payment_method' => 'tuna_payment',
            'payment_method_title' => 'API TUNA',
            'set_paid' => false,
            'customer_id'   => $user->ID,
            'customer_note' => 'created by api rest',
            'created_via'   => 'checkout_rest_api',
            'billing' => [
                'first_name' => $user->display_name,
                'country' => get_user_meta($user->ID, 'billing_country', true),
                'email' => $user->user_email,
                'phone' => get_user_meta($user->ID, 'billing_cellphone', true),
            ],
            'line_items' => [
                [
                    'product_id' => $product_id,
                    'quantity' => 1,
                ],
            ],
            // 'shipping_lines' => [
            //     [
            //         'method_id' => 'tuna_payment',
            //         'method_title' => 'Cartão de Crédito',
            //     ]
            // ],
        ];

        if ($coupon !== null ) {
            $data['coupon_lines'][] = [
                'code' => $coupon,
            ];
        }

        return $this->woocommerce->post('orders', $data);
    }

    /**
     * Verificar se o cupom está válido para a criação da order
     *
     * @param $code_id
     */
    public function coupon_rules($code_id)
    {
        try {
            $coupon = new WC_Coupon($code_id);

            if (!get_post($coupon->get_id())) {
                throw new Exception('Cupom não existe', 422);
            }

            $coupon_rules = $this->woocommerce->get("coupons/{$coupon->get_id()}");

            if (!empty($coupon_rules->usage_limit) && $coupon_rules->usage_limit >= $coupon_rules->usage_count) {
                throw new Exception('Cupom já obteve o seu limite de uso', 422);
            }

            if (!empty($coupon_rules->usage_limit_per_user)) {
                if (!is_user_logged_in()) {
                    throw new Exception('Você precisa está logado para utilizar esse cupom', 422);
                }

                $current_user = wp_get_current_user();

                if (in_array($current_user->user_email, $coupon_rules->used_by)) {
                    throw new Exception('Você já utilizou esse cupom', 422);
                }
            }

            if (!empty($coupon_rules->date_expires_gmt)) {
                $last_date = DateTime::createFromFormat('Y-m-d H:i:s', $coupon_rules->date_expires_gmt);
                $today = new DateTime();

                if ($today > $last_date) {
                    throw new Exception('Cupom já expirou', 422);
                }
            }

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage(), 422);
            wp_die();
        }

    }

    /**
     * Retorna todos os campos do formulário que são obrigatórios
     */
    private function required_fields(): array
    {
        return [
            'name' => 'Nome',
            'email' => 'E-mail',
            'phone' => 'Celular/Telefone',
            'cpf' => 'CPF',
            'instruction' => 'Instrução',
            'video_to' =>  'Endereçamento do vídeo',
            'video_category' => 'Categoria do vídeo',
            'name_to_video' => 'Nome de quem receberá o vídeo',
            'allow_video_on_page' => 'Configuração de exibição',
            'product_id' => 'ID do Produto',
        ];
    }

    /**
     * Adicionar metas na order
     *
     * @param int $order_id
     * @param array $data
     * @throws Exception
     */
    private function add_meta_to_order(int $order_id, array $data)
    {
        $order = wc_get_order($order_id);
        $email = $data['email'];
        $status = $data['allow_video_on_page'] ? 'on' : 'off';
        // $product = wc_get_product($data['product_id']);

        $order->update_meta_data('_polen_customer_email', $email);
        $order->add_meta_data( self::ORDER_METAKEY, 'polen_galo', true);

        // $order_item_id = wc_add_order_item($order_id, array(
        //     'order_item_name' => $product->get_title(),
        //     'order_item_type' => 'line_item', // product
        // ));
        $items = $order->get_items();
        $item = array_pop( $items );
        $order_item_id = $item->get_id();
        // $quantity = 1;

        // wc_add_order_item_meta($order_item_id, '_qty', $quantity, true);
        wc_add_order_item_meta($order_item_id, 'offered_by'           , $data['name'], true);
        wc_add_order_item_meta($order_item_id, 'video_to'             , $data['video_to'], true);
        wc_add_order_item_meta($order_item_id, 'name_to_video'        , $data['name_to_video'], true);
        wc_add_order_item_meta($order_item_id, 'email_to_video'       , $email, true);
        wc_add_order_item_meta($order_item_id, 'video_category'       , $data['video_category'], true);
        wc_add_order_item_meta($order_item_id, 'instructions_to_video', $data['instruction'], true);
        wc_add_order_item_meta($order_item_id, 'allow_video_on_page'  , $status, true);

        $interval  = Polen_Order::get_interval_order_basic();
        $timestamp = Polen_Order::get_deadline_timestamp($order, $interval);
        Polen_Order::save_deadline_timestamp_in_order($order, $timestamp);
        $order->add_meta_data(Polen_Order::META_KEY_DEADLINE, $timestamp, true);

        $order->save();
    }

    /**
     * Verifica se um CPF é válido
     *
     * @param string $cpf
     * @return bool
     */
    private function CPF_validate(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
