const form = "#help-form";
const url_success = document.getElementById("url-success").value;

const bus_form = new Vue({
  el: form,
  data: {
    phone: ""
  },
  methods: {
    handleChange: function (e) {
      this.phone = mtel(e.target.value);
    },
    handleEdit: function () {
      this.edit = true;
    },
    handleSubmit: function () {
      polAjaxForm(
        form,
        function (e) {
          window.location.href = url_success;
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
})(jQuery);
