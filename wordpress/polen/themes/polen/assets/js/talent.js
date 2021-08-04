var modal = document.getElementById("video-modal");
var video_box = document.getElementById("video-box");
var share_button = document.querySelectorAll(".share-button");
var talent_videos = document.getElementById("talent-videos");
var public_url = talent_videos
	? talent_videos.getAttribute("data-public-url")
	: "";

var timestamp = function() {
	var timeIndex = 0;
	var shifts = [35, 60, 60 * 3, 60 * 60 * 2, 60 * 60 * 25, 60 * 60 * 24 * 4, 60 * 60 * 24 * 10];

	var now = new Date();
	var shift = shifts[timeIndex++] || 0;
	var date = new Date(now - shift * 1000);

	return date.getTime() / 1000;
};

function generateStoriesArray(videos, category) {
	const array = [];
	videos.map((item) => {
		array.push([item.hash, "video", 0, item.video, '','javascript:clickToBuy()', category ? 'Doe Agora' : "Peça Agora", false, timestamp()]);
	});
	return array;
}

function renderStories(videos, name, avatar, category) {
	let stories = new Zuck('stories', {
		backNative: true,
		previousTap: true,
		backButton: true,
		skin: 'Snapgram',
		avatars: true,
		paginationArrows: false,
		list: false,
		cubeEffect: false,
		localStorage: true,
		language: {
			unmute: 'Toque para ouvir',
			keyboardTip: 'Clique para ver o próximo',
			visitLink: 'Visite o Link',
			time: {
				ago:'atrás',
				hour:'hora',
				hours:'horas',
				minute:'minuto',
				minutes:'minutos',
				fromnow: 'from now',
				seconds:'segundos',
				yesterday: 'ontem',
				tomorrow: 'amanhã',
				days:'dias'
			}
		},
		stories: [
			Zuck.buildTimelineItem(
				videos.length > 0 ? "1" : null,
				avatar,
				name,
				"",
				timestamp(),
				generateStoriesArray(videos, category)
			),
		],
	});
	// Customizando a lib quando nao houver vídeos
	if (videos.length === 0) {
		document.getElementById("stories").setAttribute("id", "");
		let story = document.getElementsByClassName("story");
		story[0].classList.add("seen");
		let link = document.getElementsByClassName("item-link");
		link[0].classList.add("no-link");
	}
}

jQuery(document).ready(function () {
	var id = getVideoId();
	if (id) {
		openVideoByHash(id);
	}

	if (share_button.length > 0) {
		share_button.forEach(function (btn) {
			btn.addEventListener("click", shareVideo);
		});
	}
});

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
	// killVideo();
	modal.classList.remove("show");
	video_box.innerHTML = "";
}

function handleCopyVideoUrl(id) {
	var btn_copy = document.getElementById("copy-video");
	btn_copy.addEventListener("click", function () {
		copyToClipboard(public_url + id);
	});
}

function openVideoByURL(url) {
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

function openVideoByHash(hash) {
	video_box.innerHTML = "";
	polSpinner(null, "#video-box");
	var product_id = document.getElementById("product_id").value;
	const url = `${polenObj.ajax_url}?action=draw-player-modal&hash=${hash}&product_id=${product_id}`;
	showModal();
	changeHash(hash);
	jQuery(video_box).load(url);
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

function clickToBuy() {
	document.querySelector(".single_add_to_cart_button").click();
}
