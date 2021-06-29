const formName = "form#talent-suggestion";
const form = document.querySelector(formName);

form.addEventListener("submit", function (evt) {
	evt.preventDefault();
	blockUnblockInputs(formName, true);
	polSpinner();

	jQuery
		.post(
			woocommerce_params.ajax_url,
			jQuery(formName).serialize(),
			function (result) {
				if (result.success) {
					setSessionMessage(
						CONSTANTS.SUCCESS,
						"Sugestão enviada",
						"Obrigado por nos enviar sua sugestão"
					);
					form.reset();
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
			blockUnblockInputs(formName, false);
			polSpinner("hidden");
		});
});
