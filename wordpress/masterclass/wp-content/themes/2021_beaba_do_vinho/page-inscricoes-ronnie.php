<?php
/** Template Name: Página - Inscrições */
get_header(); the_content();
?>

<div class="row mc-content">
    <div class="col-12">
        <?php
        $product = get_product_masterclass();
        mc_get_top_banner($product);
        mc_get_carrossel_how_to();
        mc_get_box_content();
        mc_get_buy_button($product);
        mc_get_bio();
        mc_get_footer();
        ?>
    </div>
</div>

<?php
get_footer();