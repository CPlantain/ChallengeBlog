<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "config.php";
require_once "functions/helpers.php";
require_once "functions/helpers_db.php";

$title = 'Login';

// шапка
require_once "includes/header.php";
?>

<div class="main col-md-8 px-5 pt-1">

	<!-- если пользователь не авторизован, показываем ему форму входа -->
    <?php if(!checkUser('user')): ?>
		<!-- форма входа на сайт -->
		<div class="pt-4 px-3">
			<p class="form_p">Login</p>

			<!-- уведомление об успешной авторизации -->
			<div class="message alert alert-success" name="successAlert"></div>

			<p class="mb-4">Please fill out the following fields to login:</p>
			<form>
			  	<div class="form-group row">
				    <label for="login" class="col-md-3 col-form-label">Login:</label>
				    <div class="col-md-9">
				      	<input type="login" class="form-control" id="login" name="login" placeholder="Login">
				    </div>
			  	</div>

			  	<div class="form-group row">
				    <label for="password" class="col-md-3 col-form-label">Password:</label>
				    <div class="col-md-9">
				      	<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				    </div>
			  	</div>

			  	<!-- уведомление об ошибке при заполнении формы -->
			  	<div class="message alert alert-danger" name="errorAlert"></div>

			  	<div class="form-group row">
				    <div class="col-md-10">
				      	<button type="button" class="btn btn-primary" name="sign_in">Sign in</button>
				    </div>
			  	</div>
			</form>
		</div>
	<? else: ?>

		<!-- если пользователь авторизован, показываем сообщение: -->
		<div class="alert alert-success mt-5">You are already authorized</div>
	<? endif; ?>
</div>

<!-- сайдбар -->	
<?php require_once "includes/sidebar.php"; ?>

<!-- футер -->
<?php require_once "includes/footer.php"; ?>
