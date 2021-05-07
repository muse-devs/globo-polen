<?php

get_header();

$product = wc_get_product( $product_id );
$product_post = get_post( $product->get_id() );
$talent = get_user_by( 'id', $product_post->post_author );

?>
    <main id="primary" class="site-main">
        Oieeee Reviewssss Aquiii!!!!
    </main><!-- #main -->
<?php

get_footer();
