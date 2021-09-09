<section class="section section-featured">
  <div class="swiper-featured swiper-container">
    <div class="swiper-wrapper">

      <?php if( have_rows('destaques_desktop', 'options') ): ?>
        <?php while( have_rows('destaques_desktop', 'options') ): the_row(); ?>

          <?php
          $posts = get_sub_field('post_principal_desktop', 'options');
          if( $posts ): ?>
          <?php foreach( $posts as $post): ?>
            <?php setup_postdata($post); ?>
            <div class="swiper-slide">
              <div style="background-image: url('<?php echo the_post_thumbnail_url( 'destaque_desk', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>')" class="item-featured">
                <div class="container">
                  <div class="row">

                    <a href="<?php the_permalink(); ?>" title="<?php echo the_title(); ?>" class="caption col-8">
                      <div class="meta-info">
                        <p class="card-subtitle"><time itemprop="datePublished" datetime="<?php the_time('Y/m/d g:i:s A ') ?>"> <?php echo get_the_date(); ?> </time></p>
                        <p class="card-subtitle"><?php $category_detail=get_the_category($post->ID); foreach($category_detail as $cd){ echo $cd->cat_name; } ?></p>
                      </div>
                      <h2 class="card-title -big"><?php echo the_title(); ?></h2>
                      <span class="btn btn-dark btn-md">Saiba mais</span>
                    </a>

                    <div class="col-4 list-cards">
                      <?php
                      $posts = get_sub_field('outros_principal_desktop', 'options');
                      if( $posts ): ?>
                      <?php foreach( $posts as $post): ?>
                        <?php setup_postdata($post); ?>
                        <a href="<?php the_permalink(); ?>" title="<?php echo the_title(); ?>" class="card post-card -h">
                          <div class="figure -small"> <img src="<?php echo the_post_thumbnail_url( 'destaque_small', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"> </div>
                          <div class="card-body -small">
                            <h2 class="card-title -small"><?php echo the_title(); ?></h2>
                            <div class="meta-info">
                              <p class="card-subtitle text-muted"><time itemprop="datePublished" datetime="<?php the_time('Y/m/d g:i:s A ') ?>"> <?php echo get_the_date(); ?> </time></p>
                              <p class="card-subtitle text-muted"><?php $category_detail=get_the_category($post->ID); foreach($category_detail as $cd){ echo $cd->cat_name; } ?></p>
                            </div>
                          </div>
                        </a>
                      <?php endforeach; ?>
                      <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                  </div>

                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>

    <?php endwhile; ?>
  <?php endif; ?>
  <div class="swiper-pagination pagination-featured -white"></div>
</div>
</div>
<div class="swiper-button-next swiper-featured-next"> <svg class="icon icon-angle-right"> <use xlink:href="#icon-angle-right"></use></svg> </div>
<div class="swiper-button-prev swiper-featured-prev"> <svg class="icon icon-angle-left"> <use xlink:href="#icon-angle-left"></use></svg> </div>
</section>
