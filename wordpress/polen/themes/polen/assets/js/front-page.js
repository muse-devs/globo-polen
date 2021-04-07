$ = jQuery;

$(document).ready(function () {
    $('.slick-padding').slick({
        arrows: false,
		dots: false,
        // centerMode: true,
		infinite: true,
        slidesToShow: 4,
		responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
			  }
			},
			{
			  breakpoint: 768,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 2
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 1,
				// centerPadding: '94px',
			  }
			}
		  ]
    });
});
