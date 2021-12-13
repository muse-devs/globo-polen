<?php
defined( 'ABSPATH' ) || exit;

use chillerlan\QRCode\QRCode;

if( $order ){
    $paid = get_post_meta($order->get_id(), '_wc_pagarme_pix_payment_paid', 'no') === 'yes' ? true : false;
}

?>

<style>
    body{
        font-family: 'Poppins', sans-serif;
        color: black;
    }

    .container{
        padding: 0 20px;
        max-width: 120rem;
    }

    .course-card{
        width: 100%;
        margin-top: 20px;
        background-color: #F3F3F3;
        border-radius: 8px;
    }

    .course-card__header{
        display: flex;
        align-items: center;
        padding: 20px 15px;
        column-gap: 20px;
        border-bottom: 2px dashed rgba(0, 0, 0, 0.05);
    }

    .course-card__header p{
        font-style: normal;
        font-weight: bold;
        font-size: 24px;
        line-height: 1;
    }

    .course-card__image{
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
    }

    .course-card__image img{
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .course-card__price{
        padding: 20px 15px;
    }

    .course-card__price p{
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 180%;
        color: #000;
    }

    .course-card__value p{
        font-style: normal;
        font-weight: bold;
        font-size: 24px;
        line-height: 180%;
    }

    .woocommerce-billing-fields h3{
        display: none;
    }

    .woocommerce-billing-fields__field-wrapper{
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        row-gap: 20px;
    }

    .form-row{
        display: flex;
        flex-direction: column;
        padding: 16px !important;
        border: 1px solid #E5E5E5;
        width: 100% !important;
        border-radius: 8px;
    }

    .form-row input{
        padding: 0 !important;
        font-style: normal;
        font-weight: normal;
        font-size: 14px !important;
        line-height: 150%;
        border: none !important;
    }

    .form-row input::placeholder{
        color: #000 !important;
        font-style: normal !important;
        font-weight: normal !important;
        font-size: 14px !important;
        line-height: 150% !important;
    }

    .form-row label{
        font-family: 'Poppins', sans-serif !important;
        font-style: normal;
        font-weight: normal;
        font-size: 10px;
        line-height: 100%;
        color: #999;
    }

    .woocommerce-input-wrapper input{
        border: none !important;
    }

    .woocommerce-input-wrapper input::placeholder{
        color: #000;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        line-height: 150%;
    }

    .footer-container__menu-column{
        display: none;
    }

    #order_review_heading{
        margin-top: 20px;

    }

    #order_review{
        margin-top: 20px;
    }

    #payment{
        background-color: transparent !important;
    }

    #payment >ul{
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        row-gap: 20px;
    }

    #payment ul.payment_methods{
        border-bottom: none !important;
    }

    .wc_payment_method{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .wc_payment_method >input{
        all: unset;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1px solid rgba(153, 153, 153, 0.5);
        background-color: rgba(158, 158, 158, 0.25);
    }

    .wc_payment_method >input:checked{
        background-color: #FD6C36;
        border-color: #FD6C36;
    }

    .wc_payment_method >label{
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 160%;
    }

    .wc_payment_method >input:checked + label{
        font-weight: 700;
    }

    .wc_payment_method >input:checked::before{
        content: '';
        display: block;
        width: 12px;
        height: 12px;
        background-color: #FD6C36;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .payment_box{
        background-color: transparent !important;
        padding: 0 !important;
    }

    .payment_box::before{
        display: none !important;
    }

    .payment_box fieldset{
        padding: 0;
        margin: 0;
        border: none;
    }

    .place-order{
        padding: 0 !important;
        border: none;
    }

    .woocommerce-privacy-policy-text{
        display: none;
    }

    .order-info{
        margin-top: 20px;
        background-color: #F3F3F3;
        padding: 20px;
        border-radius: 8px;
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 160%;
        color: #000;
    }

    .order-terms{
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        row-gap: 20px;
    }

    .order-terms__checkbox{
        display: flex;
        align-items: center;
        column-gap: 20px;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        line-height: 156%;
        color: #000;
    }

    .order-terms__checkbox input{
        all: unset;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border: 1px solid #FD6C36;
        border-radius: 4px;
    }

    .order-terms__checkbox input:checked{
        background-color: #FD6C36;
    }

    .order-terms__checkbox input:checked::before{
        content: '\f00c';
        font-family: FontAwesome;
        color: #fff;
        font-size: 14px;
    }

    .order-terms__checkbox label{
        max-width: 75%;
    }

    .order-terms__checkbox a{
        color: #FD6C36;
        font-weight: 700;
    }

    #place_order, .default-button{
        padding: 20px;
        border-radius: 94px;
        background: linear-gradient(100.13deg, #FD6C36 10.5%, #D7198B 94.06%);
    }

    #place_order:hover, .default-button:hover{
        filter: brightness(.9);
    }

    .payment__end p{
        font-style: normal;
        font-weight: bold;
        font-size: 16px;
        line-height: 160%;
        color: #3ABE39;
        margin-top: 20px;
    }

    .payment__text{
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 160%;
        margin-top: 20px;
    }

    .payment__instructions >.payment__text p{
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        max-width: 250px;
        margin: 0 auto;
    }

    .payment__instructions{
        width: 100%;
        margin-top: 20px;
        background-color: #F3F3F3;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .payment__header{
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .payment__header img{
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    .payment__header p{
        font-style: normal;
        font-weight: bold;
        font-size: 14px;
        line-height: 160%;
    }

    .payment__qr{
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }

    .payment__qr img{
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .default-button{
        font-style: normal;
        font-weight: 600;
        font-size: 14px;
        line-height: 150%;
        color: #fff;
        width: max-content;
        padding: 10px 40px;
        border: none;
        margin: 0 auto;
    }

    .payment__list p{
        max-width: 200px;
    }

    .payment__list ul{
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .payment__list ul li{
        display: flex;
        gap: 14px;
        width: max-content;
        margin: 0 auto;
    }

    .payment__list .payment__text{
        margin: 0;
    }

    .payment__bullet{
        width: 32px;
        height: 32px;
        background-color: #E2E2E2;
        color: #1B1A1A;
        font-style: normal;
        font-weight: bold;
        font-size: 12px;
        line-height: 21px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .woocommerce-order-details{
        display: none !important;
    }

    .woocommerce-customer-details{
        display: none !important;
    }

    .order_details{
        display: none !important;
    }

</style>

<?php get_header(); ?>

<section class="payment">
    <div id="successPixPaymentBox" style="display: <?php echo $paid ? 'block' : 'none'; ?>;">
        <?php
        $name_status = 'Pagamento Aprovado';
        $descriptions = 'Seu pagamento foi autorizado pela emissora do cartão.';
        $name_class = '';

        ?>

        <div class="row mb-3">
            <div class="col-12">
                <?php foreach ($order->get_items() as $item) : ?>
                    <div class="thank-you-box">
                        <img class="img-responsive"
                             style="height: 120px;width: 130px;"
                             src="<?php echo get_the_post_thumbnail_url($item['product_id']); ?>"
                             alt="<?php echo $item['name']; ?>">
                        <h3>Obrigada por pedir seu curso “<?php echo $item['name']; ?>"</h3>
                        <p>Você vai receber os e-mails com o link do acesso ao curso.</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-12 m-md-auto d-flex align-items-center justify-content-center">
            <ul class="order-flow mt-4">
                <li class="item itempayment-approved complete">
                    <span class="background status">
                        <?php Icon_Class::polen_icon_check_o(); ?>
                        <?php Icon_Class::polen_icon_exclamation_o(); ?>
                    </span>
                    <span class="text">
                        <h4 class="title">Pedido Enviado</h4>
                        <p class="description">Seu número do pedido é <?php echo $order->get_id(); ?> .</p>
                    </span>
                </li>
                <li class="item itempayment-approved complete">
                        <span class="background status">
                            <?php Icon_Class::polen_icon_check_o(); ?>
                            <?php Icon_Class::polen_icon_exclamation_o(); ?>
                        </span>

                    <span class="text">
                        <h4 class="title"><?php echo $name_status; ?></h4>
                        <p class="description">
                           <?php echo $descriptions; ?>
                        </p>
                    </span>
                </li>
                <li class="item itempayment-approved">
                    <span class="background status"></span>
                    <span class="text">
                        <h4 class="title">Acesso ao curso</h4>
                        <p class="description">Receba acesso ao curso e instruções dos próximos passos via e-mail.</p>
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="container" id="watingPixPaymentBox" style="display: <?php echo $paid ? 'none' : 'block'; ?>;">

        <div class="payment__end">
            <p>Para finalizar a sua compra é só realizar o pagamento com Pix!</p>
        </div>
        <div class="payment__text">
            <p>Obrigado pela compra. Você receberá todos os dados da sua compra no email.</p>
        </div>
        <div class="course-card">
            <?php foreach ($order->get_items() as $item) : ?>
                <div class="course-card__header">
                    <div class="course-card__image" style="margin: auto;">
                        <img src="<?php echo get_the_post_thumbnail_url($item['product_id']); ?>">
                    </div>
                    <p><?php echo $item['name']; ?></p>
                </div>
                <div class="course-card__price">
                    <p>Você vai pagar</p>
                    <div class="course-card__value">
                        <p><?php echo wc_price($order->get_total()); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="payment__instructions">
            <div class="payment__header">
                <img src="https://logospng.org/download/pix/logo-pix-icone-512.png" alt="">
                <p>Pagamento via PIX</p>
            </div>
            <div class="payment__qr">
                <img src="<?php echo (new QRCode)->render( esc_html($qr_code) ); ?>" />
            </div>
            <button class="default-button button copy-qr-code" style="margin: auto;">Copiar código PIX</button>
            <p class="text-success qrcode-copyed" style="text-align: center; display: none; margin-top: 15px;">Código copiado com sucesso!<br>Vá até o aplicativo do seu banco e cole o código.</p>

            <div class="payment__text">
                <p>
                    <b>O código é válido até <?php echo date('d/m/Y H:i:s', strtotime( '+3 days', current_time('timestamp'))); ?>.</b>
                    Se o pagamento não for confirmado, não se preocupe. O pedido será cancelado automaticamente.
                </p>
            </div>
            <div class="payment__list">
                <ul>
                    <li>
                        <div class="payment__bullet">
                            <p>1</p>
                        </div>
                        <div class="payment__text">
                            <p> Abra o app ou banco de sua preferência. Escolha a opção pagar com código Pix “copia e cola”, ou código QR.</p>
                        </div>
                    </li>
                    <li>
                        <div class="payment__bullet">
                            <p>2</p>
                        </div>
                        <div class="payment__text">
                            <p> Copie e cole o código, ou escaneie o código QR com a câmera do seu celular. Confira todas as informações e autorize o pagamento.</p>
                        </div>
                    </li>
                    <li>
                        <div class="payment__bullet">
                            <p>3</p>
                        </div>
                        <div class="payment__text">
                            <p>Você vai receber a confirmação do pagamento no seu e-mail e através dos nossos canais. E pronto!</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <input type="hidden" value="<?php echo esc_html($qr_code); ?>" id="pixQrCodeInput"></div>
    <input type="hidden" name="wc_pagarme_pix_order_key" value="<?php echo esc_html( sanitize_text_field( $order_key ) ); ?>"/>
</section>