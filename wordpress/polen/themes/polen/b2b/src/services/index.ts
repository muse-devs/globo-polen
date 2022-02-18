import axios from "axios";
import { ContactB2B } from "interfaces";

export function playVideo(video) {
  const allVideos = document.querySelectorAll("video");
  [].map.call(allVideos, (item) => (!item.paused ? item.pause() : null));

  video.currentTime = 0.1;
  video.play();
}

const polApi = axios.create({
  baseURL: process.env.BASE_URL,
});

export async function getNonce() {
  try {
    const res = await polApi.get(`/contact`);

    return res.data;
  } catch (err) {
    throw err.response;
  }
}

export async function getB2BTalents(categories: Array<String> = []) {
  try {
    const res = await polApi.get(`/b2b/talents?categories=${categories}&limit=100`);

    return res.data;
  } catch (err) {
    throw err.response;
  }
}

export async function contactFormB2B(params: ContactB2B) {
  try {
    const res = await polApi.post(`/b2b/contact`, params);

    return res.data;
  } catch (err) {
    throw err.response;
  }
}
