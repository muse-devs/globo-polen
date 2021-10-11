<?php

// Função temporária para pegar talentos Business

use Polen\Includes\Polen_Product_B2B;

function bus_get_talents()
{
  $products_id = Polen_Product_B2B::get_all_product_ids( 100 );
  return !empty($products_id) ? array_chunk( $products_id, 4 ) : array();
}

function bus_get_header()
{
?>
  <section class="row mt-5 bus-header">
    <div class="col-12 col-md-6 m-auto">
      <h1 class="title text-center mb-4">Polen para Empresas</h1>
      <p class="description text-center mb-5">Aproveite o poder das celebridades para espalhar a emoção e potencializar o seu negócio! Tudo com muita rapidez e facilidade para melhor atender à sua empresa.</p>
      <a href="#bus-form-wrapper" class="btn btn-primary btn-lg btn-block">Pedir um Polen para o meu negócio</a>
    </div>
  </section>
<?php
}

function bus_get_tutorial()
{
?>
  <section class="row bus-tutorial mt-5 pt-5">
    <div class="col-12">
      <div class="row mb-4">
        <div class="col-12 col-md-6 m-md-auto text-center mb-4">
          <h2 class="title">Como a Polen pode te ajudar:</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 text-center mb-4 bus-tutorial-item">
          <div class="icon mb-4">
            <?php bus_get_icon_group(); ?>
          </div>
          <h4 class="title">Para seus Colaboradores</h4>
          <p class="description">Aumente a satisfação e retenção dos seus colaboradores, usando os vídeos da Polen para promover cargos ou celebrar datas especiais.</p>
        </div>
        <div class="col-md-4 text-center mb-4 bus-tutorial-item">
          <div class="icon mb-4">
            <?php bus_get_icon_bag(); ?>
          </div>
          <h4 class="title">Para seus Clientes</h4>
          <p class="description">Conquiste novos clientes usando os vídeos da Polen para criar conteúdos de marketing atraentes e exclusivos.</p>
        </div>
        <div class="col-md-4 text-center mb-4 bus-tutorial-item">
          <div class="icon mb-4">
            <?php bus_get_icon_calendar(); ?>
          </div>
          <h4 class="title">Para seus Eventos</h4>
          <p class="description">Torne seus eventos inesquecíveis usando os vídeos da Polen para anunciar vencedores de prêmios ou comemorar grandes conquistas.</p>
        </div>
      </div>
    </div>
  </section>
<?php
}

function bus_get_card($item)
{
  if (isset($item)) {
    $image_data = polen_get_thumbnail($item);
  } else {
    return;
  }
?>
  <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-5 bus-talent-card">
    <figure class="image">
      <img loading="lazy" src="<?php echo $image_data["image"]; ?>" alt="<?php echo $image_data["alt"]; ?>" />
      <figcaption itemprop="name"><?php echo get_the_title($item); ?></figcaption>
    </figure>
  </div>
<?php
}

function bus_grid_scrollable($items, $title)
{
  if (!$items) {
    return;
  }
?>
  <section>
    <div class="container">
      <div class="row">
        <?php foreach ($items as $item) : ?>
          <?php bus_get_card($item); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php
}

function bus_grid($items, $title)
{
  if (empty($items)) {
    return;
  }
?>
  <section class="row bus-grid">
    <div class="col-md-12 ml-3">
      <header class="row mb-5">
        <div class="col-12 text-md-center">
          <h2 class="title"><?php echo $title; ?></h2>
        </div>
      </header>
    </div>
  </section>
  <?php
  foreach ($items as $page) {
    bus_grid_scrollable($page, $title);
  }
}

function bus_get_form()
{
  wp_enqueue_script("polen-business");
  ?>
  <section id="bus-form-wrapper" class="row mt-5 mb-5 bus-form">
    <div class="col-12 mb-4 pb-2 text-center">
      <h2 class="title">Entre em contato com a nossa equipe de vendas</h2>
    </div>
    <div class="col-12 col-md-8 m-md-auto">
      <form id="bus-form" v-on:submit.prevent="handleSubmit" method="POST">
        <input type="hidden" id="url-success" value="<?php echo enterprise_url_success(); ?>" />
        <input type="hidden" name="action" value="submit_form" />
        <input type="hidden" name="form_id" value="1" />
        <input type="hidden" name="terms" value="1" />

        <label class="pol-input-group mb-3" aria-required="true">
          <span class="label">Nome Completo</span>
          <input type="text" class="input" name="name" placeholder="Seu nome" required />
        </label>
        <label class="pol-input-group mb-3" aria-required="true">
          <span class="label">Empresa</span>
          <input type="text" name="company" class="input" placeholder="Empresa S.A." required />
        </label>
        <label class="pol-input-group mb-3" aria-required="true">
          <span class="label">Número de colaboradores</span>
          <select v-bind:class="{'selected': employees_quantity}" name="employees_quantity" v-model="employees_quantity" required>
            <option value="">Selecione uma opção</option>
            <option value="menos-de-20">Menos de 20</option>
            <option value="de-20-a-99">De 20 a 99</option>
            <option value="de-100-a-499">De 100 a 499</option>
            <option value="mais-de-500">Mais de 500</option>
          </select>
        </label>
        <label class="pol-input-group mb-3" aria-required="true">
          <span class="label">Cargo</span>
          <input type="text" name="job" class="input" placeholder="Seu cargo" required />
        </label>
        <label class="pol-input-group mb-3" aria-required="true">
          <span class="label">e-mail de trabalho</span>
          <input type="email" name="email" class="input" placeholder="exemplo@empresa.com" required />
        </label>
        <label class="pol-input-group mb-3" aria-required="true">
          <span class="label">Número de telefone</span>
          <input type="text" name="phone" v-model="phone" v-on:keyup="handleChange" class="input" placeholder="(XX) XXXXX-XXXX" maxlength="15" required />
        </label>
        <label class="pol-input-group mb-3" aria-required="true">
          <span class="label">Mensagem</span>
          <textarea name="message" placeholder="Como você pretende usar os vídeos Polen para sua empresa?" rows="6" required></textarea>
        </label>
        <input type="submit" class="btn btn-primary btn-lg btn-block mt-4" value="Enviar" />
      </form>
    </div>
  </section>
<?php
}

function bus_get_icon_group()
{
?>
  <svg width="65" height="64" viewBox="0 0 65 64" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M33.7426 28.9959H22.9589C22.6352 28.9959 22.3232 28.8279 22.1267 28.5307C21.9302 28.2335 21.8609 27.8588 21.9533 27.5099C22.0342 27.1739 22.7855 24.2278 23.8257 22.8839C24.7388 21.708 28.2409 20.39 30.2058 19.731L30.6219 17.9736L32.6561 18.568L32.0898 20.9715C31.9973 21.3721 31.7199 21.6822 31.3616 21.7985C28.865 22.6125 25.8831 23.866 25.4207 24.4216C25.0856 24.8609 24.7157 25.7655 24.4267 26.6571H33.7657V28.9959H33.7426Z" fill="#231F20" />
    <path d="M44.5256 28.9956H33.7419V26.6439H43.0809C42.7919 25.7652 42.4221 24.8477 42.0869 24.4084C41.6246 23.8398 38.6426 22.5994 36.146 21.7853C35.7877 21.669 35.5219 21.3589 35.4179 20.9583L34.8515 18.5548L36.8857 17.9604L37.3018 19.7178C39.2667 20.3768 42.7688 21.6948 43.6819 22.8707C44.7221 24.2146 45.4734 27.1737 45.5543 27.4967C45.6468 27.8456 45.5774 28.2332 45.3809 28.5175C45.1613 28.8276 44.8608 28.9956 44.5256 28.9956Z" fill="#231F20" />
    <path d="M33.7424 20.9067C31.8699 20.9067 30.09 19.2527 29.0035 16.552C28.0673 16.2161 27.3623 15.4537 27.0733 14.407C26.7613 13.257 27.004 11.9777 27.6744 11.3058C27.8015 11.1766 27.9402 11.0732 28.0905 10.9957C28.3679 7.3776 30.5754 5 33.7539 5C36.9324 5 39.1284 7.3776 39.4174 10.9957C39.5676 11.0732 39.7063 11.1766 39.8335 11.3058C40.5038 11.9777 40.7466 13.257 40.4345 14.407C40.1455 15.4537 39.4405 16.229 38.5043 16.552C37.3947 19.2527 35.6148 20.9067 33.7424 20.9067ZM29.8473 14.3553H29.882C30.2749 14.407 30.5986 14.6913 30.7488 15.0919C31.4654 17.1594 32.679 18.542 33.7539 18.542C34.8404 18.542 36.0424 17.1594 36.759 15.0919C36.8977 14.6784 37.2329 14.3941 37.6259 14.3553C37.6374 14.3553 37.649 14.3553 37.6605 14.3553C38.0304 14.2907 38.2616 14.1228 38.3771 13.8126C38.4696 13.58 38.4696 13.3345 38.4465 13.1795C38.1922 13.1924 37.9495 13.1019 37.7415 12.9339C37.4756 12.7014 37.3254 12.3525 37.3369 11.9777V11.9261C37.3369 11.8744 37.3369 11.8356 37.3369 11.7839C37.3369 9.0445 35.9615 7.33884 33.7539 7.33884C31.5463 7.33884 30.1709 9.0445 30.1709 11.7839C30.1709 11.8356 30.1709 11.8873 30.1709 11.939V11.9777C30.1825 12.3525 30.0322 12.7014 29.7664 12.9339C29.5583 13.1149 29.3156 13.1924 29.0613 13.1795C29.0382 13.3345 29.0382 13.58 29.1307 13.8126C29.2463 14.1228 29.4774 14.3037 29.8473 14.3553Z" fill="#231F20" />
    <path d="M17.3182 58.9998H6.54603C6.2224 58.9998 5.91033 58.8318 5.71385 58.5346C5.51736 58.2374 5.44801 57.8627 5.54048 57.5138C5.62138 57.1778 6.37266 54.2317 7.41289 52.8878C8.32597 51.7119 11.8281 50.3939 13.7929 49.7349L14.209 47.9775L16.2433 48.5719L15.6769 50.9754C15.5844 51.376 15.3071 51.6861 14.9488 51.8024C12.4522 52.6164 9.47022 53.8698 9.0079 54.4255C8.67271 54.8648 8.30286 55.7693 8.01391 56.6609H17.3528V58.9998H17.3182Z" fill="#231F20" />
    <path d="M28.102 59H17.3184V56.6483H26.6573C26.3683 55.7696 25.9985 54.8521 25.6633 54.4128C25.201 53.8442 22.219 52.6038 19.7224 51.7897C19.3641 51.6734 19.0983 51.3633 18.9943 50.9627L18.4279 48.5593L20.4622 47.9648L20.8782 49.7222C22.8431 50.3812 26.3452 51.6992 27.2583 52.8751C28.2985 54.219 29.0498 57.1781 29.1307 57.5011C29.2232 57.85 29.1538 58.2376 28.9573 58.5219C28.7377 58.832 28.4372 59 28.102 59Z" fill="#231F20" />
    <path d="M17.3185 50.8979C15.4461 50.8979 13.6662 49.2439 12.5797 46.5432C11.6435 46.2073 10.9385 45.4449 10.6495 44.3982C10.3375 43.2482 10.5802 41.9689 11.2505 41.297C11.3777 41.1678 11.5164 41.0644 11.6666 40.9869C11.944 37.3688 14.1516 34.9912 17.3301 34.9912C20.5086 34.9912 22.7046 37.3688 22.9935 40.9869C23.1438 41.0644 23.2825 41.1678 23.4096 41.297C24.08 41.9689 24.3227 43.2482 24.0107 44.3982C23.7217 45.4449 23.0167 46.2202 22.0805 46.5432C20.9709 49.2439 19.1909 50.8979 17.3185 50.8979ZM13.4235 44.3595H13.4581C13.8511 44.4112 14.1747 44.6954 14.325 45.096C15.0416 47.1635 16.2436 48.5461 17.3301 48.5461C18.4165 48.5461 19.6186 47.1635 20.3352 45.096C20.4739 44.6825 20.8091 44.3982 21.202 44.3595C21.2136 44.3595 21.2252 44.3595 21.2367 44.3595C21.6066 44.2949 21.8377 44.1269 21.9533 43.8168C22.0458 43.5842 22.0458 43.3386 22.0227 43.1836C21.7684 43.1965 21.5141 43.1061 21.3176 42.9381C21.0518 42.7055 20.9015 42.3566 20.9131 41.9819V41.9431C20.9131 41.8914 20.9131 41.8397 20.9131 41.788C20.9131 39.0486 19.5377 37.343 17.3301 37.343C15.1225 37.343 13.7471 39.0486 13.7471 41.788C13.7471 41.8397 13.7471 41.8914 13.7471 41.9431V41.9819C13.7586 42.3566 13.6084 42.7055 13.3426 42.9381C13.1345 43.119 12.8802 43.2094 12.6375 43.1836C12.6144 43.3386 12.6144 43.5842 12.7069 43.8168C12.8224 44.1269 13.0536 44.2949 13.4235 44.3595Z" fill="#231F20" />
    <path d="M48.6403 59.0003H37.8566C37.533 59.0003 37.2209 58.8323 37.0244 58.5351C36.8279 58.2379 36.7586 57.8632 36.851 57.5143C36.9319 57.1783 37.6832 54.2322 38.7234 52.8883C39.6365 51.7124 43.1386 50.3944 45.1035 49.7354L45.5196 47.978L47.5538 48.5724L46.9875 50.9759C46.895 51.3764 46.6176 51.6866 46.2593 51.8029C43.7627 52.6169 40.7808 53.8703 40.3184 54.426C39.9833 54.8653 39.6134 55.7698 39.3244 56.6614H48.6634V59.0003H48.6403Z" fill="#231F20" />
    <path d="M59.4126 59H48.6289V56.6483H57.9678C57.6789 55.7696 57.309 54.8521 56.9738 54.4128C56.5115 53.8442 53.5295 52.6038 51.033 51.7897C50.6747 51.6734 50.4088 51.3633 50.3048 50.9627L49.7385 48.5593L51.7727 47.9648L52.1888 49.7222C54.1537 50.3812 57.6558 51.6992 58.5688 52.8751C59.6091 54.219 60.3604 57.1781 60.4413 57.5011C60.5337 57.85 60.4644 58.2376 60.2679 58.5219C60.0483 58.832 59.7478 59 59.4126 59Z" fill="#231F20" />
    <path d="M48.6401 50.8981C46.7677 50.8981 44.9877 49.2441 43.9013 46.5435C42.965 46.2075 42.26 45.4451 41.9711 44.3856C41.659 43.2355 41.9017 41.9563 42.5721 41.2843C42.6992 41.1551 42.8379 41.0517 42.9882 40.9742C43.2656 37.3561 45.4731 34.9785 48.6516 34.9785C51.8301 34.9785 54.0261 37.3561 54.3151 40.9742C54.4653 41.0517 54.604 41.1551 54.7312 41.2843C55.4015 41.9563 55.6443 43.2355 55.3322 44.3856C55.0432 45.4322 54.3382 46.2075 53.402 46.5306C52.2924 49.2441 50.5009 50.8981 48.6401 50.8981ZM44.7334 44.3597H44.7681C45.1611 44.4114 45.4847 44.6957 45.635 45.0963C46.3516 47.1637 47.5652 48.5464 48.6401 48.5464C49.7265 48.5464 50.9286 47.1637 51.6452 45.0963C51.7839 44.6828 52.119 44.3985 52.512 44.3597C52.5236 44.3597 52.5351 44.3597 52.5467 44.3597C52.9165 44.2951 53.1477 44.1271 53.2633 43.817C53.3558 43.5844 53.3558 43.3389 53.3326 43.1838C53.0784 43.1967 52.8241 43.1063 52.6276 42.9383C52.3618 42.7057 52.2115 42.3568 52.2231 41.9821V41.9433C52.2231 41.8917 52.2231 41.84 52.2231 41.7883C52.2231 39.0489 50.8477 37.3432 48.6401 37.3432C46.4325 37.3432 45.0571 39.0489 45.0571 41.7883C45.0571 41.84 45.0571 41.8917 45.0571 41.9433V41.995C45.0686 42.3698 44.9184 42.7187 44.6525 42.9512C44.4445 43.1321 44.1902 43.2226 43.9475 43.1968C43.9244 43.3518 43.9244 43.5973 44.0168 43.8299C44.1324 44.1271 44.3636 44.2951 44.7334 44.3597Z" fill="#231F20" />
    <path d="M17.3184 31.6573C16.7405 31.6573 16.2666 31.1275 16.2666 30.4814V15.6085C16.2666 14.9624 16.7405 14.4326 17.3184 14.4326H23.8025C24.3804 14.4326 24.8542 14.9624 24.8542 15.6085C24.8542 16.2546 24.3804 16.7844 23.8025 16.7844H18.3702V30.4944C18.3702 31.1275 17.9078 31.6573 17.3184 31.6573Z" fill="#FD6C36" />
    <path d="M50.6863 31.6575C50.1084 31.6575 49.6345 31.1278 49.6345 30.4817V16.7717H44.2022C43.6243 16.7717 43.1504 16.2419 43.1504 15.5958C43.1504 14.9497 43.6243 14.4199 44.2022 14.4199H50.6863C51.2642 14.4199 51.738 14.9497 51.738 15.5958V30.4687C51.738 31.1278 51.2642 31.6575 50.6863 31.6575Z" fill="#FD6C36" />
  </svg>
<?php
}

function bus_get_icon_bag()
{
?>
  <svg width="65" height="64" viewBox="0 0 65 64" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M18.3294 56.0179C18.2577 56.0179 18.1739 56.0064 18.1022 55.9948C17.8151 55.937 17.5639 55.7636 17.4084 55.5209L12.7073 48.2971C12.5518 48.066 12.5039 47.777 12.5637 47.4996C12.6235 47.2222 12.803 46.9911 13.0422 46.8408L21.3797 41.8015C23.6166 40.4492 26.1885 39.7442 28.8201 39.7442H31.679C32.5762 39.7442 33.4614 39.9638 34.2389 40.3683C35.0762 40.8075 36.715 41.5357 38.294 41.5357H45.0047C45.8181 41.5357 46.5478 41.8593 47.0622 42.391L52.5527 38.7617C53.5336 38.1145 54.8255 38.2069 55.6988 38.9929C56.2131 39.4552 56.5122 40.114 56.5002 40.7959C56.4882 41.4779 56.1892 42.1251 55.6629 42.5874L48.0191 49.1871C47.9354 49.268 47.8277 49.3258 47.7201 49.372L44.3947 50.7243C40.8419 52.1691 37.0859 52.9088 33.2341 52.9088H25.6382C24.2147 52.9088 22.8391 53.3133 21.6429 54.0762L18.9275 55.8214C18.7481 55.9601 18.5448 56.0179 18.3294 56.0179ZM15.1236 48.0775L18.6524 53.4983L20.4347 52.3425C21.9778 51.3485 23.7721 50.8168 25.6263 50.8168H33.2221C36.7868 50.8168 40.2558 50.1349 43.5334 48.8057L46.6914 47.5227L54.1915 41.0387C54.2872 40.9578 54.3111 40.8537 54.3111 40.7959C54.3111 40.7381 54.2992 40.6341 54.2035 40.5532C54.0839 40.4492 53.9044 40.4376 53.7729 40.5185L47.8397 44.4483C47.7799 45.7312 46.799 46.8061 45.4712 47.0257L34.7533 48.7941C34.1671 48.8866 33.5929 48.5052 33.4973 47.9388C33.4016 47.3609 33.7963 46.8177 34.3824 46.7252L45.1004 44.9568C45.4234 44.8991 45.6626 44.6332 45.6626 44.3212C45.6626 43.9629 45.3636 43.6739 44.9928 43.6739H38.2821C36.3322 43.6739 34.4423 42.8995 33.1862 42.2523C32.7317 42.0095 32.2054 41.8824 31.6671 41.8824H28.8082C26.5832 41.8824 24.4061 42.4834 22.5161 43.6277L15.1236 48.0775Z" fill="#231F20" />
    <path d="M15.5776 58.9997C15.2068 58.9997 14.8479 58.8148 14.6446 58.4796L8.65162 48.7246C8.49611 48.4703 8.46023 48.1698 8.54396 47.8924C8.62769 47.615 8.83104 47.3838 9.09421 47.2452L12.3479 45.6386C12.8503 45.3959 13.4603 45.5577 13.7594 46.02L20.0036 55.6248C20.171 55.879 20.2069 56.1795 20.1351 56.4569C20.0514 56.7459 19.86 56.977 19.5849 57.1157L16.0681 58.8726C15.9126 58.965 15.7451 58.9997 15.5776 58.9997ZM11.1158 48.6206L15.9843 56.561L17.5394 55.7866L12.4555 47.9617L11.1158 48.6206Z" fill="#231F20" />
    <path d="M19.6798 45.1304C19.4166 45.1304 19.1415 45.038 18.9382 44.8415C18.8185 44.7375 15.9716 42.1022 15.9955 36.9936C16.0194 30.4633 20.553 23.1239 29.4647 15.1719C29.6681 14.987 29.9312 14.8945 30.2064 14.8945H33.5677C34.1658 14.8945 34.6562 15.3684 34.6562 15.9463C34.6562 16.5242 34.1658 16.9981 33.5677 16.9981H30.637C20.3736 26.2446 18.1965 32.9714 18.1846 37.0051C18.1726 41.2123 20.4214 43.2927 20.4454 43.3043C20.888 43.6973 20.9119 44.3676 20.4932 44.7953C20.254 45.0264 19.9669 45.1304 19.6798 45.1304Z" fill="#231F20" />
    <path d="M47.42 45.1304C47.133 45.1304 46.8339 45.0149 46.6305 44.7953C46.2238 44.3676 46.2478 43.7088 46.6784 43.3158C46.7741 43.2234 48.9871 41.1198 48.9392 36.9242C48.9033 32.902 46.6904 26.1983 36.4868 16.9981H33.5561C32.958 16.9981 32.4675 16.5242 32.4675 15.9463C32.4675 15.3684 32.958 14.8945 33.5561 14.8945H36.9174C37.1925 14.8945 37.4557 14.9986 37.659 15.1719C46.5707 23.1239 51.1043 30.4633 51.1283 36.9936C51.1402 42.1022 48.3052 44.7259 48.1856 44.8415C47.9583 45.038 47.6832 45.1304 47.42 45.1304Z" fill="#231F20" />
    <path d="M30.1954 17.0095C29.8126 17.0095 29.4298 16.813 29.2384 16.4547C27.5996 13.5305 24.9441 8.35252 25.5302 6.61881C26.1642 4.76952 28.3413 5.25496 29.9442 5.6017C30.7815 5.78663 31.7146 5.99467 32.3366 5.91377C32.7792 5.85598 33.3773 5.71728 34.0113 5.56702C36.32 5.03535 39.4779 4.29564 40.6861 6.44544C41.9062 8.6068 39.1191 14.1547 37.875 16.4432C37.5879 16.9517 36.93 17.1482 36.4037 16.8824C35.8774 16.605 35.674 15.9693 35.9491 15.4607C37.8391 12.0049 39.1908 8.19071 38.7722 7.45099C38.3535 6.71128 35.9491 7.27762 34.5137 7.61281C33.8199 7.77462 33.1739 7.92487 32.6117 7.99422C31.6069 8.12136 30.5184 7.87864 29.4538 7.64748C28.8915 7.52034 27.9824 7.32385 27.5997 7.35853C27.6116 8.43343 29.4059 12.3169 31.1524 15.4492C31.4394 15.9577 31.2361 16.5934 30.7098 16.8708C30.5423 16.9633 30.3629 17.0095 30.1954 17.0095Z" fill="#231F20" />
    <path d="M38.6527 17.0093H28.5329C27.9348 17.0093 27.4443 16.5355 27.4443 15.9575C27.4443 15.3796 27.9348 14.9058 28.5329 14.9058H38.6527C39.2508 14.9058 39.7413 15.3796 39.7413 15.9575C39.7413 16.5355 39.2508 17.0093 38.6527 17.0093Z" fill="#FD6C36" />
    <path d="M34.8487 23.5862C34.753 23.5862 34.6453 23.5746 34.5377 23.5399C33.9635 23.3781 33.6285 22.7886 33.796 22.2339C34.5735 19.6448 32.6357 16.5357 32.6118 16.501C32.2888 16.0041 32.4443 15.3568 32.9587 15.0563C33.473 14.7442 34.1429 14.8945 34.4539 15.3915C34.5616 15.5533 36.942 19.2981 35.8774 22.8233C35.7578 23.2856 35.3272 23.5862 34.8487 23.5862Z" fill="#FD6C36" />
    <path d="M38.1858 21.5058C37.9227 21.5058 37.6475 21.4134 37.4322 21.2169L33.1378 17.2871C32.7072 16.8826 32.6833 16.2238 33.102 15.7962C33.5206 15.3801 34.2025 15.3569 34.6451 15.7615L38.9394 19.6912C39.3701 20.0958 39.394 20.7546 38.9753 21.1822C38.76 21.3903 38.4729 21.5058 38.1858 21.5058Z" fill="#FD6C36" />
    <path d="M30.8889 34.647C30.8889 34.4159 30.9009 34.1963 30.9368 33.9882C30.9487 33.9189 30.9607 33.8495 30.9726 33.7802C31.0205 33.5259 31.2478 33.341 31.5109 33.341H31.8578C32.1569 33.341 32.4081 33.5837 32.4081 33.8727C32.4081 34.4737 32.5038 34.9244 32.6832 35.2365C32.8746 35.5486 33.1617 35.7104 33.5445 35.7104C33.8794 35.7104 34.1187 35.6295 34.2861 35.4677C34.4536 35.3059 34.5254 35.0747 34.5254 34.7626C34.5254 34.647 34.5134 34.5199 34.4895 34.3812C34.4656 34.2541 34.4177 34.1154 34.3579 33.9651C34.2981 33.8149 34.2144 33.6646 34.1187 33.4912C34.011 33.3179 33.8794 33.1445 33.7239 32.948L31.9655 30.7635C31.6545 30.3706 31.4033 29.9776 31.2238 29.5846C31.0444 29.1916 30.9607 28.7409 30.9607 28.2554C30.9607 27.9318 31.0085 27.6313 31.1042 27.3539C31.1999 27.0765 31.3435 26.8338 31.5229 26.6142C31.7023 26.4061 31.9296 26.2212 32.1808 26.0825C32.3243 26.0132 32.4679 25.9438 32.6354 25.8976C32.8985 25.8167 33.078 25.5855 33.078 25.3197V24.9267C33.078 24.5915 33.3531 24.3257 33.7 24.3257C34.0469 24.3257 34.322 24.5915 34.322 24.9267V25.3428C34.322 25.5971 34.5014 25.8282 34.7526 25.9091C34.9321 25.9669 35.0995 26.0363 35.2431 26.1287C35.4943 26.279 35.6857 26.4639 35.8412 26.672C35.9967 26.88 36.1043 27.1112 36.1642 27.3539C36.224 27.5966 36.2599 27.8393 36.2599 28.0821C36.2599 28.3132 36.2479 28.5328 36.212 28.7409C36.2 28.8102 36.1881 28.8911 36.1761 28.972C36.1283 29.2263 35.901 29.4112 35.6378 29.4112H35.3029C35.0038 29.4112 34.7526 29.1685 34.7526 28.8796C34.7526 28.2901 34.657 27.8509 34.4656 27.5388C34.2742 27.2268 33.999 27.0649 33.6402 27.0649C33.3172 27.0649 33.066 27.1574 32.8985 27.3308C32.7311 27.5042 32.6473 27.7353 32.6473 28.0358C32.6473 28.2901 32.6952 28.5097 32.8028 28.7178C32.9105 28.9258 33.0421 29.1338 33.2095 29.3419L35.1833 31.8038C35.3627 32.0349 35.5302 32.2545 35.6737 32.4741C35.8173 32.6937 35.9369 32.9018 36.0326 33.1214C36.1283 33.341 36.2 33.5606 36.2479 33.7802C36.2957 34.0114 36.3197 34.2425 36.3197 34.4968C36.3197 34.8204 36.2718 35.1094 36.1881 35.3752C36.0924 35.641 35.9608 35.8838 35.7933 36.0802C35.6259 36.2883 35.4105 36.4617 35.1713 36.6004C35.0397 36.6697 34.8962 36.7391 34.7526 36.7853C34.5134 36.8662 34.3459 37.0974 34.3459 37.3516V37.918C34.3459 38.2532 34.0708 38.519 33.7239 38.519C33.377 38.519 33.1019 38.2532 33.1019 37.918V37.4557C33.1019 37.1783 32.8985 36.9355 32.6114 36.8662C32.3961 36.82 32.2167 36.7506 32.0492 36.6697C31.7741 36.5195 31.5468 36.3461 31.3794 36.138C31.2119 35.93 31.0923 35.6873 31.0205 35.433C30.9248 35.1787 30.8889 34.9129 30.8889 34.647Z" fill="#231F20" />
  </svg>
<?php
}

function bus_get_icon_calendar()
{
?>
  <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M47.8601 12H14.7325C12.1188 12 10 14.2386 10 17V52C10 54.7614 12.1188 57 14.7325 57H47.8601C50.4738 57 52.5926 54.7614 52.5926 52V17C52.5926 14.2386 50.4738 12 47.8601 12Z" stroke="black" stroke-width="1.85185" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M42.4851 7V17" stroke="black" stroke-width="1.62824" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M22.4946 7V17" stroke="black" stroke-width="1.62824" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M10 27H54.9802" stroke="black" stroke-width="1.62824" stroke-linecap="round" stroke-linejoin="round" />
    <rect x="41.1108" y="31.8394" width="8.13346" height="8.13704" fill="#FD6C36" />
  </svg>
<?php
}
