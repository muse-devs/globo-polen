let response,form=document.querySelector("#form-video-upload"),file_input=document.querySelector("#file-video"),progress=document.querySelector("#progress"),content_info=document.getElementById("content-info"),content_upload=document.getElementById("content-upload"),progress_value=document.getElementById("progress-value"),video_input=document.getElementById("file-video"),video_name=document.getElementById("video-file-name"),video_rec_click=document.querySelectorAll(".video-rec");const duracaoMinima=10,duracaoMaxima=65;let videoDuration;function setFileInfo(){var e=file_input.files,o=document.createElement("video");o.preload="metadata",o.onloadedmetadata=function(){window.URL.revokeObjectURL(o.src);var e=o.duration;videoDuration=e,console.log(videoIsOk()?"Duração Ok":"Duração Errada")},o.src=URL.createObjectURL(e[0])}function videoIsOk(){return videoDuration>10&&videoDuration<65}window.URL=window.URL||window.webkitURL,window.onload=()=>{form.onsubmit=function(e){return 0==file_input.files.length?(e.preventDefault(),!1):videoIsOk()?(console.log("Iniciando upload"),content_info.classList.remove("show"),content_upload.classList.add("show"),document.querySelector("#video-rec-again").classList.remove("show"),document.querySelector("#video-send").classList.remove("show"),upload_video.file_size=file_input.files[0].size.toString(),jQuery.post(woocommerce_params.ajax_url+"?action=make_video_slot_vimeo",upload_video,(e,o,n)=>{if(200==n.status){let o=file_input.files[0];new tus.Upload(o,{uploadUrl:e.data.body.upload.upload_link,onError:errorHandler,onProgress:progressFunction,onSuccess:completeHandler}).start()}else console.log("deu error")}).fail(errorHandler),e.preventDefault(),!1):(e.preventDefault(),polError("A duração do video deve ficar entre 10 e 60 segundos."),!1)},video_rec_click.forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),video_input.click()}))})),video_input.addEventListener("change",(function(e){setFileInfo(),changeText(),document.querySelector("#video-rec").classList.remove("show"),document.querySelector("#video-rec-again").classList.add("show"),document.querySelector("#video-send").classList.add("show")}))};let completeHandler=()=>{content_upload.innerHTML='<p class="my-4"><strong id="progress-value">Enviado</strong></p>';let e={action:"order_status_completed",order:upload_video.order_id};jQuery.post(woocommerce_params.ajax_url,e,()=>{window.location.href=polenObj.base_url+"/my-account/success-upload/?order_id="+upload_video.order_id}).fail(errorHandler)},errorHandler=(e,o,n)=>{alert("Erro no envio do arquivo, tente novamente"),document.location.reload()};function progressFunction(e,o){progress_value.innerText=`Enviando vídeo ${Math.floor(e/o*100)}%`}function changeText(){document.getElementById("info").innerText="Vídeo gravado com sucesso"}