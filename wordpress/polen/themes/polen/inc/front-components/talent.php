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
					<button onclick="clickToBuy()" class="btn btn-outline-light btn-lg">Peça o seu vídeo</button>
				<?php else : ?>
					<button class="btn btn-outline-light btn-lg">Indisponível</button>
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

function polen_front_get_talent_stories()
{
	?>
		<div id="stories" class="mr-2"></div>
	<?php
}

/**
 * Criar a lista de videos já feitos
 * @param stdClass Polen_Talent_Fields
 * @return HTML
 */
function polen_front_get_talent_videos($talent)
{
	$items = polen_get_videos_by_talent($talent);

	global $product;
	$video_url = home_url() . "/v/";
?>
	<section id="talent-videos" class="row mb-1 banner-scrollable" data-public-url="<?php echo $video_url; ?>">
		<div class="d-none d-md-block col-md-12 text-right custom-slick-controls"></div>
		<div class="col-md-12 p-0">
			<div class="banner-wrapper">
				<div class="banner-content type-video<?php if (sizeof($items) < 1) echo " ml-3 ml-md-0" ?>">
					<?php foreach ($items as $item) : ?>
						<div class="polen-card-video">
							<figure class="video-cover">
                <video src="<?= $item['video']; ?>" poster="<?= $item['cover']; ?>"></video>
                <div class="video-player-button" data-id="0"></div>
								<?php /*<img loading="lazy" src="<?= $item['cover']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
								<a href="javascript:openVideoByHash('<?= $item['hash']; ?>')" class="video-player-button"></a> */ ?>
								<?php polen_video_icons($talent->user_id, $item['initials'], $item['first_order'] == "1"); ?>
							</figure>
						</div>
					<?php endforeach; ?>
					<?php polen_talent_promo_card($talent); ?>
				</div>
			</div>
		</div>
	</section>
	<input type="hidden" id="product_id" value="<?php echo $product->get_id(); ?>" />

	<div id="video-modal" class="background video-modal">
		<div class="video-card-body">
			<button id="close-button" class="close-button" onclick="hideModal()"><?php Icon_Class::polen_icon_close(); ?></button>
			<div id="video-box"></div>
		</div>
	</div>
<?php
}

function polen_front_get_talent_mini_bio($image_data, $name, $category)
{
  ?>
  <div class="talent-mini-bio text-center">
    <div class="avatar avatar-lg">
        <img src="<?php echo $image_data['image']; ?>" alt="<?php echo $image_data["alt"]; ?>" />
    </div>
    <h2 class="typo typo-subtitle-large text-center mt-3"><?php echo $name; ?></h2>
    <h3 class="typo typo-text mt-1"><?php echo $category; ?></h3>
  </div>
  <?php
}

function polen_front_get_videos_single($talent, $videos)
{
  if( ! wp_script_is( 'owl-carousel', 'enqueued' ) ) {
      wp_enqueue_script('owl-carousel');
  }
?>
	<section id="talent-videos" class="row my-1 pb-4">
    <?php if($videos && sizeof($videos) > 0) : ?>
		<div class="col-md-12 p-0 mb-4">
			<div id="videos-carousel" class="owl-carousel owl-theme ">
          <?php foreach ($videos as $key=>$video) : ?>
            <?php if ($video['video']) : ?>
              <div class="item">
                <div class="polen-card-video talent-single">
                  <figure id="cover-box" class="video-cover" data-id="<?php echo $key; ?>">
                    <img loading="lazy" src="<?php echo $video['cover']; ?>" alt="">
                    <div class="video-player-button" data-id="<?php echo $key; ?>"></div>
                    <div class="video-icons">
                      <figure class="image-cropper color small">
                        <?php echo $video['thumb']; ?>
                      </figure>
                      <figure class="image-cropper small">
                        <?php echo $video['initials']; ?>
                      </figure>
                    </div>
                  </figure>
                  <video id="video-box" class="src-box d-none" playsinline data-id="<?php echo $key; ?>"
                    <?php if ($key == 0) {
                      echo " muted";
                    } ?>>
                    <source src="<?php echo $video['video']; ?>" type="video/mp4">
                  </video>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
          <div class="item">
            <?php polen_talent_promo_card($talent); ?>
          </div>
      </div>
		</div>
    <?php endif; ?>
	</section>
  <script>
    window.onload = function() {
      jQuery('.video-player-button')[0].click();
    }
  </script>
<?php
}

function polen_get_talent_card($talent, $social = false)
{
?>
	<div class="talent-card alt<?php if($social) { echo ' criesp'; } ?>">
		<header class="row pb-3 header">
			<div class="col-md-12 d-flex align-items-center">
				<figure class="avatar avatar-sm image-cropper">
					<img src="<?php echo isset($talent["avatar"]) ? $talent["avatar"] : TEMPLATE_URI . '/assets/img/avatar.png';  ?>" alt="<?php echo isset($talent["alt"]) ? $talent["alt"] : $talent["name"]; ?>">
				</figure>
				<h4 class="name ml-3"><?php echo $talent["name"]; ?></h4>
			</div>
		</header>
		<div class="price-box pt-3">
			<span class="cat">Você vai <?php echo $social ? "doar" : "pagar" ?></span>
			<p class="price mt-2">
				<?php wc_cart_totals_order_total_html(); ?>
			</p>
			<?php if (!empty($talent['discount']) && !$social) : ?>
				<div class="row price-box-details">
					<div class="col-12 mt-3">
						<table style="width: 60%;">
							<tr>
								<td>Valor:</td>
								<td><?php echo $talent['price']; ?></td>
							</tr>
							<tr>
								<td>Desconto:</td>
								<td><?php echo $talent['discount']; ?></td>
							</tr>
							<tr>
								<td>Total:</td>
								<td><?php wc_cart_totals_order_total_html(); ?></td>
							</tr>
						</table>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($talent["has_details"] && !$social) : ?>
				<button class="show-details d-flex justify-content-center align-items-center" onclick="showDetails()"><?php Icon_Class::polen_icon_chevron("down") ?></button>
			<?php endif; ?>
		</div>
		<footer class="row details-box">
			<div class="col pt-4 mt-3 details">
				<div class="row personal">
					<div class="col d-flex justify-content-between">
						<?php
						if ( !empty( $talent["from"] ) ) : ?>
							<div class="item">
								<span class="title">Vídeo de</span>
								<p class="value mt-2"><?php echo $talent["from"]; ?></p>
							</div>
							<div class="item">
								<?php Icon_Class::polen_icon_arrows(); ?>
							</div>
						<?php endif; ?>
						<div class="item">
							<span class="title">Para</span>
							<p class="value mt-2"><?php echo $talent["to"]; ?></p>
						</div>
					</div>
				</div>
				<div class="row ocasion mt-4">
					<div class="col-12">
						<span class="title">Ocasião</span>
						<p class="value mt-2"><?php echo $talent["category"]; ?></p>
					</div>
				</div>
				<div class="row mail mt-4">
					<div class="col-12">
						<span class="title">e-mail</span>
						<p class="value mt-2"><?php echo $talent["mail"]; ?></p>
					</div>
				</div>
				<div class="row description mt-4">
					<div class="col-12">
						<?php if( !$social ) : ?>
							<span class="title">Instruções</span>
						<?php else : ?>
							<span class="title">Sua cidade</span>
						<?php endif; ?>
						<p class="value mt-2"><?php echo $talent["description"]; ?></p>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<script>
		var details = document.querySelector(".details-box");
		var btn = document.querySelector(".show-details");

		function showDetails() {
			details.classList.toggle("show");
			btn.classList.toggle("-active");
		}
	</script>
<?php
}

function polen_talent_deadline($talent)
{
  global $Polen_Plugin_Settings;
  $order_expires = $Polen_Plugin_Settings['order_expires'];
?>
  <div class="col-md-12">
		<div class="row">
			<div class="col-12 col-md-6 m-md-auto">
				<div class="row">
					<div class="col-12 col-md-12">
            <div class="box-round py-3 px-3 text-center text-md-center">
              <p class="typo-double-line-height mb-0">
                Peça hoje e receba até <br>
                <b>
                  <?php
                  $date = date("d/m/Y");
                  echo date( "d/m/y", strtotime('+'.$order_expires.' days') );
                ?>
                </b>
              </p>
            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_talent_review($reviews)
{
?>
  <section class="row tutorial mt-4 mb-4">
    <div class="col-md-12">
      <header class="row mb-3">
        <div class="col">
          <h2 class="typo typo-title">Avaliações</h2>
        </div>
      </header>
    </div>
    <div class="col-md-12 mb-3">
      <?php
        if($reviews) {
      ?>
      <div id="review-carousel" class="owl-carousel owl-theme">
        <?php
        foreach ($reviews as $review) {
          $review_id = $review->comment_ID;
          $date = new DateTime($review->comment_date);
          $rate = get_comment_meta($review_id, "rate");

          $user_name = $review->comment_author;
          if( empty( $user_name ) ) {
            $user = get_user_by( 'id', $review->user_id );
            $user_name = $user->display_name;
          }
        ?>
        <div class="item mr-3">
          <div class="box-round py-3 px-3">
            <div class="row comment-box">
              <div class="col-sm-12">
                <span class="typo-title"><?php echo $user_name; ?></span>
              </div>
              <div class="col-md-12 box-stars">
                <?php polen_get_stars((int) $rate[0]); ?>
              </div>
              <div class="col-sm-12">
                <p class="typo-p truncate truncate-4"><?php echo $review->comment_content; ?></p>
              </div>
            </div>
          </div>
        </div>
        <?php
        }
        ?>
        <div class="item mr-3">
          <a href="<?= polen_get_url_review_page(); ?>" class="link-alt typo-link typo-no-underline">
            <div class="box-round py-3 px-3 d-flex align-items-center justify-content-center">
              <div class="row">
                <div class="col-sm-12">
                  <h3 class="typo-title text-center">Gostou das avaliações?</h3>
                </div>
                <div class="col-sm-12 d-flex justify-content-center">
                <span class="typo typo-link link-alt">Ver todos</span>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <?php
        }
        else {
      ?>
      <div class="box-round py-4 px-4">
        <div class="row">
          <div class="col-12 page-content text-center mt-3">
            <p>Esse ídolo ainda não tem avaliação, compre hoje e seja o primeiro a avaliar.</p>
          </div>
        </div>
      </div>
      <?php
        }
      ?>
    </div>
  </section>
<?php
}
