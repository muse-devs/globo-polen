<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php wp_title(); ?></title>

    <?php $uri = get_template_directory_uri(); ?>

    <!-- <link rel="icon" type="image/png" href="<?php echo $uri; ?>/assets/ico/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo $uri; ?>/assets/ico/favicon-16x16.png" sizes="16x16" />

    <link rel="apple-touch-icon" href="<?php echo $uri; ?>/assets/ico/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $uri; ?>/assets/ico/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $uri; ?>/assets/ico/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="167x167" href="<?php echo $uri; ?>/assets/ico/favicon-32x32.png">

    <meta name="application-name" content="<?php echo $uri; ?>/assets/ico/favicon-32x32.png"/>
    <meta name="msapplication-TileColor" content=assets/ico/favicon-32x32.png"/>
    <meta name="msapplication-square70x70logo" content="<?php echo $uri; ?>/assets/ico/favicon-32x32.png"/>
    <meta name="msapplication-square150x150logo" content="<?php echo $uri; ?>/assets/ico/favicon-32x32.png"/>
    <meta name="msapplication-wide310x150logo" content="<?php echo $uri; ?>/assets/ico/favicon-32x32.png"/>
    <meta name="msapplication-square310x310logo" content="<?php echo $uri; ?>/assets/ico/favicon-32x32.png"/>

    <link rel="icon" sizes="192x192" href="<?php echo $uri; ?>/assets/ico/favicon-32x32.png">
    <link rel="icon" sizes="128x128" href="<?php echo $uri; ?>/assets/ico/favicon-32x32.png"> -->

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

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5d568d2bbd.js" crossorigin="anonymous"></script>

    <?php wp_head(); ?>
</head>

<body>
    <div id="page" class="container site">
        <header id="masthead" class="row py-4 header-home">
            <?php if (is_front_page()) : ?>
                <div class="col-8 col-sm-6 d-flex align-items-center">
                    <img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/logo-masterclass.svg"; ?>" alt="Logo Masterclass" />
                </div>
            <?php else : ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/logo-masterclass-black.svg"; ?>" alt="Logo Masterclass" />
                        </div>
                        <div class="col-6 text-right">
                            <img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/todo-vino.png"; ?>" alt="Logo TodoVino" />
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </header><!-- #masthead -->