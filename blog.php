<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "./config.php";
require_once "./functions/helpers.php";
require_once "./functions/helpers_db.php";

// сохраняем id выбранного пользователя в переменную
$user_id = $_GET['user_id'];

// получаем данные выбранного пользователя
$data = [ 'user_id' => $user_id, 'hidden' => 0 ];
$sql = 'SELECT a.id, category_id, user_id, title, description, pub_date, name, login, picture, hidden FROM articles a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id WHERE user_id = :user_id AND hidden = :hidden ORDER BY pub_date DESC'; 	
$articles = getRow($pdo, $sql, $data);

// передача логина в title
$title = 'All posts from ' . $articles['login'];

// шапка
require_once "./includes/header.php";

?>
<div class="main col-md-8 px-5 py-3">

	<!-- вывод всех статей одного пользователя -->
	<?php 
	$articles = getAllRows($pdo, $sql, $data);
	foreach ($articles as $article): ?>

		<div class="container">
			<div class="row">
				<div class="col-md-3 px-1">

					<?php 
			    	// если у статьи есть картинка, выводим её
			    	if(checkImage($article['picture'])): ?>

				    	<img src="./uploads/article_images/<?= $article['picture']; ?>" class="blogimg img-thumbnail my-2" alt="">

				    <? endif; ?>
					
				</div>
				<div class="col-md-9 push-md-1 mb-1">
					<h2 class="blog_h2"><?= $article['title']; ?></h2>

					<!-- если пользователь зашёл в блог со своими статьями, выводим ссылки на удаление и редактирование статьи -->
					<?php if(checkAuthor($user_id)): ?>	

						<a href="./forms/delete_article.php?id=<?= $article['id'] ?>" class="delete_btn btn btn-outline-light btn-sm ml-2 float-right">&#9747;</a>

						<a href="./editarticle.php?id=<?= $article['id'] ?>" class="edit_btn btn btn-light btn-sm float-right">Edit</a>
						
					<? endif; ?>

					<small class="btn_block">
						<a href="./category.php?id=<?= $article['category_id']?>"><?= $article['name']; ?></a>
					</small>
					<p><?= $article['description']; ?></p>
					<a class="btn btn-outline-primary btn-sm mr-2 mb-4" href="./article.php?id=<?= $article['id']?>">Read more...<a><br>									
					<p class="btn_block mb-2">
						<a href="./blog.php?user_id=<?= $article['user_id']?>">@<?= $article['login']; ?></a>
						at <?=date("d.m.y",strtotime($article['pub_date']))?>
					</p>
					<hr>					
				</div>
			</div>						
		</div>	
	<? endforeach; ?>

</div>

<!-- сайдбар -->	
<?php require_once "./includes/sidebar.php"; ?>

<!-- футер -->	
<?php require_once "./includes/footer.php"; ?>
