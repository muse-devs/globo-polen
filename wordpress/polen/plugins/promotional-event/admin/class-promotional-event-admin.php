<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://polen.me
 * @since      1.0.0
 *
 * @package    Promotional_Event
 * @subpackage Promotional_Event/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Promotional_Event
 * @subpackage Promotional_Event/admin
 * @author     Polen.me <glaydson.queiroz@polen.me>
 */
class Promotional_Event_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	const ORDER_METAKEY = 'promotional_event';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
    {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
    {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/promotional-event-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.2.0/css/bootstrap.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
    {
        wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', $this->version, false );
        wp_enqueue_script( 'dataTables-script', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/promotional-event-admin.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( 'ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    /**
     * Adiciona os menus no dashboard do wordpress
     *
     * @since    1.0.0
     */
    public function add_menu()
    {
        add_menu_page('Evento Promocional',
            'Evento Promocional',
            'manage_options',
            'promotional-event',
            array($this, 'show_promotions')
        );

        add_submenu_page('promotional-event',
            'Configurações',
            'Configurar Cupons',
            'manage_options',
            'options_event',
            array($this, 'show_options'),
        );
    }

    /**
     * View página principal do plugin
     *
     * @since    1.0.0
     */
    public function show_promotions()
    {
        $sql = new Coupons();
        $values_code = $sql->get_codes();
        require 'partials/promotional-event-admin-display.php';
    }

    /**
     * View página de opções do plugin
     *
     * @since    1.0.0
     */
    public function show_options()
    {
        $sql = new Coupons();
        $count = $sql->count_rows();
        require 'partials/promotional-event-options.php';
    }

    /**
     * Criar cupons
     *
     * @throws Exception
     */
    public function create_coupons()
    {
        $foo = addslashes($_POST['qty']);
        $sql = new Coupons();
        $sql->insert_coupons($foo);
        wp_send_json_success( 'ok', 200 );
        wp_die();
    }

    /**
     * Criar uma nova order e salvar cupom
     */
    function create_orders_video_autograph()
    {
        try{
            $coupon_code = !empty($_POST['coupon']) ? sanitize_text_field($_POST['coupon']) : null;
            $name = sanitize_text_field($_POST['name']);
            $city = sanitize_text_field($_POST['city']);
            $email = sanitize_text_field($_POST['email']);

            $address = array(
                'first_name' => $name,
                'email' => $email,
                'city' => $city,
                'state' => sanitize_text_field($_POST['state']),
                'country' => 'Brasil',
                'phone' => sanitize_text_field($_POST['phone']),
            );

            $coupon = new Coupons();
            $check = $coupon->check_coupoun_exist($coupon_code);
            $check_is_used = $coupon->check_coupoun_is_used($coupon_code);

            if (empty($coupon_code)) {
                throw new Exception('Cupon é obrigatório', 422);
                wp_die();
            }

            if (empty($check)) {
                throw new Exception('Cupon está incorreto ou não existe', 404);
                wp_die();
            }

            if ($check_is_used == 1) {
                throw new Exception('Cupon já foi utilizado', 401);
                wp_die();
            }

            $order = wc_create_order();
            $coupon->update_coupoun($coupon_code, $order->get_id());

            $order->add_meta_data(self::ORDER_METAKEY, 1, true);
            $order->add_meta_data('campaign', 'de-porta-em-porta', true);

            $order->update_status('wc-payment-approved');

            // ID Product
            global $Polen_Plugin_Settings;
            $product_id = $Polen_Plugin_Settings['promotional-event-text'];

            $quantity = 1;
            $product = wc_get_product($product_id);
            $order->add_product($product, $quantity);
            $order->set_address($address, 'billing');
            $order->set_address($address, 'shipping');

            $order_item_id = wc_add_order_item( $order->get_id(), array(
                'order_item_name' => $product->get_title(),
                'order_item_type' => 'line_item',
            ));

            $instruction = "{$name} de {$city} já garantiu sua cópia do livro 'De porta em porta'! 
            Envie um vídeo para agradecer e mande um alô para toda cidade.";

            wc_add_order_item_meta( $order_item_id, '_qty', $quantity, true );
            wc_add_order_item_meta( $order_item_id, '_product_id', $product->get_id(), true );
            wc_add_order_item_meta( $order_item_id, '_line_subtotal', 0, true );
            wc_add_order_item_meta( $order_item_id, '_line_total', 0, true );
            //Polen Custom Meta Order_Item
            wc_add_order_item_meta( $order_item_id, 'offered_by'            , '', true );

            wc_add_order_item_meta( $order_item_id, 'video_to'              , 'to_myself', true );
            wc_add_order_item_meta( $order_item_id, 'name_to_video'         , $name, true );
            wc_add_order_item_meta( $order_item_id, 'email_to_video'        , $email, true );
            wc_add_order_item_meta( $order_item_id, 'video_category'        , 'video-autografo', true );
            wc_add_order_item_meta( $order_item_id, 'instructions_to_video' , $instruction, true );

            wc_add_order_item_meta( $order_item_id, 'allow_video_on_page'   , 1, true );
            wc_add_order_item_meta( $order_item_id, '_fee_amount'           , 0, true );
            wc_add_order_item_meta( $order_item_id, '_line_total'           , 0, true );
            $order->save();

            $order = new \WC_Order($order->get_id());
            $order->calculate_totals();

            wp_send_json_success( 'ok', 200 );
            wp_die();

        } catch (\Exception $e) {
            wp_send_json_error(array('Error' => $e->getMessage()), 422);
            wp_die();
        }
    }

    /**
     * Verificar cupon
     */
    function check_coupon()
    {
        try{
            $coupon_code = !empty($_POST['coupon']) ? sanitize_text_field($_POST['coupon']) : null;

            $coupon = new Coupons();
            $check = $coupon->check_coupoun_exist($coupon_code);
            $check_is_used = $coupon->check_coupoun_is_used($coupon_code);

            if (empty($coupon_code)) {
                throw new Exception('Cupon é obrigatório', 422);
                wp_die();
            }

            if (empty($check)) {
                throw new Exception('Cupon está incorreto ou não existe', 404);
                wp_die();
            }

            if ($check_is_used == 1) {
                throw new Exception('Cupon já foi utilizado', 401);
                wp_die();
            }

            wp_send_json_success( 'Cupon Disponivél', 200 );
            wp_die();

        } catch (\Exception $e) {
            wp_send_json_error(array('Error' => $e->getMessage()), 422);
            wp_die();
        }
    }
}
