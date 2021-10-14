function verify_checkbox_selected_to_hidde_or_show_fields() {
  let value_checked = ''
  if (document.querySelectorAll('input[name="video_to"')[1].checked) {
    value_checked = document.querySelectorAll('input[name="video_to"')[1].value;
  } else {
    value_checked = document.querySelectorAll('input[name="video_to"')[0].value;
  }

  if (value_checked == 'to_myself') {
    jQuery('.video-to-info').hide();
    jQuery('input[name=offered_by]').prop('required', false);
  } else {
    jQuery('.video-to-info').show();
    jQuery('input[name=offered_by]').prop('required', true);
  }
}

(function ($) {
  $(document).on('click', '.cart-video-to', verify_checkbox_selected_to_hidde_or_show_fields);

  $(document).ready(function () {
    verify_checkbox_selected_to_hidde_or_show_fields();
    $('.polen-cart-item-data').on('blur change paste click', function () {
      var cart_id = $(this).data('cart-id');
      var item_name = $(this).attr('name');
      var allowed_item = ['offered_by', 'video_to', 'name_to_video', 'email_to_video', 'video_category', 'instructions_to_video', 'allow_video_on_page'];
      if ($.inArray(item_name, allowed_item) !== -1) {
        let item_value;

        if (item_name == 'allow_video_on_page') {
          if ($('#cart_' + item_name + '_' + cart_id).is(':checked')) {
            item_value = 'on';
          } else {
            item_value = 'off';
          }
        } else {
          item_value = $('#cart_' + item_name + '_' + cart_id).val();
        }
        $.ajax({
          type: 'POST',
          url: polenObj.ajax_url,
          data: {
            action: 'polen_update_cart_item',
            security: $('#woocommerce-cart-nonce').val(),
            polen_data_name: item_name,
            polen_data_value: item_value,
            cart_id: cart_id
          },
          success: function (response) {
            //	$('.cart_totals').unblock();
            //$( '.woocommerce-cart-form' ).find( ':input[name="update_cart"]' ).prop( 'disabled', false ).attr( 'aria-disabled', false );
          }
        });
      }
    });

    // Função para assistir as mudanças nas instruções do video
    // e exibir o aviso para não pedir músicas/cantar
    $("textarea[name='instructions_to_video']").on("change keyup", function () {
      var cart_id = $(this).data('cart-id');
      let item_value = $('#cart_instructions_to_video' + '_' + cart_id).val();
      checkWordsInInstructions(item_value);
    });

    $('.select-ocasion').on('change', function () {
      return;
      var item_value = $(this).val();

      if (item_value) {
        $('.video-instruction-refresh').click();
      }
    });

    // Checando se existem palavras proibidas na instrução do video
    function checkWordsInInstructions(instruction) {
      let forbiddenWords = ["música", "Música", "musica", "Musica","canta", "cantar",
      "toca", "tocar", "palinha", "palhinha", "Palinha", "Palhinha", '"'];
      if (forbiddenWords.some(v => instruction.includes(v))) {
        $('#prohibited-instruction-alert').removeClass("d-none");
      } else {
        $('#prohibited-instruction-alert').addClass("d-none");
      }
    }

    function messagesPreloader(active) {
      var loader = document.getElementById("reload");
      loader.classList[active ? "add" : "remove"]("spin");
    }

    $('.video-instruction-refresh').on('click', function () {
      return;
      var category_item = $('select[name="video_category"]');
      var category_name = category_item.val();
      var cart_id = category_item.attr('data-cart-id');

      if (category_name) {
        messagesPreloader(true);
        $.ajax(
          {
            type: 'POST',
            url: polenObj.ajax_url,
            data: {
              action: 'get_occasion_description',
              occasion_type: category_name,
              refresh: 1
            },
            success: function (response) {
              let obj = $.parseJSON(response);
              //console.log(obj['response'][0].description);

              if (obj) {
                if (obj['response'][0].description) {
                  $('#cart_instructions_to_video_' + cart_id).html(obj['response'][0].description);
                }
              }

            },
            complete: function () {
              messagesPreloader(false);
            }
          });
      }
    });

    // Tratando a div que funciona como Placeholder no textarea
    const ta = document.querySelector('textarea[name=instructions_to_video]');
    const pp = document.querySelector('.placeholder');

    ta.addEventListener('input', () => {
      pp.classList.toggle('d-none', ta.value !== '');
    });
  });
})(jQuery);
