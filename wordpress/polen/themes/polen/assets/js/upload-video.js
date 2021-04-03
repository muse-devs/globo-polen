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
        upload_video.file_size = file_input.files[0].size.toString();
        jQuery.post(polen_ajax.ajaxurl + '?action=make_video_slot_vimeo', upload_video, (data, textStatus, jqXHR) => {
            if(jqXHR.status == 200) {
                var formData = new FormData();
                formData.append("file_data", file_input.files[0]);
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
                    success: completeHandler,
                    error: errorHandler,
                    complete: completeHandler,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
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