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
$order_status = wc_get_order_status_name($order->get_status());

$flow_1 = array(
	"success" => true,
	"title" => "Pedido feito com sucesso",
	"description" => "Seu número de pedido é " . $order_number,
	"class" => "complete"
);

$flow_2 = array(
	"success" => true,
	"title" => "Aguardando confirmação do talento",
	"description" => "Caso seu pedido não seja aprovado pelo talento o seu dinheiro será devolvido imediatamente.",
	"class" => "in-progress"
);

$flow_3 = array(
	"success" => true,
	"title" => "Aguardando gravação do vídeo",
	"description" => "Quando o artista disponibilizar o vídeo ele será exibido aqui",
	"class" => "waiting"
); //se falhar, "class" => "fail"
?>

<div class="row my-4">
	<div class="col-12">
		<div class="order-flow d-flex">
			<div class="col-flow-icons mr-3">
				<div class="flow-icon d-flex flex-column justify-content-start align-items-center <?php echo $flow_1["class"]; ?>">
					<div class="flow flow-1"><?php get_icon($flow_1["success"]); ?></div>
					<div class="line line-1"></div>
				</div>
				<div class="flow-icon d-flex flex-column align-items-center <?php echo $flow_2["class"]; ?>">
					<div class="flow flow-2"><?php get_icon($flow_2["success"]); ?></div>
					<div class="line line-2"></div>
				</div>
				<div class="flow-icon d-flex flex-column align-items-center <?php echo $flow_3["class"]; ?>">
					<div class="flow flow-3"><?php get_icon($flow_3["success"]); ?></div>
				</div>
			</div>
			<div class="col-flow-texts">
				<div class="flow flow-1 <?php echo $flow_1["class"]; ?>">
					<h2 class="title mb-2"><?php echo $flow_1["title"]; ?></h2>
					<p class="description"><?php echo $flow_1["description"]; ?></p>
				</div>
				<div class="flow flow-2 d-flex flex-column justify-content-center <?php echo $flow_2["class"]; ?>">
					<h2 class="title mb-2"><?php echo $flow_2["title"]; ?></h2>
					<p class="description"><?php echo $flow_2["description"]; ?></p>
				</div>
				<div class="flow flow-3 mt-4 <?php echo $flow_3["class"]; ?>">
					<h2 class="title mb-2"><?php echo $flow_3["title"]; ?></h2>
					<p class="description"><?php echo $flow_3["description"]; ?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
if( !is_user_logged_in() ) :
?>

<div class="row my-3">
	<div class="col-12">
		<a href="/register" class="btn btn-primary btn-lg btn-block">Criar uma conta</a>
	</div>
</div>

<?php
else:
?>

<div class="row my-3">
	<div class="col-12">
		<a href="/my-account/" class="btn btn-primary btn-lg btn-block">Meu pedidos</a>
	</div>
</div>

<?php
endif;