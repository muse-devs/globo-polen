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
          Informações do pedido
        </h2>
        <button class="btn-edit">editar</button>
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
  </form>
  <?php
}
