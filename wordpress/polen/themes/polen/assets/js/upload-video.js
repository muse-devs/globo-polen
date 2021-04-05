let form = document.querySelector("#form-video-upload");
let file_input = document.querySelector("#file-video");
let progress = document.querySelector("#progress");
let content_info = document.getElementById("content-info");
let content_upload = document.getElementById("content-upload");
let progress_value = document.getElementById("progress-value");
let video_input = document.getElementById("file-video");
let video_name = document.getElementById("video-file-name");
let video_rec_click = document.querySelectorAll(".video-rec");
let response;

window.onload = () => {
	form.onsubmit = function (evt) {
		if (file_input.files.length == 0) {
			evt.preventDefault();
			return false;
		}
		console.log("Iniciando upload");

		content_info.classList.remove("show");
		content_upload.classList.add("show");
		document.querySelector("#video-rec-again").classList.remove("show");
		document.querySelector("#video-send").classList.remove("show");

		upload_video.file_size = file_input.files[0].size.toString();
		jQuery.post(
			polen_ajax.ajaxurl + "?action=make_video_slot_vimeo",
			upload_video,
			(data, textStatus, jqXHR) => {
				if (jqXHR.status == 200) {
					var formData = new FormData();
					formData.append("file_data", file_input.files[0]);
					jQuery.ajax({
						url: data.data.body.upload.upload_link,
						type: "POST",
						xhr: function () {
							var myXhr = jQuery.ajaxSettings.xhr();
							if (myXhr.upload) {
								myXhr.upload.addEventListener(
									"progress",
									progressFunction,
									false
								);
							}
							return myXhr;
						},
						success: completeHandler,
						error: errorHandler,
						complete: completeHandler,
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
					});
				}
			}
		);
		evt.preventDefault();
		return false;
	};

	video_rec_click.forEach(function (item) {
		item.addEventListener("click", function (e) {
			e.preventDefault();
			video_input.click();
		});
	});

	video_input.addEventListener("change", function (e) {
		changeText();
		document.querySelector("#video-rec").classList.remove("show");
		document.querySelector("#video-rec-again").classList.add("show");
		document.querySelector("#video-send").classList.add("show");
	});
};
let updateProgress = (evt) => {
	console.log(evt.lengthComputable);
};
let completeHandler = (evt) => {
	console.log("complete");
	content_upload.innerHTML =
		'<p class="my-4"><strong id="progress-value">Enviado</strong></p>';
	let obj_complete_order = {
		action: "order_status_completed",
		order: upload_video.order_id,
	};
	jQuery
		.post(
			polen_ajax.ajaxurl,
			obj_complete_order,
			(data, textStatus, jqXHR) => {
				alert("Video enviado com sucesso");
				window.location.href = museobj.base_url + "/my-account/orders/";
			}
		)
		.fail(function () {
			alert("Ocorreu um erro, envie o video novamente");
		});
};
let errorHandler = (jqXHR, textStatus, errorThrown) => {
	console.log("error", jqXHR, textStatus, errorThrown);
};
let transferCanceled = (evt) => {
	console.log("cancelado");
};

function progressFunction(e) {
	if (e.lengthComputable) {
		progress_value.innerText = `Enviando vídeo ${Math.floor(
			(e.loaded / e.total) * 100
		)}%`;
	}
}

function changeText() {
	document.getElementById("info").innerText = "Vídeo gravado com sucesso";
}

serialize = function (obj, prefix) {
	var str = [],
		p;
	for (p in obj) {
		if (obj.hasOwnProperty(p)) {
			var k = prefix ? prefix + "[" + p + "]" : p,
				v = obj[p];
			str.push(
				v !== null && typeof v === "object"
					? serialize(v, k)
					: encodeURIComponent(k) + "=" + encodeURIComponent(v)
			);
		}
	}
	return str.join("&");
};

function getFullPath(str) {
	if (str) {
		var startIndex =
			str.indexOf("\\") >= 0
				? str.lastIndexOf("\\")
				: str.lastIndexOf("/");
		var filename = str.substring(startIndex);
		if (filename.indexOf("\\") === 0 || filename.indexOf("/") === 0) {
			filename = filename.substring(1);
		}
		return filename;
	}
	return false;
}
