const formName="form#landpage-form",form_landpage=document.querySelector(formName);form_landpage.addEventListener("submit",(function(e){e.preventDefault(),polSpinner(),jQuery.post(woocommerce_params.ajax_url,jQuery(formName).serialize(),(function(e){e.success?(polMessages.message(CONSTANTS.SUCCESS,"Enviado com sucesso","Seu cadastro foi efetuado com sucesso"),window.location.href="success"):polError(e.data)})).fail((function(e){e.responseJSON?polError(e.responseJSON.data):polError(e.statusText)})).complete((function(e){polSpinner("hidden")}))}));