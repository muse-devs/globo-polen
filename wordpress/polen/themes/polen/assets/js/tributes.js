const btn_play = document.getElementById("btn-play");
const video_tribute = document.getElementById("tribute-home-video");

function playVideo(evt) {
	if (video_tribute.paused) {
		btn_play.classList.add("hidden");
		video_tribute.play();
	} else {
		btn_play.classList.remove("hidden");
		video_tribute.pause();
	}
}

if (btn_play) {
	btn_play.addEventListener("click", playVideo);
}
