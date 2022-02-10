// const home_carrousel = function() {
// 	const images = document.querySelectorAll(".top-banner .carrousel .image");
// 	console.log(images);
// }

// home_carrousel();

jQuery(document).ready(function ($) {
  $('.carousel').on('touchstart', function (event) {
    const xClick = event.originalEvent.touches[0].pageX;
    $(this).one('touchmove', function (event) {
      const xMove = event.originalEvent.touches[0].pageX;
      const sensitivityInPx = 5;

      if (Math.floor(xClick - xMove) > sensitivityInPx) {
        $(this).carousel('next');
      }
      else if (Math.floor(xClick - xMove) < -sensitivityInPx) {
        $(this).carousel('prev');
      }
    });
    $(this).on('touchend', function () {
      $(this).off('touchmove');
    });
  });
  $('#product-carousel').owlCarousel({
    loop: true,
    items: 1,
    autoplayTimeout: 5000,
    animateOut: 'fadeOut',
    autoplayHoverPause: true,
    margin: 0,
    nav: false,
    autoplay: true,
    dots: true,
    autoHeight: false,
  });
});

(function ($) {
  // Newsletter submit click
  $(document).on("submit", "form#newsletter, form#newsletter-mobile", function (e) {
    const formName = "form#" + this.id;
    e.preventDefault();
    // Ajax Request
    polAjaxForm(
      formName,
      function () {
        polMessages.message(
          "Seu e-mail foi adicionado a lista",
          "Aguarde nossas novidades!"
        );
      },
      function (error) {
        polMessages.error(error);
      }
    );
    // Zapier request
    polRequestZapier(
      formName
    );
  });
})(jQuery);

// const home_video = function () {
// 	const video_banner = document.getElementById("video-banner");
// 	if(!video_banner) {
// 		return;
// 	}

// 	let currentVideo = polIsSmall()
// 		? home_video.mobile.class
// 		: home_video.desktop.class;

// 	function polIsSmall() {
// 		return window.innerWidth < 670;
// 	}

// 	function changeVideo(obj) {
// 		currentVideo = obj.class;
// 		const sources = video_banner.getElementsByTagName("source");

// 		video_banner.setAttribute("poster", obj.poster);
// 		sources[0].src = obj.video;
// 		video_banner.load();
// 	}

// 	function checkSize() {
// 		if (polIsSmall()) {
// 			currentVideo !== home_video.mobile.class &&
// 				changeVideo(home_video.mobile);
// 		} else {
// 			currentVideo !== home_video.desktop.class &&
// 				changeVideo(home_video.desktop);
// 		}
// 	}

// 	window.onresize = checkSize;
// };

// home_video();


