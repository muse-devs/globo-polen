<?php
/** Template Name: Inscrições - Seja outro sendo você */
get_header(); the_content();
?>

<div class="row">
    <div class="col-12">
        <?php
        $product = get_product_masterclass(338);
        mc_get_top_banner($product);
        ?>
        <br>
        <div class="col-12 text-center mt-3">
            <h3 class="title mb-4">O que é a Masterclass?</h3>
            <p style="font-size: 15px;">Viver em sociedade é interagir e comunicar. Independente do seu projeto de vida, um dia vai precisar vender. Seja ideias, produtos ou você mesmo. Essa aula te convida a conhecer e dominar esse vendedor que existe em você, fazendo com que ele trabalhe para a sua felicidade.</p>
        </div>
        <br>
        <?php
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