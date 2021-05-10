function copyToClipboard(text) {
	var copyText = document.getElementById("share-input");
	copyText.value = text;
	copyText.select();
	copyText.setSelectionRange(0, 99999); /* For mobile devices */

	document.execCommand("copy");
	alert("Link copiado para Área de transferência");
}

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(";");
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == " ") {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function changeHash(hash) {
	window.location.hash = hash || "";
}

function setImediate(handle) {
	setTimeout(handle, 1);
}

function polMessageKill(id) {
	var el = document.getElementById(id);
	if (el) {
		el.classList.remove("show");
		setImediate(function () {
			el.parentNode.removeChild(el);
		});
	}
}

function polSpinner(action, el) {
	if (action === "hidden") {
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
		if (el) {
			container = document.querySelector(el);
			fog.classList.add("inner");
		} else {
			container = document.body;
		}
		container.appendChild(fog);
		setImediate(function () {
			fog.classList.add("show");
		});
	}
}

function polMessage(title, message) {
	var id = "message-box";
	polMessageKill(id);

	var messageBox = document.createElement("div");
	messageBox.id = id;
	messageBox.classList.add(id);
	messageBox.classList.add("success");
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
		messageBox.classList.add("show");
	});
}

function polError(message) {
	var id = "message-box";
	polMessageKill(id);

	var messageBox = document.createElement("div");
	messageBox.id = id;
	messageBox.classList.add(id);
	messageBox.classList.add("fail");
	messageBox.innerHTML = `
	<i class="icon icon-error-o" style="color: var(--danger);"></i>
	<p class="message-text px-1">${message}</p>
	<button class="message-close" onclick="polMessageKill('${id}')">
		<i class="icon icon-close"></i>
	</button>
	`;
	document.body.appendChild(messageBox);
	setImediate(function () {
		messageBox.classList.add("show");
	});
}

function truncatedItems() {
	const ps = document.querySelectorAll(".truncate");
	const observer = new ResizeObserver((entries) => {
		for (let entry of entries) {
			entry.target.classList[
				entry.target.scrollHeight > entry.contentRect.height
					? "add"
					: "remove"
			]("truncated");
		}
	});

	ps.forEach((p) => {
		observer.observe(p);
	});
}

jQuery(document).ready(function () {
	truncatedItems();
});

(function ($) {
	$(document).on("click", ".signin-newsletter-button", function (e) {
		e.preventDefault();
		var email = $('input[name="signin_newsletter"]').val();
		var wnonce = $(this).attr("code");

		if (email !== "") {
			$.ajax({
				type: "POST",
				url: woocommerce_params.ajax_url,
				data: {
					action: "polen_newsletter_signin",
					security: wnonce,
					email: email,
				},
				success: function (response) {
					console.log(response);
					let obj = $.parseJSON(response);
					$(".signin-response").html(obj["response"]);
				},
			});
		} else {
			$(".signin-response").html("Favor digite um e-mail válido");
		}
	});
})(jQuery);
