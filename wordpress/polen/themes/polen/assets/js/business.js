const form = "#bus-form";

const bus_form = new Vue({
	el: form,
	data: {
		phone: "",
	},
	methods: {
		handleChange: function (e) {
			this.phone = mtel(e.target.value);
		},
		handleEdit: function () {
			this.edit = true;
		},
		handleSubmit: function () {
			polAjaxForm(
				form,
				function (e) {
					polMessages.message(
						"Enviado!",
						"Seu número foi adicionado com sucesso"
					);
				},
				function (e) {
					polMessages.error(e);
				}
			);
		},
	},
});
