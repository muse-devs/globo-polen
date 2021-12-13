console.log(navigator.mediaDevices);

let video = document.querySelector('#video');
let start = document.querySelector('#start');
let stop = document.querySelector('#stop');
let _console = document.querySelector('#console');
// let mediaRecoder;
let videoURL;
let chunk = [];
if(navigator.mediaDevices) {
    console.log('getUserMedia supported.');

    const options = {mimetype: 'video/webm'}
    const constraint = { audio: true, video: true };
    // const stream = new MediaStream();
    // const mediaRecorder = new MediaRecorder(stream, options);
    // // mediaRecorder.start();
    let onSuccess = function(stream) {
        console.log('será?')
        const mediaRecoder = new MediaRecorder(stream);

        start.onclick = function() {
            // chunk = [];
            mediaRecoder.start();
            video.srcObject = stream;
            _console.innerHTML = 'Gravando...'
        }

        stop.onclick = function() {
            // chunk.push(null);
            mediaRecoder.stop();
            _console.innerHTML = ''
            video.srcObject = null;
            const blob = new Blob(chunk, {type: 'video/webm'});
            videoURL = URL.createObjectURL(blob);
            // const media = HTMLMediaElement.srcObject(url);
            // new HTMLMediaElement().srcObject = blob;
            // video.srcObject = blob;//new MediaSource(chunk);
            video.setAttribute( "src", videoURL );
        }

        mediaRecoder.onstop = function(e) {
            // chunk.push(e.data);
            // donwload();
        }

        mediaRecoder.ondataavailable = function(e) {
            if(e.data && e.data.size > 0) {
                chunk.push(e.data);
            }
        }
    }

    let onError = function(err) {
        console.log(err);
    }
    navigator.mediaDevices.getUserMedia(constraint).then(onSuccess, onError);
} else {
    console.log('getUserMedia not supported.');
}


// function handlerDataAvailable(event) {
//     if(event.data.size > 0) {
//         chunk.push(event.data)
//     }
// }


// let onSuccess = function(stream) {
//     const mediaRecorder = new MediaRecorder(stream);
// }

function donwload() {
    let blob = new Blob(chunk, {type: 'video/webm'});
    let url = URL.createObjectURL(blob);
    // chunk = [];
    let a = document.createElement('a');
    document.body.appendChild(a);
    a.style = 'display: none';
    a.href = url;
    a.download = 'test.webm';
    a.click();
    window.URL.revokeObjectURL(url);
}