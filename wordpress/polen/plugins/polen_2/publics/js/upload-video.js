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
//        upload_video.action = 'make_video_slot_vimeo';
        upload_video.file_size = file_input.files[0].size.toString();
        jQuery.post(polen_ajax.ajaxurl + '?action=make_video_slot_vimeo', upload_video, (data, textStatus, jqXHR) => {
            console.log(data, textStatus, jqXHR);
            if(jqXHR.status == 200) {
//                form.action = data.data.body.upload.upload_link;
//                form.submit();
                var formData = new FormData();
//                formData.append("file", this.file, this.getName());
                formData.append("file_data", file_input.files[0]);
//                let oReq = new XMLHttpRequest();

//                oReq.addEventListener("progress", updateProgress, false);
//                oReq.addEventListener("load", transferComplete, false);
//                oReq.addEventListener("error", transferFailed, false);
//                oReq.addEventListener("abort", transferCanceled, false);

//                oReq.open('post', data.data.body.upload.upload_link, true);
//                oReq.setRequestHeader('Access-Control-Allow-Origin', 'polen.globo');
//                oReq.send(formData);
console.log('ajdshfgakjdhfg');
                jQuery.ajax({
                    url: data.data.body.upload.upload_link,
                    type: 'POST',
                    xhr: function() {
                        var myXhr = jQuery.ajaxSettings.xhr();
                        if(myXhr.upload){
                            myXhr.upload.addEventListener('progress',progressFunction, false);
                        }
                        return myXhr;
                    },
//                    beforeSend: beforeSendHandler,
                    success: completeHandler,
                    error: errorHandler,
                    complete: completeHandler,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
//                    type: 'post',
//                    data: formData,
//                    contentType: false,
//                    processData: false,
//                    success: function(response){
//                       if(response != 0){
//                          console.log(response);
//                       }else{
//                          alert('file not uploaded');
//                       }
//                    },
//                    crossDomain: true,
//                    dataType: 'json',
//                    method: 'POST',
//                    contentType: 'multipart/form-data',
//                    url: data.data.body.upload.upload_link,
//                    data: formData,
//                    complete: (jqXHR, textStatus) => {
//                        console.log(textStatus);
//                    },
//                    error: ( jqXHR, textStatus, errorThrown) => {
//                        console.log(textStatus);
//                    },
//                    success: (data, textStatus, jqXHR) => {
//                        console.log(textStatus);
//                    }
//                    type: "POST",
//                    url: data.data.body.upload.upload_link,
//                    xhr: (evt) => {
//                        console.log(evt.lengthComputable)
//                    },
//                    success: () => {
//                        
//                    },
//                    error: () => {
//                        
//                    },
//                    async: true,
//                    data: formData,
//                    cache: false,
//                    contentType: false,
//                    processData: false
                });
            }
        });
        evt.preventDefault();
    }
}
 let updateProgress = (evt) => {
     console.log(evt.lengthComputable)
 }
 let completeHandler = (evt) => {
     console.log('complete')
 }
 let errorHandler = (jqXHR, textStatus, errorThrown) => {
     console.log('error', jqXHR, textStatus, errorThrown)
 }
 let transferCanceled = (evt) => {
     console.log('cancelado')
 }
 
 function progressFunction(e){
    if(e.lengthComputable){
        console.log({value:e.loaded,max:e.total});
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