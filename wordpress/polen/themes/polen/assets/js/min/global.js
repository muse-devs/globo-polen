function copyToClipboard(t){var e=document.getElementById("share-input");e.value=t,e.select(),e.setSelectionRange(0,99999),document.execCommand("copy"),alert("Link copiado para Área de transferência")}function changeHash(t){window.location.hash=t||""}function setImediate(t){setTimeout(t,1)}function truncatedItems(){const t=document.querySelectorAll(".truncate"),e=new ResizeObserver(t=>{for(let e of t)e.target.classList[e.target.scrollHeight>e.contentRect.height?"add":"remove"]("truncated")});t.forEach(t=>{e.observe(t)})}function polMessageKill(t){var e=document.getElementById(t);e&&(e.classList.remove("show"),setImediate((function(){e.parentNode.removeChild(e)})))}function polSpinner(t,e){if("hidden"===t)polMessageKill("pol-fog");else{polMessageKill("pol-fog");var s=null,n=document.createElement("div");n.id="pol-fog",n.classList.add("fog"),n.innerHTML='\n\t\t\t<div class="spinner">\n\t\t\t\t<div class="spinner-border text-primary" role="status">\n\t\t\t\t\t<span class="sr-only">Aguarde...</span>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t',e?(s=document.querySelector(e),n.classList.add("inner")):s=document.body,s.appendChild(n),setImediate((function(){n.classList.add("show")}))}}function polMessage(t,e){var s="message-box";polMessageKill(s);var n=document.createElement("div");n.id=s,n.classList.add(s),n.classList.add("success"),n.innerHTML=`\n\t<div class="row">\n\t\t<div class="col-md-12">\n\t\t\t<i class="bi bi-check-circle" style="color: var(--success)"></i>\n\t\t</div>\n\t\t<div class="col-md-12">\n\t\t\t<h4 class="message-title">${t}</h4>\n\t\t\t<p class="message-text mt-1">${e}</p>\n\t\t</div>\n\t</div>\n\t<button class="message-close" onclick="polMessageKill('${s}')">\n\t\t<i class="icon icon-close"></i>\n\t</button>\n\t`,document.body.appendChild(n),setImediate((function(){n.classList.add("show")}))}function polError(t){var e="message-box";polMessageKill(e);var s=document.createElement("div");s.id=e,s.classList.add(e),s.classList.add("fail"),s.innerHTML=`\n\t<i class="icon icon-error-o" style="color: var(--danger);"></i>\n\t<p class="message-text px-1">${t}</p>\n\t<button class="message-close" onclick="polMessageKill('${e}')">\n\t\t<i class="icon icon-close"></i>\n\t</button>\n\t`,document.body.appendChild(s),setImediate((function(){s.classList.add("show")}))}jQuery(document).ready((function(){truncatedItems()}));