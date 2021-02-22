<?php

// $size pode ser 'medium' e 'small'
function polen_get_card($id_produto, $size = "medium")
{
	$url = "#opa";
	$name = "Nome do Artista";
	$price = "100";
	$image = "https://picsum.photos/255/350";
	$category = "Categoria";
	$category_url = "#cat";
	ob_start();
?>
	<div class="col-md-3">
		<div class="polen-card <?= $size; ?>">
			<figure class="image">
				<a href="<?= $url; ?>">
					<img src="<?= $image; ?>" alt="<?= $name; ?>">
				</a>
				<span class="price">R$<?= $price; ?></span>
			</figure>
			<h4 class="title">
				<a href="<?= $url; ?>"><?= $name; ?></a>
			</h4>
			<h5 class="category">
				<a href="<?= $category_url; ?>"><?= $category; ?></a>
			</h5>
		</div>
	</div>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}
