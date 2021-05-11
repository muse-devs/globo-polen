<?php

defined('ABSPATH') || exit;

function get_icon($bool)
{
	if ($bool) {
		return Icon_Class::polen_icon_check_o();
	} else {
		return Icon_Class::polen_icon_exclamation_o();
	}
}

$notes = $order->get_customer_order_notes();
$order_number = $order->get_order_number();
$order_status = $order->get_status();

$flow_1 = array(
	'pending' => array(
		'title' => 'Pendente de pagamento',
		'description' => 'Seu número de pedido #' . $order_number . ' está aguardando pagamento.',
		'class' => 'fail',
		'subclass' => 'flow-1',
	),
	'payment-in-analysis' => array(
		'title' => 'Pagamento em análise',
		'description' => 'Seu número de pedido #' . $order_number . ' está em análise pela sua operadora de crédito.',
		'class' => 'complete',
		'subclass' => 'flow-1',
	),
	'payment-rejected' => array(
		'title' => 'Pagamento rejeitado',
		'description' => 'Seu número de pedido #' . $order_number . ' foi rejeitado pela sua operadora de crédito.',
		'class' => 'fail',
		'subclass' => 'flow-1',
	),
	'payment-approved' => array(
		'title' => 'Pagamento aprovado',
		'description' => 'Seu número de pedido #' . $order_number . ' foi aprovado.',
		'class' => 'complete',
		'subclass' => 'flow-1',
	),
);

$flow_2 = array(
	'order-expired' => array(
		'title' => 'Pedido expirado',
		'description' => 'Infelizmente o artista não aceitou o seu pedido em tempo hábil e seu pedido expirou.',
		'class' => 'fail',
		'subclass' => 'flow-2',
	),
	'talent-rejected' => array(
		'title' => 'O talento rejeitou',
		'description' => 'Infelizmente o talento não aceitou o seu pedido.',
		'class' => 'fail',
		'subclass' => 'flow-2',
	),
	'talent-accepted' => array(
		'title' => 'O talento aceitou',
		'description' => 'O talento aceitou o seu pedido.',
		'class' => 'complete',
		'subclass' => 'flow-2',
	),
	'_next-step' => array(
		'title' => 'Aguardando confirmação do talento',
		'description' => 'Caso seu pedido não seja aprovado pelo talento o seu dinheiro será devolvido imediatamente.',
		'class' => 'in-progress',
		'subclass' => 'flow-2',
	),
);

$flow_3 = array(
	'completed' => array(
		'title' => 'Seu vídeo está pronto!',
		'description' => 'O talento aceitou o seu pedido.',
		'class' => 'complete',
		'subclass' => 'flow-3',
	),
	'cancelled' => array(
		'title' => 'Seu pedido foi cancelado',
		'description' => 'Seu pedido foi cancelado.',
		'class' => 'fail',
		'subclass' => 'flow-3',
	),
);

if( isset( $flow_1[ $order_status ] ) ) {
	$flows = array(
		$flow_1[ $order_status ],
		'_next-step_1' => array(
			'title' => 'Aguardando confirmação do talento',
			'description' => 'Caso seu pedido não seja aprovado pelo talento o seu dinheiro será devolvido imediatamente.',
			'class' => 'in-progress',
			'subclass' => 'flow-2',
		),
		'_next-step_2' => array(
			'title' => 'Aguardando gravação do vídeo',
			'description' => 'Quando o artista disponibilizar o vídeo ele será exibido aqui.',
			'class' => 'in-progress',
			'subclass' => 'flow-3',
		),
	);
} elseif( isset( $flow_2[ $order_status ] ) ) {
	$flows = array(
		'payment-approved' => array(
			'title' => 'Pagamento aprovado',
			'description' => 'Seu número de pedido #' . $order_number . ' foi aprovado.',
			'class' => 'complete',
			'subclass' => 'flow-1',
		),
		$flow_2[ $order_status ],
		'_next-step_2' => array(
			'title' => 'Aguardando gravação do vídeo',
			'description' => 'Quando o artista disponibilizar o vídeo ele será exibido aqui.',
			'class' => 'in-progress',
			'subclass' => 'flow-3',
		),
	);
} elseif( isset( $flow_3[ $order_status ] ) ) {
	$flows = array(
		'payment-approved' => array(
			'title' => 'Pagamento aprovado',
			'description' => 'Seu número de pedido #' . $order_number . ' foi aprovado.',
			'class' => 'complete',
			'subclass' => 'flow-1',
		),
		'talent-accepted' => array(
			'title' => 'O talento aceitou',
			'description' => 'O talento aceitou o seu pedido.',
			'class' => 'complete',
			'subclass' => 'flow-2',
		),
		$flow_3[ $order_status ],
	);
}

?>

<div class="row my-4">
	<div class="col-12">
		<div class="order-flow d-flex">
			<div class="col-flow-icons mr-3">
				<?php 
				$i=1; 
				foreach( $flows as $slug => $status ) { 
					if( $status['subclass'] == 'flow-2' ) { 
						$class = '';
					}elseif( $status['subclass'] == 'flow-3' ) { 
						$class = '';
					} else {
						$class = ' justify-content-start ';
					}
				?>

				<div class="flow-icon d-flex flex-column <?php echo $class; ?> align-items-center <?php echo $status["class"]; ?>">
					<div class="flow <?php echo $status["subclass"]; ?>"><?php get_icon($status["success"]); ?></div>
					<div class="line line-<?php echo $i; ?>"></div>
				</div>

				<?php $i++; } ?>
			</div>
			<div class="col-flow-texts">
				<?php 
				foreach( $flows as $slug => $status ) { 
					if( $status['subclass'] == 'flow-2' ) { 
						$class = ' d-flex flex-column justify-content-center ';
					}elseif( $status['subclass'] == 'flow-3' ) { 
						$class = ' mt-4 ';
					} else {
						$class = '';
					}
				?>

				<div class="flow <?php echo $status["subclass"] . $class . ' ' . $status["class"]; ?>">
					<h2 class="title mb-2"><?php echo $status["title"]; ?></h2>
					<p class="description"><?php echo $status["description"]; ?></p>
				</div>

				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php
use \Polen\Includes\Polen_Order;
$order_is_completed = Polen_Order::is_completed( $order );
$class_disable  = $order_is_completed == true ? '' : ' disabled ' ;
$url_watch_video = $order_is_completed == true ? polen_get_link_watch_video_by_order_id( $order_number ) : '';
?>

<div class="row my-3">
	<div class="col-12">
		<a href="<?php echo $url_watch_video; ?>" class="btn btn-primary btn-lg btn-block<?= $class_disable; ?>">Assistir vídeo</a>
	</div>
</div>
