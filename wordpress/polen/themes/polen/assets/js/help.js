(function ($) {
  // Faq accordion close behavior
  $(document).on("click", ".panel-button", function (e) {
    let id = $(this).attr('href');
    $('.panel-button:not([href='+id+'])').addClass("collapsed").attr("aria-expanded","false");
    $('.collapse:not('+id+')').removeClass('show');
  });
})(jQuery);
