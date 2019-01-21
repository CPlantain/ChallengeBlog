
<!--"шаблон" шапки и основной структуры документа -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./assets/main.css">
    <title><?= $title; ?></title>
  </head>
  <body>
  	<header>
	    <div class="header container-fluid align-items-center p-3 px-md-4 mb-3  border-bottom shadow-sm">
	    	<div class="container">
	    		<div class="row">
	        		<h5 class="my-auto mr-md-auto font-weight-normal" id="top">My blog</h5>
	        			<nav class="my-2 mr-md-auto my-md-auto mr-md-3">
				            <a class="p-2 text-dark" href="./index.php">Homepage</a>
				            <a class="p-2 text-dark" href="#">About us</a>
				            <a class="p-2 text-dark" href="#">Contact me</a>
	        			</nav>

						<!-- показываем неавторизованному пользователю кнопки входа и регистрации -->
						<?php if (!checkUser('user')): ?>								
							<a class="btn btn-outline-primary mr-2" href="./login.php">Login</a>
				        	<a class="btn btn-outline-primary" href="./signup.php">Sign up</a>

				        <!-- авторизованный пользователь видит ссылки на свой блог со статьями, профиль и выход из учетной записи -->
						<? else: ?>

							<a class="p-2 mr-1 text-dark" href="./blog.php?user_id=<?= $_SESSION['user']['id']?>">My blog</a>
							<a class="p-2 mr-4 text-dark" href="./profile.php?user_id=<?= $_SESSION['user']['id']?>">My profile</a>
							<a class="btn btn-outline-primary mr-2" href="./forms/logout.php">logout</a>
				        <? endif; ?>
	    		</div>
	    	</div>
	    </div>
	</header>
<main class="container mb-3">
	<div class="row">