<?php
use Polen\Includes\Polen_Talent;
$polen_talent = new Polen_Talent();

$talent_orders = '';
$logged_user = wp_get_current_user();
if( in_array( 'user_talent',  $logged_user->roles ) )
{ 
	$talent_id = $logged_user->ID;
	$talent_orders = $polen_talent->get_talent_orders( $talent_id );
}	

?>
<section>
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Suas Solicitações', 'polen' ); ?></h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<?php
		if( empty( $talent_orders ) ){
			echo "<p>Você não possui novas solicitações</p>";
		}else{
			echo "<p>Você tem ".count($talent_orders)." pedidos de vídeo</p>"; 
			if( count($talent_orders) > 0 ){
				foreach( $talent_orders as $order ): 
				?>

					<div class="container">
						<div class="row">
							<div class="col-md-12">

							</div>
						</div>	
						<div class="row">
							<div class="col-md-12">
								<span>Ocasião</span>
								<div><?php echo $order['category'];?></div>
							</div>
						</div>	
						<div class="row">
							<div class="col-md-4">
								<span>Oferecido por</span>
								<div><?php echo $order['from'];?></div>
							</div>
							<div class="col-md-8">
								<span>Instruções</span>
								<div><?php echo $order['instructions'];?></div>
							</div>
						</div>	
						<div class="row">
							<div class="col-md-4">
								<span>Mensagem para:</span>
								<div><?php echo $order['name'];?></div>
							</div>
						</div>
					</div>

		<?php	
				endforeach;
			}
		}



		?>


	</div><!-- .page-content -->
</section><!-- .no-results -->
