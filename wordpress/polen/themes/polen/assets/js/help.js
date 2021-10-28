const form = "#help-form";

const bus_form = new Vue({
  el: form,
  data: {
    phone: ""
  },
  methods: {
    handleChange: function (e) {
      //this.phone = mtel(e.target.value);
    },
    handleEdit: function () {
      this.edit = true;
    },
    handleSubmit: function () {
      polAjaxForm(
        form,
        function (e) {
          polMessages.message(
						"Enviado!",
						"Sua mensagem foi enviada com sucesso!"
					);
        },
        function (e) {
          polMessages.error(e);
        }
      );
    },
  },
});

(function ($) {
  // Faq accordion close behavior
  $(document).on("click", ".panel-button", function (e) {
    let id = $(this).attr('href');
    $('.panel-button:not([href='+id+'])').addClass("collapsed").attr("aria-expanded","false");
    $('.collapse:not('+id+')').removeClass('show');
  });

  // Open collapse url
  let currentURL = window.location.href;
  var hash = currentURL.substring(currentURL.indexOf('#'));
  if (hash) {
    $('.panel-button[aria-controls='+hash.substring(1)+']').removeClass("collapsed").attr("aria-expanded","true");
    $(hash).addClass('show');
  }
})(jQuery);
