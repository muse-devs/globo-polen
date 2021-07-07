<?php

use Polen\Tributes\Tributes_Invites_Model;

global $tribute;

$invites = Tributes_Invites_Model::get_all_by_tribute_id($tribute->ID);
$hash = $tribute->hash;

function getIcon($done)
{
	if ($done) {
		Icon_Class::polen_icon_check_o_alt();
	} else {
		Icon_Class::polen_icon_clock();
	}
}

//video_sent / email_opened / email_clicked
?>

<?php get_header('tributes'); ?>

<main id="tribute-details">
	<div class="container py-3 tribute-container tribute-app">
		<div class="row mb-4 py-3 border-bottom">
			<div class="col-md-3">
				<p>Pra quem é o tributo?</p>
				<p><strong><?php echo $tribute->name_honored; ?></strong></p>
			</div>
			<div class="col-md-3">
				<p>Data de Vencimento</p>
				<p><strong><?php echo date('d/m/Y', strtotime($tribute->deadline)); ?></strong></p>
			</div>
			<div class="col-md-3">
				<p>% de sucesso</p>
				<p><strong><?php echo number_format($total_success); ?>%</strong></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mt-4">
				<h2 class="subtitle subtitle-tribute">Pessoas</h2>
			</div>
			<div class="col-md-12">
				<div class="card-invite invites-list">
					<?php foreach ($invites as $invite) : ?>
						<div class="row mb-4">
							<div class="col-md-5">
								<input type="text" name="" id="" value="<?php echo $invite->name_inviter; ?>" class="form-control form-control-lg" disabled />
								<?php if ($invite->video_sent) : ?>
									<span class="status"><?php getIcon(true); ?>Vídeo já foi enviado. <a href="#">Visualizar vídeo</a></span>
								<?php else : ?>
									<span class="status mt-1"><?php getIcon(false); ?><?php echo $invite->email_opened ? "Usuário abriu o e-mail" : "Usuário não finalizou o vídeo"; ?></span>
								<?php endif; ?>
							</div>
							<div class="col-md-5">
								<input type="email" name="" id="" value="<?php echo $invite->email_inviter; ?>" class="form-control form-control-lg" disabled />
							</div>
							<div class="col-md-2"><a href="#" class="d-inline-block pt-3">Reenviar e-mail</a></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer('tributes'); ?>
