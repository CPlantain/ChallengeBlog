<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "./config.php";
require_once "./functions/helpers.php";
require_once "./functions/helpers_db.php";

// сохраняем параметр id в переменную
$id = $_GET['id'];

// достаём из базы данных всё о выбранной статье 
$data = [ 'id' => $id, 'hidden' => 0 ];
$sql = 'SELECT a.id, category_id, user_id, title, content, pub_date, name, login, picture FROM articles a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id WHERE a.id = :id AND hidden = :hidden'; 
$article = getRow($pdo, $sql, $data);

// передаём название статьи в title
$title = $article['title'];

// подключение шапки
require_once "./includes/header.php";
?>
<div class="main col-md-8 px-5 py-3">

	<!-- если пользователь зашёл в блог со своими статьями, выводим ссылки на удаление и редактирование статьи -->
	<?php if(checkAuthor($article['user_id'])): ?>	

		<a href="./forms/delete_article.php?id=<?= $article['id'] ?>" class="delete_btn btn btn-outline-light btn-sm ml-2 float-right">&#9747;</a>

		<a href="./editarticle.php?id=<?= $article['id'] ?>" class="edit_btn btn btn-light btn-sm float-right">Edit</a>
		
	<? endif; ?>
	
	<!-- вывод отдельной статьи -->
	<h2><?= $article['title']; ?></h2>
	<small><a href="./category.php?id=<?= $article['category_id']?>"><?= $article['name']; ?></a></small>

	<?php 
	// если у статьи есть картинка, выводим её
	if(checkImage($article['picture'])): ?>

    	<div class="px-5">
    		<img src="./uploads/article_images/<?= $article['picture']; ?>" class="sidebar_img img-thumbnail my-2" alt="">
    	</div>

    <? endif; ?>
	
	<p><?= $article['content']; ?></p>
	<p>
		<a href="./blog.php?user_id=<?= $article['user_id']?>">@<?= $article['login']; ?></a> 
		at <?=date("d.m.y",strtotime($article['pub_date']))?>
	</p>


	<!-- вывод комментариев к статье -->
	<?php

	// получение всех комментариев к выбранной статье
	$data = [ 'id' => $id ];
	$sql = 'SELECT c.id, c.user_id, article_id, c.content, c.pub_date, login, avatar FROM comments c JOIN users u ON c.user_id = u.id JOIN articles a ON c.article_id = a.id WHERE a.id = :id';
	$comments = getAllRows($pdo, $sql, $data);
	
	// аватарка по умолчанию, если пользователь не загрузил свою, вывод комментариев
	foreach ($comments as $comment):
		if(!checkImage($comment['avatar'])){
			$comment['avatar'] = 'default_icon.png';
		} ?>
		
		<div class="comment_block container p-3 my-2 mb-3 rounded">
			<div class="row">
				<div class="col-md-2">
					<img src="./uploads/avatars/<?= $comment['avatar']; ?>" alt="" class="avatar_small rounded-circle">
				</div>

				<div class="col-md-10 push-md-1">
					<p class="comment_login">
						<a href="./blog.php?user_id=<?= $comment['user_id']?>">@<?= $comment['login']; ?></a>
					</p>
					<p class="comment_pubdate"><?=date("d.m.y",strtotime($comment['pub_date']))?></p>
					<p><?= $comment['content']; ?></p>
				</div>
			</div>						
		</div>

	<? endforeach; ?>

	<!-- форма добавления комментария для авторизованных пользователей -->
	<?php 

	if (checkUser('user')): ?>
		<form class="p-2 mt-4">
			<p>Leave a comment:</p>
			<textarea class="p-3 form-control" name="comment" cols="85" rows="5" placeholder="write a message"></textarea><br>	

			<!-- уведомление об ошибке при заполнении формы -->
			<div class="message alert alert-danger" name="errorAlert"></div>	

			<!-- скрытый блок для передачи id текущей статьи -->
			<input type="hidden" name="id" value="<?=$_GET['id']?>">

			<button type="button" class="btn btn-outline-primary" name="send">send a comment</button>
		</form>
	<? endif; ?>
	
</div>

<!-- подключение сайдбара -->
<?php require_once "./includes/sidebar.php"; ?>

<!-- подключение футера -->
<?php require_once "./includes/footer.php"; ?>

