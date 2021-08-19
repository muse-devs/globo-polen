<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
			<?php
			va_get_banner_book(true);
			va_magalu_box_cart();
			?>
			<div class="row mb-3">
				<div class="col-12">
					<h1 class="title mb-3">Inserir código</h1>
					<form id="va-check-code">
						<input type="hidden" name="action" value="" />
						<input type="text" class="form-control form-control-lg mb-2" placeholder="Inserir código fornecido pela Magalu" required />
						<input type="submit" class="btn btn-primary btn-lg btn-block" value="Checar" />
					</form>
				</div>
			</div>
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();
