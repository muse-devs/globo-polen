<?php

function polen_front_get_banner()
{
	ob_start();
?>
	<section class="row my-5 py-4 top-banner">
		<div class="col-sm-12">
			<video class="video" autoplay muted>
				<source src="<?= TEMPLATE_URI; ?>/assets/video.mp4" type="video/mp4">
				<!-- <source src="movie.ogg" type="video/ogg"> -->
			</video>
			<div class="content">
				<h2 class="title">Presenteie e<br />surpreenda com vídeos personalizados.</h2>
				<a href="#como" class="link">Como funciona</a>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

// $size pode ser 'medium' e 'small'
function polen_front_get_card($item, $size = "medium")
{
	$class = "col-md-3";
	if ($size === "small") {
		$class = "col-flex-20";
	}
	ob_start();
?>
	<div class="<?= $class; ?>">
		<div class="polen-card <?= $size; ?>">
			<figure class="image">
				<a href="<?= $item["talent_url"]; ?>">
					<img src="<?= $item["image"]; ?>" alt="<?= $item["name"]; ?>">
				</a>
				<span class="price">R$<?= $item["price"]; ?></span>
			</figure>
			<h4 class="title">
				<a href="<?= $item["talent_url"]; ?>"><?= $item["name"]; ?></a>
			</h4>
			<h5 class="category">
				<a href="<?= $item["category_url"]; ?>"><?= $item["category"]; ?></a>
			</h5>
		</div>
	</div>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_news($items)
{
	if (!$items) {
		return;
	}
	ob_start();
?>
	<section class="row my-5 py-4 news">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Novidades</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos</a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row">
				<?php foreach ($items as $item) : ?>
					<?php polen_front_get_card($item); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_categories($items)
{
	if (!$items) {
		return;
	}
	ob_start();
?>
	<section class="row my-5 py-4 categories">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Categorias</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos</a></div>
			</header>
		</div>
		<div class="col md-12">
			<div class="row">
				<?php foreach ($items as $item) : ?>
					<div class="col-md-3">
						<figure class="polen-card category">
							<img src="<?= $item["image"] ?>" alt="<?= $item["title"] ?>">
							<a href="<?= $item["url"] ?>" class="link"><?= $item["title"] ?></a>
						</figure>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_artists($items)
{
	if (!$items) {
		return;
	}
	ob_start();
?>
	<section class="row my-5 py-4 all-artists">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Todos os Artistas</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver mais</a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row d-flex justify-content-between flex-wrap">
				<?php foreach ($items as $item) : ?>
					<?php polen_front_get_card($item, "small"); ?>
				<?php endforeach; ?>
			</div>
			<div class="row mt-5">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-primary btn-lg">Ver mais</button>
				</div>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_tutorial()
{
	ob_start();
?>
	<section class="row my-5 py-4 tutorial">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col text-center">
					<h2>Como funciona</h2>
					<p class="mt-4">Presenteie e surpreenda com vídeos personalizados.</p>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row mt-5">
				<div class="col-md-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="https://picsum.photos/200/200" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Conecte-se aos ídoles</h5>
						<p class="description">Peça um vídeo personalizado com o seu ídolo para celebar ocasiões especiais.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="https://picsum.photos/200/200" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Receba sua encomenda</h5>
						<p class="description">Ídolo recebe o seu pedido, atende e entrega pela plataforma. </p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="https://picsum.photos/200/200" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Mande para todo mundo</h5>
						<p class="description">Você pode enviar o vídeo para os amigos ou postar nas redes.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}
