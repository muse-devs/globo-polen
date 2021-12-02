<?php

namespace Polen\Api;

use Automattic\WooCommerce\Client;
use DateTime;
use Exception;
use Polen\Includes\Polen_Order;
use WC_Coupon;

class Api_Checkout{

    private $woocommerce;
    const ORDER_METAKEY = 'hotsite';

    public function __construct()
    {
        $this->auth();
    }
    // TODO: Criar campo no redux para gerenciar essas chaves
    public function auth()
    {
        $this->woocommerce = new Client(
            'https://polen-api.c9t.pw',
            'ck_bbac69256370675706263779310cc2eaa27534c9',
            'cs_1c95fc725d7e5bdc367be454ec9c47dcfc584ede',
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
        try{
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
                wp_send_json_error($errors, 422);
                wp_die();
            }

            if (!$this->CPF_validate($fields['cpf'])) {
                throw new Exception('CPF Inválido', 422);
            }

            $product = wc_get_product($fields['product_id']);
            if (!$product->is_in_stock()) {
                throw new Exception('Produto sem estoque', 422);
            }

            $user = $this->create_new_user($data);

            $coupon = null;
            if (isset($fields['coupon'])) {
                $this->coupon_rules($fields['coupon']);
                $coupon = sanitize_text_field($fields['coupon']);
            }

            $order_woo = $this->order_payment_woocommerce($user, $fields['product_id'], $coupon);
            $this->add_meta_to_order($order_woo->id, $data);
            $tuna->process_payment($order_woo->id, $user->data, $fields);

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage(), 422);
            wp_die();
        }
    }

    /**
     * Criar usuario
     *
     * @param array $data
     * @return \WP_User
     */
    private function create_new_user(array $data): \WP_User
    {
        $userdata = array(
            'user_login' => $data['email'],
            'user_email' => $data['email'],
            'user_pass' => wp_generate_password(),
            'first_name' => $data['name'],
            'nickname' => $data['name'],
            'role' => 'customer',
        );

        $userId = wp_insert_user($userdata);
        $user = get_user_by('id', $userId);

        if (empty($user)) {
            $user = get_user_by('login', $data['email']);
        }

        $address = array(
            'billing_email' => $data['email'],
            'billing_cpf' => preg_replace('/[^0-9]/', '', $data['cpf']),
            'billing_country' => 'BR',
            'billing_phone' => preg_replace('/[^0-9]/', '', $data['phone']),
            'billing_cellphone' => preg_replace('/[^0-9]/', '', $data['phone']),
        );

        foreach ($address as $key => $value) {
            update_user_meta($user->ID, $key, $value);
        }

        return $user;
    }

    /**
     * Criar uma order no woocommerce
     *
     * @param $user
     * @param $product_id
     * @param null $coupon
     */
    public function order_payment_woocommerce($user, $product_id, $coupon = null)
    {
        $data = [
            'payment_method' => 'tuna_payment',
            'payment_method_title' => 'API TUNA',
            'set_paid' => false,
            'billing' => [
                'first_name' => $user->first_name,
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
            'shipping_lines' => [
                [
                    'method_id' => 'tuna_payment',
                    'method_title' => 'Cartão de Crédito',
                ]
            ],
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
            'product_id' => 'ID do Produto é obrigatório',
        ];
    }

    /**
     * Adicionar metas na order
     *
     * @param int $order_id
     * @param array $data
     * @throws Exception
     */
    private function add_meta_to_order(int $order_id, array $data): void
    {
        $order = wc_get_order($order_id);
        $email = $data['email'];
        $product = wc_get_product($data['product_id']);

        $order->update_meta_data('_polen_customer_email', $email);
        $order->add_meta_data(self::ORDER_METAKEY, 'galo', true);

        $quantity = 1;
        $order_item_id = $order->add_product($product, $quantity);

        wc_add_order_item_meta($order_item_id, '_qty', $quantity, true);
        wc_add_order_item_meta($order_item_id, '_product_id', $product->get_id(), true);
        wc_add_order_item_meta($order_item_id, '_line_subtotal', '0', true);
        wc_add_order_item_meta($order_item_id, '_line_total', '0', true);
        wc_add_order_item_meta($order_item_id, 'offered_by', '', true);

        wc_add_order_item_meta($order_item_id, 'video_to', 'to_myself', true);
        wc_add_order_item_meta($order_item_id, 'name_to_video', $data['name'], true);
        wc_add_order_item_meta($order_item_id, 'email_to_video', $email, true);
        wc_add_order_item_meta($order_item_id, 'video_category', 'Vídeo-Autógrafo', true);
        wc_add_order_item_meta($order_item_id, 'instructions_to_video', $data['instruction'], true);

        wc_add_order_item_meta($order_item_id, 'allow_video_on_page', 'on', true);
        wc_add_order_item_meta($order_item_id, '_fee_amount', 0, true);
        wc_add_order_item_meta($order_item_id, '_line_total', 0, true);

        $interval  = Polen_Order::get_interval_order_event();
        $timestamp = Polen_Order::get_deadline_timestamp_by_social_event($order, $interval);
        $order->add_meta_data(Polen_Order::META_KEY_DEADLINE, $timestamp, true );

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
