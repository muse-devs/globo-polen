<?php
if ( DEVELOPER ) {
	return;
}
global $Polen_Plugin_Settings;

if( !empty( $Polen_Plugin_Settings['polen_google_tagmanager_key'] ) ) :
?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $Polen_Plugin_Settings['polen_google_tagmanager_key']; ?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

<?php endif;

if( !empty( $Polen_Plugin_Settings['polen_ca_pub_key'] ) ) : ?>
	<script data-ad-client="<?= $Polen_Plugin_Settings['polen_ca_pub_key']; ?>" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php endif;
