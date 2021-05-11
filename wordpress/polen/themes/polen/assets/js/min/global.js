const CONSTANTS={MESSAGE_COOKIE:"message_cookie",SUCCESS:"success",ERROR:"error",SHOW:"show",HIDDEN:"hidden"};function copyToClipboard(e){var t=document.getElementById("share-input");t.value=e,t.select(),t.setSelectionRange(0,99999),document.execCommand("copy"),alert("Link copiado para Área de transferência")}function changeHash(e){window.location.hash=e||""}function setImediate(e){setTimeout(e,1)}function polMessageKill(e){var t=document.getElementById(e);t&&(t.classList.remove(CONSTANTS.SHOW),setImediate((function(){t.parentNode.removeChild(t)})))}function polSpinner(e,t){if(e===CONSTANTS.HIDDEN)polMessageKill("pol-fog");else{polMessageKill("pol-fog");var s=null,n=document.createElement("div");n.id="pol-fog",n.classList.add("fog"),n.innerHTML='\n\t\t\t<div class="spinner">\n\t\t\t\t<div class="spinner-border text-primary" role="status">\n\t\t\t\t\t<span class="sr-only">Aguarde...</span>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t',t?(s=document.querySelector(t),n.classList.add("inner")):s=document.body,s.appendChild(n),setImediate((function(){n.classList.add(CONSTANTS.SHOW)}))}}function polMessage(e,t){var s="message-box";polMessageKill(s);var n=document.createElement("div");n.id=s,n.classList.add(s),n.classList.add(CONSTANTS.SUCCESS),n.innerHTML=`\n\t<div class="row">\n\t\t<div class="col-md-12">\n\t\t\t<i class="bi bi-check-circle" style="color: var(--success)"></i>\n\t\t</div>\n\t\t<div class="col-md-12">\n\t\t\t<h4 class="message-title">${e}</h4>\n\t\t\t<p class="message-text mt-1">${t}</p>\n\t\t</div>\n\t</div>\n\t<button class="message-close" onclick="polMessageKill('${s}')">\n\t\t<i class="icon icon-close"></i>\n\t</button>\n\t`,document.body.appendChild(n),setImediate((function(){n.classList.add(CONSTANTS.SHOW)}))}function polError(e){var t="message-box";polMessageKill(t);var s=document.createElement("div");s.id=t,s.classList.add(t),s.classList.add(CONSTANTS.ERROR),s.innerHTML=`\n\t<i class="icon icon-error-o" style="color: var(--danger);"></i>\n\t<p class="message-text px-1">${e}</p>\n\t<button class="message-close" onclick="polMessageKill('${t}')">\n\t\t<i class="icon icon-close"></i>\n\t</button>\n\t`,document.body.appendChild(s),setImediate((function(){s.classList.add(CONSTANTS.SHOW)}))}function truncatedItems(){const e=document.querySelectorAll(".truncate");if(e.length<1)return;const t=new ResizeObserver(e=>{for(let t of e)t.target.classList[t.target.scrollHeight>t.contentRect.height?"add":"remove"]("truncated")});e.forEach(e=>{t.observe(e)})}function setSessionMessage(e=CONSTANTS.SUCCESS,t="Obrigado!",s){sessionStorage.setItem(CONSTANTS.MESSAGE_COOKIE,JSON.stringify({type:e,title:t,message:s}))}function getSessionMessage(){var e=sessionStorage.getItem(CONSTANTS.MESSAGE_COOKIE);if(e){var t=JSON.parse(e);t.type===CONSTANTS.SUCCESS?polMessage(t.title,t.message):t.type===CONSTANTS.ERROR&&polError(t.message),sessionStorage.removeItem(CONSTANTS.MESSAGE_COOKIE)}}polenObj.developer||(console={debug:function(){},error:function(){},info:function(){},log:function(){},warn:function(){}}),jQuery(document).ready((function(){truncatedItems(),getSessionMessage()})),function(e){e(document).on("click",".signin-newsletter-button",(function(t){t.preventDefault();var s=e('input[name="signin_newsletter"]'),n=e(this).attr("code");e(".signin-response").html(""),""!==s.val()?(polSpinner(CONSTANTS.SHOW,"#signin-newsletter"),e.ajax({type:"POST",url:woocommerce_params.ajax_url,data:{action:"polen_newsletter_signin",security:n,email:s.val()},success:function(t){polMessage("Cadastro Efetuado",e.parseJSON(t).response),s.val("")},complete:function(){polSpinner(CONSTANTS.HIDDEN)},error:function(e,t,s){polError(`${t}: ${s}`)}})):e(".signin-response").html("Favor digite um e-mail válido")}))}(jQuery);