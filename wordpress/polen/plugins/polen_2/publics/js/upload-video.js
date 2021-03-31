let form = document.querySelector('#form-video-upload');
let file_input = document.querySelector('#file-video');
let response;

window.onload = () => {
    form.onsubmit = function(evt) {
        if(file_input.files.length == 0) {
            evt.preventDefault();
            console.log('kd o arquivo meu querido?');
            return false;
        }
        console.log('mais foi');
//        const xhttp = new XMLHttpRequest();
//        xhttp.onreadystatechange = (evt) => {
//            const request = evt.currentTarget;
//            if(request.readyState == 4 && request.status == 200) {
//                console.log(request)
//                response = JSON.parse(request.responseText);
//                form.action = response.body.upload.upload_link;
                // new_form_div.innerHTML = response.body.upload.form;
//                form.submit();
//            }
//        }
//        let video_params = encodeURIComponent(url);
//        xhttp.open('POST', polen_ajax.ajaxurl, true);
//        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        upload_video.action = 'make_video_slot_vimeo';
        upload_video.file_size = file_input.files[0].size.toString();
//        console.log(upload_video, upload_video);
//        xhttp.send(serialize(upload_video));
        jQuery.post(polen_ajax.ajaxurl + '?action=create_video_slot_vimeo', upload_video, (data, textStatus, jqXHR) => {
            console.log(data, textStatus, jqXHR);
        });
        evt.preventDefault();
    }
}

serialize = function(obj, prefix) {
  var str = [],
    p;
  for (p in obj) {
    if (obj.hasOwnProperty(p)) {
      var k = prefix ? prefix + "[" + p + "]" : p,
        v = obj[p];
      str.push((v !== null && typeof v === "object") ?
        serialize(v, k) :
        encodeURIComponent(k) + "=" + encodeURIComponent(v));
    }
  }
  return str.join("&");
}