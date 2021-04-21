var modal = document.getElementById("video-modal");
var video_box = document.getElementById("video-box");
var share_button = document.querySelectorAll(".share-button");

jQuery(document).ready(function () {
	jQuery(".banner-content.type-video").slick({
		arrows: true,
		appendArrows: jQuery(".custom-slick-controls"),
		infinite: false,
		speed: 300,
		slidesToShow: 6,
		slidesToScroll: 3,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 5,
					slidesToScroll: 1,
				},
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
				},
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
				},
			},
			{
				breakpoint: 480,
				settings: "unslick",
			},
		],
	});
	var id = window.location.hash.substring(1);
	if (id) {
		openVideoById(id);
	}

	if (share_button.length > 0) {
		share_button.forEach(function (btn) {
			btn.addEventListener("click", shareVideo);
		});
	}
});

function copyToClipboard(text) {
	var copyText = document.getElementById("share-input");
	copyText.value = text;
	copyText.select();
	copyText.setSelectionRange(0, 99999); /* For mobile devices */

	document.execCommand("copy");
	alert("Link copiado para Área de transferência");
}

async function shareVideo(title = "Nome do talento", text = "texto do vídeo") {
	var shareData = {
		title: title,
		text: text,
		url: window.location.href,
	};
	if (navigator.share) {
		try {
			await navigator.share(shareData);
		} catch (err) {
			alert("Error: " + err);
		}
	} else {
		copyToClipboard(shareData.url);
		console.log("URL: " + shareData.url);
	}
}

function changeHash(hash) {
	window.location.hash = hash || "";
}

function addVideo() {
	var div = document.createElement("DIV");
	div.id = "polen-video";
	div.className = "polen-video";
	video_box.appendChild(div);
}

function killVideo() {
	var video = document.getElementById("polen-video");
	video.parentNode.removeChild(video);
}

function showModal() {
	document.body.classList.add("no-scroll");
	modal.classList.add("show");
}

function hideModal(e) {
	document.body.classList.remove("no-scroll");
	changeHash();
	killVideo();
	modal.classList.remove("show");
}

function openVideoByURL(url) {
	console.log(url);
	addVideo();
	showModal();
	var videoPlayer = new Vimeo.Player("polen-video", {
		url: url,
		autoplay: true,
		width: document.getElementById("polen-video").offsetWidth,
	});
	videoPlayer.getVideoId().then(function (id) {
		changeHash(id);
	});
}

function openVideoById(id) {
	addVideo();
	showModal();
	var videoPlayer = new Vimeo.Player("polen-video", {
		id: id,
		autoplay: true,
		width: document.getElementById("polen-video").offsetWidth,
	});
	changeHash(id);
}
