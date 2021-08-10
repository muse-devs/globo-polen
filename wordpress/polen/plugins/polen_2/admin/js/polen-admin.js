(function( $ ) {
	'use strict';

	$(function() {
		if( $( '.link-downloads-btn' ).length > 0 ) {
			$('.link-downloads-btn').click(function( evt ){
				evt.preventDefault();
				let tribute_id = jQuery( evt.currentTarget ).attr('data-tribute-id');
				let security = jQuery( evt.currentTarget ).attr('nonce');
				jQuery.post('',{tribute_id,security},function(data){});
			});
		}
	});

})( jQuery );
