<?php global $my_tributes; ?>

<?php get_header('tributes'); ?>

<main id="my-tributes">
	<div class="container py-3 tribute-container tribute-app">
		<div class="row mb-5">
			<div class="col-md-12">
				<h1 class="title text-center">Seus tributos</h1>
			</div>
		</div>
		<?php foreach ($my_tributes as $tribute) : ?>
			<div class="row mb-4 pb-3 border-bottom">
				<div class="col-md-3">
					<p>Pra quem é o tributo?</p>
					<p><strong><?php echo $tribute->name_honored; ?></strong></p>
				</div>
				<div class="col-md-3">
					<p>Data de Vencimento</p>
					<p><strong><?php echo date('d/m/Y', strtotime( $tribute->deadline ) ); ?></strong></p>
				</div>
				<div class="col-md-3">
					<p>% de sucesso</p>
					<p><strong>75%</strong></p>
				</div>
				<div class="col-md-3">
					<a href="#" class="btn btn-primary btn-lg btn-block">Visualizar</a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</main>

<?php get_footer('tributes'); ?>
