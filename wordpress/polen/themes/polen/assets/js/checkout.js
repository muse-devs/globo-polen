jQuery(document).ready(function () {
	jQuery("form.checkout").on("submit", function () {
		blockUnblockInputs("form.checkout", true);
	});
	jQuery("body").on("checkout_error", function () {
		blockUnblockInputs("form.checkout", false);
	});
});
