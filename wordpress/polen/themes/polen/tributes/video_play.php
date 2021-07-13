<?php
global $tribute;

// var_dump($tribute);
?>

<?php get_header('tributes'); ?>

<div class="container">
	<?php polen_get_video_player_html($tribute); ?>
</div>

<?php get_footer('tributes'); ?>
