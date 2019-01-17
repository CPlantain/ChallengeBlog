<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "config.php";
require_once "functions/helpers.php";
require_once "functions/helpers_db.php";

$title = 'Sign up';

// шапка
require_once "includes/header.php"; 
?>

<div class="main col-md-8 px-5 pt-1">

	<!-- если пользователь не авторизован, показываем ему форму регистрации -->
	<?php if(!checkUser('user')): ?>
		<!-- форма регистрации на сайте -->
		<div class="pt-4 px-3">
			<p class="form_p">Register</p>

			<!-- уведомление об успешной регистрации -->
			<div class="message alert alert-success" name="successAlert"></div>

			<p class="mb-4">Please fill out the following fields to register:</p>
			<form>
				<div class="form-group row">
				    <label for="login" class="col-md-3 col-form-label">Login:</label>
				    <div class="col-md-9">
				      	<input type="text" class="form-control" id="login" name="login" placeholder="Login">
				    </div>
			  	</div>

			  	<div class="form-group row">
				    <label for="email" class="col-md-3 col-form-label">Email:</label>
				    <div class="col-md-9">
				      	<input type="email" class="form-control" id="email" name="email" placeholder="Email">
				    </div>
			  	</div>

			  	<div class="form-group row">
				    <label for="password" class="col-md-3 col-form-label">Password:</label>
				    <div class="col-md-9">
				      	<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				    </div>
			  	</div>

			  	<div class="form-group row">
				    <label for="password_confirm" class="col-md-3 col-form-label">Confirm password:</label>
				    <div class="col-md-9">
				      	<input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm password">
				    </div>
			  	</div>
					
				<!-- уведомление об ошибке при заполнении формы -->
			  	<div class="message alert alert-danger" name="errorAlert"></div>

			  	<div class="form-group row">
				    <div class="col-md-10">
				      	<button type="button" class="btn btn-primary" name="sign_up">Sign up</button>
				    </div>
			  	</div>
			</form>
		</div>
		<? else: ?>
			<!-- если нет, выводим сообщение: -->
			<div class="alert alert-success mt-5">You are already authorized</div>
		<? endif; ?>
</div>	

<!-- сайдбар -->			
<?php require_once "includes/sidebar.php"; ?>

<!-- футер -->
<?php require_once "includes/footer.php"; ?>
