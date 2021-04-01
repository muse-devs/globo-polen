(function($) {
    $(document).on('click', '.cart-video-to', function(e) {
        if( $(this).val() == 'to_myself' ) {
            $('.video-to-info').hide();
            $('input[name=offered_by]').prop( 'required', false );
//            $('input[name=name_to_video]').prop( 'required', false );
        } else {
            $('.video-to-info').show();
            $('input[name=offered_by]').prop( 'required', true );
//            $('input[name=name_to_video]').prop( 'required', true );
        }
    });
})(jQuery);