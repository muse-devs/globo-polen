const CONSTANTS = {
	MESSAGE_COOKIE: "message_cookie",
	SUCCESS: "success",
	ERROR: "error",
	SHOW: "show",
	HIDDEN: "hidden",
	MESSAGE_TIME: 5,
	THEME: "theme_mode",
};

var interval = setInterval;

function docReady(fn) {
	// see if DOM is already available
	if (
		document.readyState === "complete" ||
		document.readyState === "interactive"
	) {
		// call on next available tick
		setTimeout(fn, 1);
	} else {
		document.addEventListener("DOMContentLoaded", fn);
	}
}

function copyToClipboard(text) {
	var copyText = document.createElement("input");
	copyText.id = "share-input";
	copyText.style = "position: fixed; top: 500vh";
	document.body.appendChild(copyText);
	copyText.value = text;
	copyText.select();
	copyText.setSelectionRange(0, 99999); /* For mobile devices */

	document.execCommand("copy");
	document.body.removeChild(copyText);
	polMessage("Sucesso", "Link copiado para Área de transferência");
}

function shareVideo(title, url) {
	var shareData = {
		title: title,
		url: url,
	};
	if (navigator.share) {
		try {
			navigator
				.share(shareData)
				.then(() => {
					console.log("Sucesso!", "Link compartilhado com sucesso");
				})
				.catch(console.error);
		} catch (err) {
			polError("Error: " + err);
		}
	} else {
		copyToClipboard(shareData.url);
	}
}

function changeHash(hash) {
	window.location.hash = hash || "";
}

function setImediate(handle) {
	setTimeout(handle, 1);
}

function polMessageKill(id) {
	clearInterval(interval);
	var el = document.getElementById(id);
	if (el) {
		el.classList.remove(CONSTANTS.SHOW);
		setImediate(function () {
			el.parentNode.removeChild(el);
		});
	}
}

function polMessageAutoKill(id) {
	interval = setInterval(function () {
		polMessageKill(id);
	}, CONSTANTS.MESSAGE_TIME * 1000);
}

function polSpinner(action, el) {
	if (action === CONSTANTS.HIDDEN) {
		polMessageKill("pol-fog");
	} else {
		polMessageKill("pol-fog");
		var container = null;
		var fog = document.createElement("div");
		fog.id = "pol-fog";
		fog.classList.add("fog");
		fog.innerHTML = `
			<div class="spinner">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Aguarde...</span>
				</div>
			</div>
		`;

		container = document.querySelector(el);
		if (container) {
			fog.classList.add("inner");
		} else {
			container = document.body;
		}
		container.appendChild(fog);
		setImediate(function () {
			fog.classList.add(CONSTANTS.SHOW);
		});
	}
}

const polMessages = {
	message: function (title, message) {
		polMessage(title, message);
	},
	error: function (message) {
		polError(message);
	},
};

function polMessage(title, message) {
	var id = "message-box";
	polMessageKill(id);

	var messageBox = document.createElement("div");
	messageBox.id = id;
	messageBox.classList.add(id);
	messageBox.classList.add(CONSTANTS.SUCCESS);
	messageBox.innerHTML = `
	<div class="row">
		<div class="col-md-12">
			<i class="bi bi-check-circle" style="color: var(--success)"></i>
		</div>
		<div class="col-md-12">
			<h4 class="message-title">${title}</h4>
			<p class="message-text mt-1">${message}</p>
		</div>
	</div>
	<button class="message-close" onclick="polMessageKill('${id}')">
		<i class="icon icon-close"></i>
	</button>
	`;
	document.body.appendChild(messageBox);
	setImediate(function () {
		messageBox.classList.add(CONSTANTS.SHOW);
		polMessageAutoKill(id);
	});
}

function polError(message) {
	var id = "message-box";
	polMessageKill(id);

	var messageBox = document.createElement("div");
	messageBox.id = id;
	messageBox.classList.add(id);
	messageBox.classList.add(CONSTANTS.ERROR);
	messageBox.innerHTML = `
	<i class="icon icon-error-o" style="color: var(--danger);"></i>
	<p class="message-text px-1">${message}</p>
	<button class="message-close" onclick="polMessageKill('${id}')">
		<i class="icon icon-close"></i>
	</button>
	`;
	document.body.appendChild(messageBox);
	setImediate(function () {
		messageBox.classList.add(CONSTANTS.SHOW);
		polMessageAutoKill(id);
	});
}

function truncatedItems() {
	const ps = document.querySelectorAll(".truncate");
	if (ps.length < 1) {
		return;
	}
	const observer = new ResizeObserver((entries) => {
		for (let entry of entries) {
			entry.target.classList[
				entry.target.scrollHeight > entry.contentRect.height + 1
					? "add"
					: "remove"
			]("truncated");
		}
	});

	ps.forEach((p) => {
		observer.observe(p);
	});
}

// Mensagens globais via cookie ----------------------------------------
//type: success || error
//title: only in success
function setSessionMessage(
	type = CONSTANTS.SUCCESS,
	title = "Obrigado!",
	message
) {
	sessionStorage.setItem(
		CONSTANTS.MESSAGE_COOKIE,
		JSON.stringify({ type, title, message })
	);
}

function getSessionMessage() {
	var ck = sessionStorage.getItem(CONSTANTS.MESSAGE_COOKIE);
	if (!ck) {
		return;
	}
	var content = JSON.parse(ck);
	if (content.type === CONSTANTS.SUCCESS) {
		polMessage(content.title, content.message);
	} else if (content.type === CONSTANTS.ERROR) {
		polError(content.message);
	}
	sessionStorage.removeItem(CONSTANTS.MESSAGE_COOKIE);
}

function blockUnblockInputs(el, block) {
	const allEl = document.querySelectorAll(
		`${el} input, ${el} select, ${el} textarea`
	);
	allEl.forEach(function (element, key, parent) {
		block
			? element.setAttribute("readonly", block)
			: element.removeAttribute("readonly");
	});
	console.log("blocked inputs", block);
}

// -----------------------------------------------------------------------

// ----------------------------
// Handler do Download do Video
function downloadClick_handler(evt) {
	evt.preventDefault();
	let hash = jQuery(evt.currentTarget).attr("data-download");
	let security = jQuery(evt.currentTarget).attr("data-nonce");
	let action = "video-download-link";
	let data = { hash, security, action };
	jQuery.post(woocommerce_params.ajax_url, data, (response) => {
		if (response.success) {
			window.location.href = response.data;
		}
	});
}
// ---------------------------

// Analytics ----------------------------------
const GA_EVENTS = {
	PURCHASE: "purchase",
};

function polenGA(type, value) {
	gtag("event", type, value);

	// gtag("event", "purchase", {
	// 	transaction_id: "24.031608523954162",
	// 	affiliation: "Google online store",
	// 	value: 23.07,
	// 	currency: "USD",
	// 	tax: 1.24,
	// 	shipping: 0,
	// 	items: [
	// 		{
	// 			id: "P12345",
	// 			name: "Android Warhol T-Shirt",
	// 			list_name: "Search Results",
	// 			brand: "Google",
	// 			category: "Apparel/T-Shirts",
	// 			variant: "Black",
	// 			list_position: 1,
	// 			quantity: 2,
	// 			price: "2.0",
	// 		},
	// 	],
	// });
}
// --------------------------------------------

jQuery(document).ready(function () {
	truncatedItems();
	getSessionMessage();
});

(function ($) {
	$(document).on("click", ".signin-newsletter-button", function (e) {
		e.preventDefault();
		var email = $('input[name="signin_newsletter"]');
		var page_source = $('input[name="signin_newsletter_page_source"]');
		var event = $('input[name="signin_newsletter_event"]');
		var is_mobile = $('input[name="signin_newsletter_is_mobile"]');
		var wnonce = $(this).attr("code");
		$(".signin-response").html("");
		if (email.val() !== "") {
			polSpinner(CONSTANTS.SHOW, "#signin-newsletter");
			$.ajax({
				type: "POST",
				url: woocommerce_params.ajax_url,
				data: {
					action: "polen_newsletter_signin",
					security: wnonce,
					email: email.val(),
					page_source: page_source.val(),
					event: event.val(),
					is_mobile: is_mobile.val(),
				},
				success: function (response) {
					polMessage(
						"Seu email foi adicionado à lista",
						response.data.response
					);
					email.val("");
				},
				complete: function () {
					polSpinner(CONSTANTS.HIDDEN);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					polError(`Erro: ${jqXHR.responseJSON.data.response}`);
				},
			});
		} else {
			polError("Por favor, digite um e-mail válido");
		}
	});
})(jQuery);
