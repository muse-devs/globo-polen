var modal = document.getElementById("video-modal");
var video_box = document.getElementById("video-box");
var share_button = document.getElementById("share-button");

jQuery(document).ready(function () {
	jQuery(".talent-carousel").slick({
		infinite: true,
		speed: 300,
		slidesToShow: 1,
		variableWidth: true,
	});
	var id = window.location.hash.substring(1);
	if (id) {
		openVideoById(id);
	}
});

share_button.addEventListener("click", async () => {
	var shareData = {
		title: "",
		text: "",
		url: "",
	};
	if (navigator.share) {
		try {
			await navigator.share(shareData);
		} catch (err) {
			alert("Error: " + err);
		}
	}
});

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
	modal.classList.add("show");
	video_box.classList.add("show");
}

function hideModal(e) {
	changeHash();
	killVideo();
	modal.classList.remove("show");
	video_box.classList.remove("show");
}

function openVideoByURL(url) {
	addVideo();
	showModal();
	var videoPlayer = new Vimeo.Player("polen-video", {
		url: url,
	});
	videoPlayer.getVideoId().then(function (id) {
		console.log("id:", id);
		changeHash(id);
	});
}

function openVideoById(id) {
	addVideo();
	showModal();
	var videoPlayer = new Vimeo.Player("polen-video", {
		id: id,
	});
	changeHash(id);
}
