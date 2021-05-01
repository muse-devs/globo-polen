<?php
get_header();
use \Polen\Includes\Polen_Video_Info;

if( ( isset( $video_hash ) && empty( $video_hash ) ) || empty( $video_hash ) ){
    $video_hash = get_query_var('v');
}

$video_info = Polen_Video_Info::get_by_hash( $video_hash );
var_dump('here');die;
if( empty( $video_info ) ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 );
    exit();
}

use \Polen\Includes\Polen_Update_Fields;
$Talent_Fields = new Polen_Update_Fields();
$talent = $Talent_Fields->get_vendor_data($video_info->talent_id);

?>
    <main id="primary" class="site-main">
        <?php
        polen_get_video_player( $talent, $video_info );
        ?>
    </main><!-- #main -->
<?php

get_footer();
