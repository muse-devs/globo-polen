const form="#help-form",bus_form=new Vue({el:form,data:{phone:""},methods:{handleChange:function(e){},handleEdit:function(){this.edit=!0},handleSubmit:function(){polAjaxForm(form,(function(e){polMessages.message("Enviado!","Sua mensagem foi enviada com sucesso!")}),(function(e){polMessages.error(e)}))}}});!function(e){e(document).on("click",".panel-button",(function(o){let n=e(this).attr("href");e(".panel-button:not([href="+n+"])").addClass("collapsed").attr("aria-expanded","false"),e(".collapse:not("+n+")").removeClass("show")}))}(jQuery);