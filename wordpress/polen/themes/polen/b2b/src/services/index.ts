export function playVideo(video) {
  const allVideos = document.querySelectorAll("video");
  [].map.call(allVideos, (item) => (!item.paused ? item.pause() : null));

  video.currentTime = 0.1;
  video.play();
}
