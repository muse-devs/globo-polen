<?php

function va_get_banner_book($small = false)
{
	$img_bg = TEMPLATE_URI . "/assets/img/video-autografo/bg_lh_right.png";
	$img_book = TEMPLATE_URI . "/assets/img/video-autografo/book_cover.png";

?>
	<div class="row<?php echo $small ? ' mb-2' : ' mb-4'; ?>">
		<div class="col-12">
			<div class="va-top-banner<?php echo $small ? ' small' : ''; ?>">
				<div class="box-round">
					<img src="<?php echo $img_bg; ?>" alt="Fundo do Box" class="img-bg" />
				</div>
				<div class="content<?php echo $small ? '' : ' pb-2'; ?>">
					<img src="<?php echo $img_book; ?>" alt="Capa do Livro" class="book-cover" />
					<h1 class="title"><?php echo $small ? 'Livro - ' : ''; ?>De porta em porta</h1>
				</div>
			</div>
		</div>
	</div>
<?php
}

function va_get_book_infos()
{
?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="box-round book-info-wrapp py-3 px-3">
				<div class="row">
					<div class="col-12">
						<h4 class="title">Sobre o Livro</h4>
						<p>Em seu novo livro, Luciano Huck compila memórias pessoais, aprendizados e conversas com representantes de várias áreas do conhecimento para trazer luz ao debate sobre a responsabilidade individual para a construção de uma sociedade mais igualitária.<br />
							<a href="javascript:showMoreText()" class="link-more-text show">Mostrar mais</a>
						</p>
						<p class="more-text">De porta em porta reúne as contribuições de figuras como Yuval Noah Harari, Esther Duflo, Michael Sandel e Anne Applebaum, além de memórias muito pessoais de Huck e relatos de encontros com brasileiros anônimos, mas cheios de histórias para contar.</p>
					</div>
				</div>
				<div class="row mb-4">
					<div class="col-12 col-md-6">
						<h4 class="title">Informações do livro</h4>
						<div class="row">
							<div class="col-3">
								<div class="book-info">
									<div class="title">Páginas</div>
									<?php Icon_Class::va_icons("pages"); ?>
									<div class="description">264 páginas</div>
								</div>
							</div>
							<div class="col-3">
								<div class="book-info">
									<div class="title">Idioma</div>
									<?php Icon_Class::va_icons("language"); ?>
									<div class="description">Português</div>
								</div>
							</div>
							<div class="col-3">
								<div class="book-info">
									<div class="title">Editora</div>
									<?php Icon_Class::va_icons("books"); ?>
									<div class="description">Objetiva</div>
								</div>
							</div>
							<div class="col-3">
								<div class="book-info">
									<div class="title">Publicação</div>
									<?php Icon_Class::va_icons("calendar"); ?>
									<div class="description">20 agosto 2021</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12">
						<h4 class="title text-md-center">Avaliação</h4>
						<div class="row book-rate text-center">
							<div class="col-12">
								<?php polen_get_stars(4.2); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
