<?php get_header('tributes'); ?>

<main id="create-tribute">
	<div class="container py-3 tribute-container tribute-app">
		<div class="row">
			<div class="col-md-12">
				<h1 class="title text-center">Comece sua homenagem</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mt-5">
				<form id="form-create-tribute" action="./" method="POST">
					<input type="hidden" name="action" value="create_tribute" />
					<p class="mb-4">
						<label for="">Para quem é o tributo?</label>
						<input type="text" name="" id="" placeholder="Ex: Diego, Rodolfo" class="form-control form-control-lg">
					</p>
					<p class="mb-4">
						<label for="occasion">Qual é a ocasião?</label>
						<select name="occasion" id="occasion" class="form-control form-control-lg custom-select">
							<option value="">Selecione a ocasião</option>
						</select>
					</p>
					<div class="mb-4">
						<label for="">Qual a URL do seu tributo?</label>
						<p>https://polen.me/tribute/<input type="text" name="" id="" placeholder="nome-do-tributo" class="input-tribute-url" /></p>
					</div>
					<p class="mb-4">
						<label for="date">Qual o prazo?</label>
						<br />Recomendamos vários dias antes da entrega do tributo para que você tenha tempo de editar seu vídeo.
						<input type="text" name="date" id="date" placeholder="dd/mm/aaaa" class="form-control form-control-lg" />
					</p>
					<p class="mb-4">
						<label for="">Qual o seu e-mail?</label>
						<input type="email" name="" id="" placeholder="Entre com seu e-mail" class="form-control form-control-lg">
					</p>
					<p class="mb-4">
						<label for="">Instruções</label>
						<textarea name="" id="" cols="30" rows="5" class="form-control form-control-lg"></textarea>
					</p>
					<p class="mb-5">
						<label for="">Que perguntas você deseja que as pessoas respondam em seus vídeos Tributo?</label>
						<input type="text" name="" id="" placeholder="Qual sua coisa favorita sobre Fulano?" class="form-control form-control-lg">
					</p>
					<div class="row">
						<div class="col-md-4 m-md-auto">
							<input type="submit" value="Avançar" class="btn btn-primary btn-lg btn-block" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>

<?php get_footer('tributes'); ?>
