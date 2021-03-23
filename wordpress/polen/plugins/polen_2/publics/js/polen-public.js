(function( $ ) {
	'use strict';
    $(document).ready(function(){
		$('button.btn-search-order').on('click',function(e) {
			e.preventDefault();
			var wnonce = $('#_wpnonce').val();
			var order_id = $('#order_number').val();
			var email = $('#fan_email').val();
			$.ajax(
				{
					type: 'POST',
					url: polen_ajax.ajaxurl,
						data: {
						action: 'search_order_status',
						order: order_id,
						email: email,
						security: wnonce
					},
					success: function( response ) {
						//let obj = $.parseJSON( response );
						console.log(response);
					}
				});
		});

		/**** talento ****/
		$('button.talent-check-order').on('click',function(){
			var wnonce = $(this).parent().attr('button-nonce');
			var order_id = $(this).parent().attr('order-id');
			var type = $(this).attr('type');
			$.ajax(
				{
					type: 'POST',
					url: polen_ajax.ajaxurl,
						data: {
						action: 'talent_acceptance',
						order: order_id,
						type: type,
						security: wnonce
					},
					success: function( response ) {
						let obj = $.parseJSON( response );
						console.log(obj['success']);
						if( type == 'reject' && obj['success'] == true ){
							$('div[box-id="'+order_id+'"]').remove();

							let qtd = parseInt( $('#order-count').html() );
							qtd = qtd - 1;

							$('#order-count').html(qtd);
						}
					}
				});
		});

		$('.polen-cart-item-data').on('blur change paste click',function(){
			var cart_id = $(this).data( 'cart-id' );
			var item_name = $(this).attr('name');

			if( item_name == 'video_category' ){	
				var item_value = $(this).val();

				if( item_value ){
					$.ajax(
						{
							type: 'POST',
							url: polen_ajax.ajaxurl,
								data: {
								action: 'get_occasion_description',
								occasion_type: item_value,
							},
							success: function( response ) {
								let obj = $.parseJSON( response );
								//console.log(obj['response'][0].description);
								if( obj ){
									$( '#cart_instructions_to_video_' + cart_id ).html(obj['response'][0].description);
								}	
							}
						});	
				}				
			}

			var allowed_item = [ 'offered_by', 'video_to', 'name_to_video', 'email_to_video', 'video_category', 'instructions_to_video', 'allow_video_on_page' ];
			if( $.inArray( item_name, allowed_item ) !== -1 ){
				$.ajax(
				{
					type: 'POST',
					url: polen_ajax.ajaxurl,
						data: {
						action: 'polen_update_cart_item',
						security: $('#woocommerce-cart-nonce').val(),
						polen_data_name: item_name,
						polen_data_value: $('#cart_'+ item_name + '_' + cart_id).val(),
						cart_id: cart_id
					},
					success: function( response ) {
					//	$('.cart_totals').unblock();
						//$( '.woocommerce-cart-form' ).find( ':input[name="update_cart"]' ).prop( 'disabled', false ).attr( 'aria-disabled', false );
					}
				});
			}	
		});	

	});
})( jQuery );
