<?php
get_header();
use \Polen\Includes\Polen_Video_Info;

$video_info = Polen_Video_Info::get_by_hash( $video_hash );
$talent = get_user_by( 'id', $video_info->talent_id );

?>
    <main id="primary" class="site-main">
        <?php
        polen_get_video_player( $talent, $video_info );
        ?>
    </main><!-- #main -->
<?php

get_footer();