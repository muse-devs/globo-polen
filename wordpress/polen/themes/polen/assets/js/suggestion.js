const form = document.querySelector("form#talent-suggestion");

form.addEventListener("submit", function (evt) {
	evt.preventDefault();
	blockUnblockInputs("form#talent-suggestion", true);
	polSpinner();

	jQuery
		.post(
			woocommerce_params.ajax_url,
			jQuery("#form-comment").serialize(),
			function (result) {
				if (result.success) {
					setSessionMessage(
						CONSTANTS.SUCCESS,
						"Sugestão enviada",
						"Obrigado por nos enviar sua sugestão"
					);
					window.location.reload();
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
			blockUnblockInputs("form#talent-suggestion", false);
			polSpinner("hidden");
		});
});
