const btn_play = document.getElementById("btn-play");
const video_tribute = document.getElementById("tribute-home-video");
const formCreateName = "form#form-create-tribute";
const formCreate = document.querySelector(formCreateName);
const slug = document.getElementById("slug");
const nameInput = document.getElementById("name_honored");
const formsResend = document.querySelectorAll(".resend-email");

const SESSION_OBJ_INVITES = "polen_tributes_invites";

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

function getSlug() {
	if (slug.value) {
		return;
	}
	slug.value = polSlugfy(nameInput.value);
	checkSlug();
}

function mascaraData(campo, e) {
	var kC = document.all ? event.keyCode : e.keyCode;
	var data = campo.value;

	if (kC != 8 && kC != 46) {
		if (data.length == 2) {
			campo.value = data += "/";
		} else if (data.length == 5) {
			campo.value = data += "/";
		} else campo.value = data;
	}
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

function createTribute(evt) {
	evt.preventDefault();
	if (slug.classList.contains("error")) {
		polError("É preciso uma URL válida para seu Tributo");
		return;
	}
	polSpinner();
	jQuery
		.post(
			polenObj.ajax_url,
			jQuery(formCreateName).serialize(),
			function (result) {
				if (result.success) {
					setSessionMessage(
						CONSTANTS.SUCCESS,
						"Tributo criado",
						"Agora convide seus amigos para essa homenagem"
					);
					window.location.href = result.data.url_redirect;
				} else {
					polError(result.data);
				}
			}
		)
		.fail(function (e) {
			polSpinner(CONSTANTS.HIDDEN);
			if (e.responseJSON) {
				polError(e.responseJSON.data);
			} else {
				polError(e.statusText);
			}
		});
}

function reSendEmail(evt) {
	evt.preventDefault();
	polAjaxForm(`form#${evt.target.id}`, function(){
		polMessage("Enviado", "e-mail foi enviado com sucesso");
	}, function(res){
		polError(res);
	});
}

if (btn_play) {
	btn_play.addEventListener("click", playVideo);
}

if (slug) {
	slug.addEventListener("focusout", checkSlug);
	nameInput.addEventListener("focusout", getSlug);
}

if (formCreate) {
	formCreate.addEventListener("submit", createTribute);
}

if (formsResend.length) {
	formsResend.forEach(function (item) {
		item.addEventListener("submit", reSendEmail);
	});
}

if (document.getElementById("invite-friends")) {
	function saveToDisk(obj) {
		sessionStorage.setItem(SESSION_OBJ_INVITES, JSON.stringify(obj));
	}

	function getToDisk() {
		const st = sessionStorage.getItem(SESSION_OBJ_INVITES);
		return st ? JSON.parse(st) : [];
	}

	function submitFriends(_this) {
		const formName = "form#friends-form";
		polAjaxForm(
			formName,
			function () {
				sessionStorage.removeItem(SESSION_OBJ_INVITES);
				polMessage(
					"Amigos adicionados com sucesso",
					"Seus amigos receberão as instruções por e-mail"
				);
				_this.friends = [];
			},
			function (e) {
				polError(e);
			}
		);
	}

	const inviteFriends = new Vue({
		el: "#invite-friends",
		data: {
			name: "",
			email: "",
			friends: getToDisk(),
		},
		methods: {
			resetAddFriend: function () {
				this.name = this.email = "";
			},
			updateDisk: function () {
				saveToDisk(this.friends);
			},
			addFriend: function () {
				this.friends.push({ name: this.name, email: this.email });
				this.resetAddFriend();
				this.updateDisk();
				document.getElementById("add-name").focus();
			},
			removeFriend: function (email) {
				this.friends = this.friends.filter(
					(friend) => friend.email != email
				);
				this.updateDisk();
			},
			onChangeEmail: function (evt) {
				if (evt.key == "Enter") {
					this.addFriend();
				}
			},
			sendFriends: function () {
				submitFriends(this);
			},
		},
	});
}
