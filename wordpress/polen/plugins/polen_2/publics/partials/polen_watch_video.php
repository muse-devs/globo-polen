<?php
use \Polen\Includes\Polen_Video_Info;
$video_info = Polen_Video_Info::get_by_hash( $video_hash );

global $current_user;
$user_id = $current_user->ID;
$order = wc_get_order($video_info->order_id);
$order_user_id = $order->get_user_id();

if( empty( $video_info ) || $user_id !== $order_user_id) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 );
    exit();
}

use \Polen\Includes\Polen_Update_Fields;
$Talent_Fields = new Polen_Update_Fields();
$talent = $Talent_Fields->get_vendor_data( $video_info->talent_id );
?>
    <main id="primary" class="site-main">
        <?php
        polen_get_video_player( $talent, $video_info, get_current_user_id() );
        ?>
    </main><!-- #main -->