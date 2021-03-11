<?php /* Template Name: Login Page */ ?>

<?php get_header(); ?>

<main id="primary" class="site-main">

    <div class="row my-5 py-5 justify-content-md-center talent-login">
        <div class="col-12 text-center">
            <h1 class="mb-5">Sou um talento</h1>
        </div>
        <div class="col-4">
            <form action="." method="post" class="text-center">
                <p><input type="mail" name="mail" id="mail" placeholder="e-mail" class="form-control form-control-lg" /></p>
                <p><input type="password" name="password" id="password" placeholder="Senha" class="form-control form-control-lg" /></p>
                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Entrar" />
                <p class="my-4"><a href="#">Esqueci minha senha</a></p>
            </form>
        </div>
    </div>

    <?php polen_front_get_tutorial(); ?>

</main><!-- #main -->

<?php
get_footer();
