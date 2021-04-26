(function( $ ) {
	'use strict';
    $(document).ready(function(){
		$('form.form_search_order').on('submit',function(e) {
			$(this).off(e); 
			e.preventDefault();
			e.stopPropagation();

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
						let obj = $.parseJSON( response );
						if( obj.found != 0 ){
							$('form.form_search_order').submit();
						}
					}
				});
		});


		$(document).on('click', '.btn-visualizar-pedido',function(e){
			e.preventDefault();
			var wnonce = $(this).attr('button-nonce');
			var order_id = $(this).attr('order-id');
			$.ajax(
				{
					type: 'POST',
					url: polen_ajax.ajaxurl + '?action=get_talent_order_data',
					data: {
						// action: 'get_talent_order_data',
						order: order_id,
						security: wnonce
					},
					success: function( response ) {
						let obj = $.parseJSON( response );
						if( obj.success == true ){
							$('#order-value').html(obj['data'][0]['total']);
                            if( obj['data'][0]['from'].length === 0 ) {
                                $('#item-render-video-from').hide();
                            } else {
                                $('#item-render-video-from').show();
                            }
                            $('#video-from').html(obj['data'][0]['from'])
							$('#video-name').html(obj['data'][0]['name']);
							$('#video-email').html(obj['data'][0]['email']);
							$('#video-category').html(obj['data'][0]['category']);
							$('#expiration-time').html(obj['data'][0]['expiration']);
							$('#video-instructions').html(obj['data'][0]['instructions']);
							$('.modal-group-buttons').attr('order-id',obj['data'][0]['order_id'] );
						}
					}
				});
		});

		/**** talento ****/
		$('button.talent-check-order').on('click',function(){
			var wnonce = $(this).parent().attr('button-nonce');
			var order_id = $(this).parent().attr('order-id');
			var type = $(this).attr('action-type');

			if( type == 'reject' ){
				var confirm_reject = confirm("Deseja realmente rejeitar o pedido?");
				if( confirm_reject == true)  {
					$.ajax(
						{
							type: 'POST',
							url: polen_ajax.ajaxurl,
							data: {
							action: 'get_talent_acceptance',
							order: order_id,
							type: type,
							security: wnonce
							},
							success: function( response ) {
								let obj = $.parseJSON( response );
								if( obj['success'] == true ){
									if( obj['code'] == 1 ){
										$('#OrderActions').modal('toggle');
										location.href='/my-account/send-video/?order_id=' + order_id;
									}
									if( obj['code'] == 2 ){
										location.reload();
									}		
								}
							}
						});
				} 
			}else{
				$.ajax(
					{
						type: 'POST',
						url: polen_ajax.ajaxurl,
						data: {
						action: 'get_talent_acceptance',
						order: order_id,
						type: type,
						security: wnonce
						},
						success: function( response ) {
							let obj = $.parseJSON( response );
							//console.log(obj);
							if( obj['success'] == true ){
								if( obj['code'] == 1 ){
									$('#OrderActions').modal('toggle');
									//location.reload();'/enviar-video/?order_id=35
									location.href='/my-account/send-video/?order_id=' + order_id;
								}
								if( obj['code'] == 2 ){
									//$('#OrderActions').modal('toggle');
									location.reload();
								}		
							}
						}
					});
			}
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


		$('.video-instruction-refresh').on('click',function(){
			var category_item = $('select[name="video_category"]');
			var category_name = category_item.val();
			var cart_id = category_item.attr('data-cart-id');

			console.log( category_name );
			if( category_name ){
				$.ajax(
				{
					type: 'POST',
					url: polen_ajax.ajaxurl,
						data: {
						action: 'get_occasion_description',
						occasion_type: category_name,
						refresh: 1
					},
					success: function( response ) {
						let obj = $.parseJSON( response );
						//console.log(obj['response'][0].description);

						if( obj ){
							if( obj['response'][0].description ){
								$( '#cart_instructions_to_video_' + cart_id ).html(obj['response'][0].description);
							}
						}
							
					}
				});	
			}	
		});	
	});
})( jQuery );
