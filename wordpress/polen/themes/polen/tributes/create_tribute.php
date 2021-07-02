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
						<label for="name_honored">Para quem é o tributo?</label>
						<input type="text" name="name_honored" id="name_honored" placeholder="Ex: Diego, Rodolfo" class="form-control form-control-lg" required>
					</p>
					<p class="mb-4">
						<label for="occasion">Qual é a ocasião?</label>
						<select name="occasion" id="occasion" class="form-control form-control-lg custom-select">
							<option value="">Selecione a ocasião</option>
						</select>
					</p>
					<div class="mb-4">
						<label for="slug">Qual a URL do seu tributo?</label>
						<p class="d-flex mb-0 slug-wrap">https://polen.me/tributes/<input type="text" name="slug" id="slug" placeholder="nome-do-tributo" class="input-tribute-url" required /></p>
						<small id="slug-message" class="slug-message"></small>
					</div>
					<p class="mb-4">
						<label for="deadline">Qual o prazo?</label>
						<br />Recomendamos vários dias antes da entrega do tributo para que você tenha tempo de editar seu vídeo.
						<input type="text" name="deadline" id="deadline" placeholder="dd/mm/aaaa" class="form-control form-control-lg" required />
					</p>
					<p class="mb-4">
						<label for="creator_name">Qual o seu nome?</label>
						<input type="text" name="creator_name" id="creator_name" placeholder="Seu nome" class="form-control form-control-lg" required />
					</p>
					<p class="mb-4">
						<label for="creator_email">Qual o seu e-mail?</label>
						<input type="email" name="creator_email" id="creator_email" placeholder="Entre com seu e-mail" class="form-control form-control-lg" required />
					</p>
					<p class="mb-4">
						<label for="welcome_message">Instruções</label>
						<textarea name="welcome_message" id="welcome_message" cols="30" rows="5" class="form-control form-control-lg" required></textarea>
					</p>
					<p class="mb-5">
						<label for="question">Que perguntas você deseja que as pessoas respondam em seus vídeos Tributo?</label>
						<input type="text" name="question" id="question" placeholder="Qual sua coisa favorita sobre Fulano?" class="form-control form-control-lg" required />
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
