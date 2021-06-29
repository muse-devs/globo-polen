const formName = "form#landpage-form";
const form_landpage = document.querySelector(formName);

form_landpage.addEventListener("submit", function (evt) {
	evt.preventDefault();
	polSpinner();
	jQuery
		.post(
			woocommerce_params.ajax_url,
			jQuery(formName).serialize(),
			function (result) {
				if (result.success) {
					polMessages.message(
						CONSTANTS.SUCCESS,
						"Enviado com sucesso",
						"Seu cadastro foi efetuado com sucesso"
					);
					form_landpage.reset();
				} else {
					polError(result.data);
				}
			}
		)
		.fail(function (e) {
			if (e.responseJSON) {
				polError(e.responseJSON.data);
			} else {
				polError(e.statusText);
			}
		})
		.complete(function (e) {
			polSpinner("hidden");
		});
});
