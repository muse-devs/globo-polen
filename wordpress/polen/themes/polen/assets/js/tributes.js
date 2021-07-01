const btn_play = document.getElementById("#btn-play");

function playVideo(evt) {
	console.log(evt);
}

if (btn_play) {
	btn_play.addEventListener("click", playVideo);
}
