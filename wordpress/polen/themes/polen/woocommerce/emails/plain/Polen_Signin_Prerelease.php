<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$email_heading='';
// echo "= " . $email_heading . " =\n\n";
// $e = \WC_Emails::get_instance();
// $e->email_header('');

?>
<p>
Olá,<br />
Obrigada por entrar em nossa lista de espera para experimentar um novo jeito de se relacionar com seus artistas favoritos. Na Polen, você vai poder encomendar vídeos de artistas com mensagens gravadas por eles do jeito que você quiser.
Para você ir preparando suas primeiras encomendas, aqui vão algumas boas sugestões do que pedir aos talentos na Polen:
<ul>
<li>Peça um vídeo com um alô e um abraço, simples e direto.</li>
<li>Que tal dar um vídeo de aniversário? O artista dá parabéns a quem você quiser.</li>
<li>Ou os parabéns podem ser por algo diferente: uma conquista, o nascimento de um filho, uma promoção no trabalho.</li>
<li>Se você quiser contar algo importante a alguém de uma forma especial, que tal um vídeo da Polen?</li>
<li>Ou simplesmente peça um conselho ao seu artista favorito, respostas às suas perguntas, as curiosidades que quiser.</li>
</ul>
Gostou? Muito, né? E claro, quando o site estiver no ar eu volto aqui para te contar em primeira mão. Até já!<br />
Polen
<?
// $e->email_footer();
// echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

// do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

// echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
