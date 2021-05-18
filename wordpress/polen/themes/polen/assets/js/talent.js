var modal = document.getElementById("video-modal");
var video_box = document.getElementById("video-box");
var share_button = document.querySelectorAll(".share-button");
var talent_videos = document.getElementById("talent-videos");
var public_url = talent_videos ? talent_videos.getAttribute("data-public-url") : "";
let get_your_video_banner = document.getElementsByClassName("btn btn-outline-light btn-lg")[0];

jQuery(document).ready(function () {
	get_your_video_banner.addEventListener('click', evt => {
		evt.preventDefault();
		jQuery('.single_add_to_cart_button')[0].click();
	});
	jQuery(".banner-content.type-video").slick({
		arrows: true,
		appendArrows: jQuery(".custom-slick-controls"),
		infinite: false,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 3,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 1,
				},
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
				},
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
				},
			},
			{
				breakpoint: 576,
				settings: "unslick",
			},
		],
	});

	var id = getVideoId();
	if (id) {
		openVideoById(id);
	}

	if (share_button.length > 0) {
		share_button.forEach(function (btn) {
			btn.addEventListener("click", shareVideo);
		});
	}
});

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

function getVideoId() {
	return window.location.hash.substring(1);
}

function changeVideoCardUrl(id) {
	var url = public_url + id;
	var el_url = document.getElementById("video-url");
	if (!el_url) {
		return;
	}
	el_url.setAttribute("href", url);
	el_url.innerText = url;
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

function handleCopyVideoUrl(id) {
	var btn_copy = document.getElementById('copy-video');
	btn_copy.addEventListener('click', function() {
		copyToClipboard(public_url + id);
	});
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
		changeVideoCardUrl(id);
		// handleCopyVideoUrl(id);
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
	changeVideoCardUrl(id);
	// handleCopyVideoUrl(id);
}
