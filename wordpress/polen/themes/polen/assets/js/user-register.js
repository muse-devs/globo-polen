"use strict";

function polen_onSubmit(token) {
	const formName = "form.register";
	polRequestZapier(
		formName,
		ZAPIERURLS.NEW_ACCOUNT
	);
	document.querySelector('form.register').submit();
}