<?php

if (!defined('ABSPATH')) {
	exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Obrigado por ajudar o Criança Esperança</title>
</head>

<body style="
      margin: 0;
      font-family: Roboto, 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 16px;
      line-height: 34px;
      color: white;
      background-color: white;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    ">
	<table style="margin: 3% auto; padding: 0; min-width: 300px; max-width: 594px; width: 97%;" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/img/email/logo-polen-criesp.png" alt="Logos Polen e Criança Esperança" style="display: block; margin: auto; max-width: 80%" />
				</td>
			</tr>
			<tr>
				<td style="height: 20px"></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<table style="
                width: 100%;
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
                background-color: #e6e9ec;
              ">
						<tr>
							<td>
								<table style="width: 80%; margin: auto">
									<tr>
										<td style="height: 20px"></td>
									</tr>
									<tr>
										<td>
											<h1 style="
                            font-size: 40px;
                            font-weight: 700;
                            color: #d7198b;
                            text-align: center;
                          ">
												Obrigado por ajudar o Criança Experança
											</h1>
										</td>
									</tr>
									<tr>
										<td>
											<p style="
                            margin: 0;
                            color: black;
                            font-weight: 200;
                            line-height: 1.2;
                            text-align: center;
                          ">
												Na Polen 100% do cachê dos vídeos serão revertidos em
												doações para o Criança Esperança.
												<strong style="font-weight: 400">Em ate 15 dias o seu idolo vai enviar o seu
													video-agradecimento.</strong>
											</p>
										</td>
									</tr>
									<tr>
										<td style="height: 40px"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<a style="text-decoration: none" href="https://redeglobo.globo.com/criancaesperanca/noticia/confira-as-instituicoes-apoiadas-nesta-edicao-do-crianca-esperanca.ghtml">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/email/criesp-art.jpg" alt="Arte Criança Esperança" style="display: block; max-width: 100%" />
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<table style="
                width: 100%;
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
                background-color: #262626;
              ">
						<tr>
							<td>
								<table style="width: 80%; margin: auto">
									<tr>
										<td style="height: 40px"></td>
									</tr>
									<tr>
										<td>
											<p style="margin-bottom: 40px">
												O talento tem até 15 dias para responder
												a sua solicitação.
											</p>
											<p>
												Caso você não tenha feito o pedido com a conta logada,
												você pode acessar o
												<?php $order_number = $order->get_order_number(); ?>
												<a href="<?= site_url( "my-account/view-order/{$order_number}/"); ?>" style="color: #d7198b">acompanhamento do pedido</a>
												e colocar seu email e o numero do pedido que é <strong><?php echo $order_number; ?></strong>. <br />
												Mas se você não criou a sua conta ou tem dúvidas sobre o sua
												doação, fique tranquilo! Você pode enviar um e-email
												para <a href="mailto:atendimento@polen.me">atendimento@polen.me</a>.
											</p>
										</td>
									</tr>
									<tr>
										<td style="height: 40px"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</body>

</html>
