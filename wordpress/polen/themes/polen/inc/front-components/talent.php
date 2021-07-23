<?php

function polen_talent_promo_card($talent)
{
	global $product;
?>
	<div id="video-promo-card" class="video-promo-card">
		<div class="box-color card row">
			<div class="col-12 col-md-12 d-flex flex-column justify-content-center align-items-center text-center p-2">
				<div class="image-cropper">
					<?php echo polen_get_avatar($talent->user_id, 'polen-square-crop-lg'); ?>
				</div>
				<p class="mt-2">E aí, ficou com vontade de ter um vídeo?</p>
				<?php if ($product->is_in_stock()) : ?>
					<a href="#pedirvideo" class="btn btn-outline-light btn-lg">Peça o seu vídeo</a>
				<?php else : ?>
					<a href="#pedirvideo" class="btn btn-outline-light btn-lg">Indisponível</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php
}

function polen_get_talent_socials($talent)
{
?>
	<?php if ($talent->facebook) : ?>
		<a href="<?php echo $talent->facebook; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('facebook'); ?>Facebook</a>
	<?php endif; ?>
	<?php if ($talent->instagram) : ?>
		<a href="<?php echo $talent->instagram; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('instagram'); ?>Instagram</a>
	<?php endif; ?>
	<?php if ($talent->twitter) : ?>
		<a href="<?php echo $talent->twitter; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('twitter'); ?>Twitter</a>
	<?php endif; ?>
<?php
}
