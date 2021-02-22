<?php

// $size pode ser 'medium' e 'small'
function polen_get_card($url, $image, $price, $name, $category, $category_url, $size = "medium")
{
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
