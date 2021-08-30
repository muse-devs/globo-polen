<?php

function event_promotional_url_home()
{
    return site_url( Promotional_Event_Rewrite::BASE_URL . '/de-porta-em-porta' );
}

function event_promotional_url_code_validation()
{
    return event_promotional_url_home() . '/validar-codigo';
}

function event_promotional_url_order( $cupom_code )
{
    return event_promotional_url_home() . '/pedido?cupom_code=' . $cupom_code;
}

function event_promotional_url_success( $order_id, $order_key )
{
    return event_promotional_url_home() . "/confirmado?order={$order_id}&order_key={$order_key}";
}

function event_promotional_is_home()
{
    $is_set = isset( $GLOBALS[ Promotional_Event_Rewrite::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME ] );
    if( $is_set && $GLOBALS[ Promotional_Event_Rewrite::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME ] == '1' ) {
        return true;
    }
    return false;
}

function event_promotional_is_app()
{
    $is_set = isset( $GLOBALS[ Promotional_Event_Rewrite::QUERY_VARS_EVENT_PROMOTIONAL_APP ] );
    if( $is_set && $GLOBALS[ Promotional_Event_Rewrite::QUERY_VARS_EVENT_PROMOTIONAL_APP ] == '1' ) {
        return true;
    }
    return false;
}


function event_promotional_order_is_event_promotional( $order )
{
    $is_ep = $order->get_meta( Promotional_Event_Admin::ORDER_METAKEY, true );
    if( $is_ep && $is_ep == '1' ) {
        return true;
    }
    return false;
}


/**
 *
 */
function event_promotional_get_order_flow_layout($array_status, $order_number, $whatsapp_number = "", $redux_whatsapp = 0)
{
	//status: complete, in-progress, pending, fail
	//title: string
	//description: string

	if (empty($array_status) || !$array_status) {
		return;
	}

	$class = "";
	$new_array = array_values($array_status);

	if ($new_array[0]['status'] === "fail" || $new_array[0]['status'] === "in-progress") {
		$class = " none";
	}
	if ($new_array[1]['status'] === "complete" && $new_array[2]['status'] !== "fail") {
		$class = " half";
	}
	if ($new_array[2]['status'] === "complete") {
		$class = " complete";
	}

?>
	<div class="row">
		<div class="col-md-12">
			<ul class="order-flow<?php echo $class; ?>">
				<?php foreach ($array_status as $key => $value) : ?>
					<li class="item <?php echo "item" . $key; ?> <?php echo $value['status']; ?>">
						<span class="background status">
							<?php Icon_Class::polen_icon_check_o(); ?>
							<?php Icon_Class::polen_icon_exclamation_o(); ?>
						</span>
						<span class="text">
							<h4 class="title"><?php echo $value['title']; ?></h4>
							<p class="description"><?php echo $value['description']; ?></p>
							<?php
							if ($redux_whatsapp == "1" && !isset($first)) {
								$first = true;
								polen_form_add_whatsapp($order_number, $whatsapp_number);
							}
							?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php
}

function event_promotional_get_order_flow_obj($order_number, $order_status, $email_billing = null )
{
    $flow_1_complement_email = '';
    if( !empty( $email_billing ) ) {
        $flow_1_complement_email = "<br />Todas as atualizações serão enviadas para o email <strong>{$email_billing}</strong>.";
    }
    $flow_1 = array(
        'pending' => array(
            'title' => 'Pendente de pagamento',
            'description' => 'Seu número de pedido #' . $order_number . ' está aguardando pagamento. ' . $flow_1_complement_email,
            'status' => 'fail',
        ),
        'payment-in-analysis' => array(
            'title' => 'Pagamento em análise',
            'description' => 'Seu número de pedido #' . $order_number . ' está em análise pela sua operadora de crédito.',
            'status' => 'complete',
        ),
        'payment-rejected' => array(
            'title' => 'Pagamento rejeitado',
            'description' => 'Seu número de pedido #' . $order_number . ' foi rejeitado pela sua operadora de crédito.',
            'status' => 'fail',
        ),
        'payment-approved' => array(
            'title' => 'Recebemos seu pedido de vídeo-autógrafo',
            'description' => 'Seu número de pedido é #' . $order_number . ' foi aprovado. ' . $flow_1_complement_email,
            'status' => 'complete',
        ),
    );

    $flow_2 = array(
        'order-expired' => array(
            'title' => 'Pedido expirado',
            'description' => 'Infelizmente o artista não aceitou o seu pedido em tempo hábil e seu pedido expirou.',
            'status' => 'fail',
        ),
        'talent-rejected' => array(
            'title' => 'O talento rejeitou',
            'description' => 'Infelizmente o talento não aceitou o seu pedido.',
            'status' => 'fail',
        ),
        'talent-accepted' => array(
            'title' => 'O Luciano aceitou',
            'description' => 'O Luciano aceitou o seu pedido.',
            'status' => 'complete',
        ),
        '_next-step' => array(
            'title' => 'Aguardando confirmação',
            'description' => 'Você será informado quando o Luciano visualizar e aceitar a sua solicitação de vídeo-autógrafo.',
            'status' => 'in-progress',
        ),
    );

    $url_user_order = site_url('my-account/view-order/' . $order_number);
    $flow_3 = array(
        'completed' => array(
            'title' => 'Seu vídeo está pronto!',
            'description' => 'Corre lá e confira seu vídeo-autógrafo.',
            'status' => 'complete',
        ),
        'cancelled' => array(
            'title' => 'Seu pedido foi cancelado',
            'description' => 'Seu pedido foi cancelado.',
            'status' => 'fail',
        ),
    );

    if (isset($flow_1[$order_status])) {
        $flows = array(
            $flow_1[$order_status],
            '_next-step_1' => array(
                'title' => 'Aguardando confirmação',
                'description' => 'Você será informado quando o Luciano visualizar e aceitar a sua solicitação de vídeo-autógrafo.',
                'status' => $flow_1[$order_status]['status'] === "fail" ? 'pending' : 'in-progress',
            ),
            '_next-step_2' => array(
                'title' => 'Aguardando gravação do vídeo',
                'description' => 'Quando o Luciano disponibilizar o vídeo ele será exibido aqui.',
                'status' => 'pending',
            ),
        );
    } elseif (isset($flow_2[$order_status])) {
        $flows = array(
            'payment-approved' => array(
                'title' => 'Pagamento aprovado',
                'description' => 'Seu número de pedido #' . $order_number . ' foi aprovado.',
                'status' => 'complete',
            ),
            $flow_2[$order_status],
            '_next-step_2' => array(
                'title' => 'Aguardando gravação do vídeo',
                'description' => 'Quando o Luciano disponibilizar o vídeo ele será exibido aqui.',
                'status' => 'in-progress',
            ),
        );
    } elseif (isset($flow_3[$order_status])) {
        $flows = array(
            'payment-approved' => array(
                'title' => 'Pagamento aprovado',
                'description' => 'Seu número de pedido #' . $order_number . ' foi aprovado.',
                'status' => 'complete',
            ),
            'talent-accepted' => array(
                'title' => 'O talento aceitou',
                'description' => 'O talento aceitou o seu pedido.',
                'status' => 'complete',
            ),
            $flow_3[$order_status],
        );
    }

    return $flows;
}


function event_get_magalu_url()
{
	return "https://www.magazineluiza.com.br/livro-de-porta-em-porta-luciano-huck-com-brinde/p/231238100/li/adml/";
}
