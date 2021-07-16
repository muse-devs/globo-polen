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

function checkSlug() {
	polSpinner(CONSTANTS.SHOW, ".slug-wrap");
	slug.value = polSlugfy(slug.value);
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
		polError("É preciso uma URL válida para seu Colab");
		return;
	}
	if (document.getElementById("deadline").classList.contains("error")) {
		polError("Data inválida. A data precisa ser futura.");
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
						"Colab criado",
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
	polAjaxForm(
		`form#${evt.target.id}`,
		function () {
			polMessage("Enviado", "e-mail foi enviado com sucesso");
		},
		function (res) {
			polError(res);
		}
	);
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
				setSessionMessage(
					CONSTANTS.SUCCESS,
					"Amigos adicionados com sucesso",
					"Seus amigos receberão as instruções por e-mail"
				);
				_this.friends = [];
				window.location.href = "./detalhes";
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

if (document.getElementById("deadline-wrapp")) {
	const formValidate = new Vue({
		el: "#deadline-wrapp",
		data: {
			date: "",
		},
		methods: {
			maskDate: function (evt) {
				if (/\D/.test(evt.key)) {
					this.date = this.date.replace(evt.key, "");
					return;
				}
				if (this.date.length == 2) {
					this.date = this.date += "/";
				} else if (this.date.length == 5) {
					this.date = this.date += "/";
				}
			},
			checkDate: function (evt) {
				evt.target.classList.remove("error");

				const d = new Date();

				const df = evt.target.value.split("/");
				const d2 = new Date(df[2], df[1] - 1, df[0]);

				const t1 = d.getTime();
				const t2 = d2.getTime();

				if (!t2 || t2 < t1) {
					evt.target.classList.add("error");
				}
			},
		},
	});
}

if (document.querySelectorAll(".tribute_delete_invite").length) {
	jQuery(".tribute_delete_invite").click(function (evt) {
		evt.preventDefault();
		polSpinner();
		let invite_hash = jQuery(evt.currentTarget).attr("data_invite");
		let tribute_hash = jQuery(evt.currentTarget).attr("data_tribute");
		let action = "tribute_delete_invite";
		let security = jQuery("#wpnonce").val();
		jQuery
			.post(
				polenObj.ajax_url,
				{ action, invite_hash, tribute_hash, security },
				function (data, status, b) {
					if (data.success) {
						setSessionMessage(CONSTANTS.SUCCESS, "Sucesso", data.data);
						document.location.reload();
					} else {
						polError(data.data);
					}
				}
			)
			.fail(function (jqxhr, settings, ex) {
				polSpinner(CONSTANTS.HIDDEN);
				polError("failed, " + ex);
			});
	});
}
