const btn_play = document.getElementById("btn-play");
const video_tribute = document.getElementById("tribute-home-video");
const formName = "form#form-create-tribute";
const slug = document.getElementById("slug");

function playVideo(evt) {
	if (video_tribute.paused) {
		btn_play.classList.add("hidden");
		video_tribute.play();
	} else {
		btn_play.classList.remove("hidden");
		video_tribute.pause();
	}
}

function slugValidate(valid, message) {
	const slug_message = document.getElementById("slug-message");
	slug.classList.remove("error");
	slug_message.classList.remove("error");
	slug_message.innerText = "";
	if (!valid) {
		slug.classList.add("error");
		slug_message.classList.add("error");
	}
	slug_message.innerText = message;
}

function checkSlug() {
	polSpinner(CONSTANTS.SHOW, ".slug-wrap");
	jQuery.ajax({
		type: "POST",
		url: polenObj.ajax_url,
		data: {
			action: "check_tribute_slug_exists",
			slug: slug.value,
		},
		success: function (response) {
			if (response.success) {
				slugValidate(true, response.data);
			} else {
				slugValidate(false, response.data);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			slugValidate(false, jqXHR.responseJSON.data);
		},
		complete: function () {
			polSpinner(CONSTANTS.HIDDEN);
		},
	});
}

function createTribute() {
	polSpinner();
	jQuery
		.post(
			polenObj.ajax_url,
			jQuery(formName).serialize(),
			function (result) {
				if (result.success) {
					setSessionMessage(CONSTANTS.SUCCESS, "", "");
					window.location.href = result.redirect;
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
			console.log("complete event");
		});
}

if (btn_play) {
	btn_play.addEventListener("click", playVideo);
}

if (slug) {
	slug.addEventListener("focusout", checkSlug);
}
