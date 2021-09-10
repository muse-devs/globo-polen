<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php wp_title(); ?></title>

    <?php $uri = get_template_directory_uri(); ?>

    <meta property="og:title" content="Masterclass Ronnie Von">
    <meta property="og:description" content="Beabá do Vinho com Ronnie Von">
    <meta property="og:url" content="https://polen.me/masterclass/ronnie-von/beaba-do-vinho/inscricoes/">
    <meta property="og:image" content="https://polen.me/masterclass/wp-content/uploads/2021/09/Screen-Shot-2021-09-09-at-21.11.14.png">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Masterclass Ronnie Von">

    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="date=no" />
    <meta name="format-detection" content="address=no" />
    <meta name="format-detection" content="email=no" />
    <style>
        .payment_box.payment_method_wc_pagarme_pix_payment_geteway {
            display: none !important;
        }

        .payment_box.payment_method_pagarme-banking-ticket {
            display: none !important;
        }

        .woocommerce-message {
            display: none !important;
        }
    </style>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T65KTZ3');</script>
    <!-- End Google Tag Manager -->

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5d568d2bbd.js" crossorigin="anonymous"></script>

    <?php wp_head(); ?>
</head>

<body>
    <div id="page" class="container site">
        <header id="masthead" class="row py-4 header-home">
            <?php global $wp; ?>
            <?php if (add_query_arg( array(), $wp->request ) === "ronnie-von/beaba-do-vinho/inscricoes") : ?>
                <div class="col-8 col-sm-6 d-flex align-items-center">
                    <a href="<?php echo site_url('ronnie-von/beaba-do-vinho/inscricoes'); ?>">
                        <img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/logo-masterclass.svg"; ?>" alt="Logo Masterclass" />
                    </a>
                </div>
            <?php else : ?>
                <?php ; ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <a href="<?php echo site_url('ronnie-von/beaba-do-vinho/inscricoes'); ?>"><img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/logo-masterclass.svg"; ?>" alt="Logo Masterclass" /></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </header><!-- #masthead -->