<?php 
/* Template Name: Pesquisa de pedido */
 
get_header(); 
use Polen\Includes\Polen_Account;

?>
 
<div id="primary" class="content-area cart-other">
    <main id="main" class="site-main" role="main">
        <form action="" method="post">
            <?php wp_nonce_field('user_search_order', '_wpnonce', true, true); ?>
            <div>
                <h1>Número do pedido</h1>
                <span>Entrar com o número do pedido enviado para o seu e-mail.</span>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <input id="fan_email" class="form-control form-control-lg" name="fan_email" value="" placeholder="E-mail" required="required"/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <input id="order_number" class="form-control" name="order_number" value="" placeholder="Número do pedido" required="required"/>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-lg btn-block py-4" name="" value="">Buscar</button>
                    </div>
                </div>
            </div>
        </form>    
    </main>
</div>
 
<?php 

$_wpnonce = wp_verify_nonce( $_POST['_wpnonce'], 'user_search_order' );
if( $_wpnonce === 1 ){
    $polen_orders = new Polen_Account();

    $email = strip_tags( $_POST['fan_email'] );
    $order_number = strip_tags( $_POST['order_number'] );
    $fan_orders = $polen_orders->get_orders_by_user_email( $email, $order_number );

    if( !empty( $fan_orders ) ){ 
        foreach( $fan_orders as $orders ):
        ?>
        <p>Encontramos <?php count($fan_orders);?> pedido(s).</p>        
        <div class="row mt-5 d-flex justify-content-between align-items-center">
            <div class="col-md-1">
                <div class="row">
                    <span class="order-title"><?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $orders['order_id'] ); ?></span>
                </div>	
            </div>	
            <div class="col-md-1">
                <div class="row">
                    <div class="image-cropper">
                        <?php
                        $thumbnail = get_the_post_thumbnail_url($orders['product_id'], 'post-thumbnail');
                        ?>	
                        <img src="<?php  echo $thumbnail; ?>" class="profile-pic">
                    </div>
                </div>		
            </div>				
            <div class="col-md-3">
                <div class="row">
                    <span class="order-title"><?php echo  $orders['name']; ?></a>	
                </div>
                <div class="row">
                    <?php echo $orders['total'];?>
                </div>		
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <span class="order-title">Status</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <span>
                            <?php 
                            switch ( $orders['status'] ){
                                case 'on-hold':
                                    echo 'Aguardando aprovação';
                                    break;
                                default:	
                                    echo $orders['status'];
                                    break;			
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="text-center">
                        <a 	href="" 
                            class="btn-primary btn">
                            Ver mais
                        </a>
                    </div>
                </div>
            </div>
        </div>
<?php
        endforeach;
    }
}


get_footer();