<?php

function polen_get_cart_form()
{
  $inputs = new Material_Inputs();
?>
  <form class="woocommerce-cart-form mt-4 cart-advanced">
    <div class="cart-step cart-step1">
      <header class="header mb-4">
        <h2 class="title">
          <div class="cart-step__ico cart-step__ico1"></div>
          <span>
            Informações do pedido
            <a href="javascript:void(0)" class="btn-edit">editar</a>
          </span>
        </h2>
      </header>
      <div class="cart-step__content">
        <?php
        $inputs->material_input(Material_Inputs::TYPE_TEXT, "name", "name", "Seu nome", true, "mb-3");
        $inputs->material_input(Material_Inputs::TYPE_EMAIL, "email", "email", "Seu e-mail", true, "mb-3");
        $inputs->material_input(Material_Inputs::TYPE_PHONE, "whatsapp", "whatsapp", "Seu Whatsapp (opcional)", false);
        $inputs->material_input_helper("Pode ficar tranquilo que enviaremos somente atualizações sobre o pedido");
        $inputs->material_button_outlined(Material_Inputs::TYPE_BUTTON, "next1", "Avançar", "mt-4");
        ?>
      </div>
    </div>
    <div class="divisor"></div>
    <div class="cart-step cart-step2">
      <header class="header mb-4">
        <h2 class="title">
          <div class="cart-step__ico cart-step__ico2"></div>
          Informações do vídeo
        </h2>
      </header>
      <div class="cart-step__content">
        <h3 class="subtitle">Para quem é o vídeo?</h3>
        <?php
        $icons_path = TEMPLATE_URI . "/assets/img/pol_form_icons/";
        $inputs->pol_select_advanced("praquem", "praquem", array(
          $inputs->pol_select_advanced_item($icons_path . "presente.png", "Presente", "presente"),
          $inputs->pol_select_advanced_item($icons_path . "mim.png", "Para mim", "paramim")
        ));
        ?>
        <div class="mt-3">
          <?php $inputs->material_input(Material_Inputs::TYPE_TEXT, "quemvai", "quemvai", "Quem vai receber o presente?"); ?>
        </div>
        <h3 class="subtitle mt-4">Qual é a ocasião do vídeo?</h3>
        <?php
        $inputs->pol_select_advanced("ocasiao", "ocasiao", array(
          $inputs->pol_select_advanced_item($icons_path . "aniversario.png", "Aniversário", "aniversario"),
          $inputs->pol_select_advanced_item($icons_path . "casamento.png", "Casamento", "casamento"),
          $inputs->pol_select_advanced_item($icons_path . "conselho.png", "Conselho", "conselho"),
          $inputs->pol_select_advanced_item($icons_path . "formatura.png", "Formatura", "formatura"),
          $inputs->pol_select_advanced_item($icons_path . "novidade.png", "Novidade", "novidade"),
          $inputs->pol_select_advanced_item($icons_path . "outras.png", "Outras", "outras")
        ));
        ?>
        <h3 class="subtitle mt-4">Instrução para o vídeo vídeo?</h3>

      </div>
    </div>
    <?php $inputs->material_button(Material_Inputs::TYPE_SUBMIT, "btn-buy", "Comprar agora", "mt-4"); ?>
  </form>
<?php
}
