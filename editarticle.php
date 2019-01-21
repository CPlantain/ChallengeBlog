<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "./config.php";
require_once "./functions/helpers.php";
require_once "./functions/helpers_db.php";

// сохраняем id выбранной статьи в переменную
$id = $_GET['id'];

// получаем из бд все данные о статье 
$data = [ 'id' => $id, 'hidden' => 0 ];
$sql = 'SELECT category_id, user_id, title, description, content, name, picture FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.id = :id AND hidden = :hidden'; 	
$article = getRow($pdo, $sql, $data);

// проверяем, свою ли статью пользователь хочет редактировать и авторизован ли он
if(!checkAuthor($article['user_id'])){
	// если нет, переадресуем на главную
	header('Location: ./index.php');
	exit();
}

// передаем название статьи в title
$title = 'Edit ' . $article['title'];

// шапка
require_once "./includes/header.php";

?>					
<div class="main col-md-8 px-5 py-3">
	
	<!-- форма редактирования статьи со всеми имеющимися в бд данными -->
	<div class="pb-3 px-3">
		<p class="form_p">Edit article:</p>

		<form name="edit_article">
			<div class="form-group row">
			    <div class="col-md-11">
			      	<input type="text" class="form-control" name="title" placeholder="Title" value="<?= $article['title']; ?>">
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<select class="form-control custom-select" name="category">
					  	<option value="<?= $article['category_id']; ?>"><?= $article['name']; ?></option>
					  	<?php

					  	// вывод всех категорий, кроме текущей, в список select
					  	$sql = 'SELECT * FROM categories';
					  	$categories = getAllRows($pdo, $sql);

					  	foreach ($categories as $category): 
					  		if(checkValue($category['id'], $article['category_id'])): ?>
					  			<option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
					  		<? endif; ?>
					  	<? endforeach; ?>
					</select>
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<textarea class="form-control" name="description" cols="100" rows="5" placeholder="Article description..."><?= $article['description']; ?></textarea>
			    </div>
		  	</div>

		  	<div class="form-group row">
			    <div class="col-md-11">
			      	<textarea class="form-control" name="content" cols="100" rows="15" placeholder="Article text..."><?= $article['content']; ?></textarea>
			    </div>
		  	</div>

		  	<div class="form-group row">							    
			    
			    <div class="col-md-11">

			    	<?php 

			    	// если у статьи есть картинка, выводим её
			    	if(checkImage($article['picture'])): ?>

				    	<p class="col-form-label">Article picture:</p>
				    	<img src="./uploads/article_images/<?= $article['picture']; ?>" class="edit_img img-thumbnail my-2">

				    <? endif; ?>
					
					<p class="col-form-label">Change or upload picture:</p>
			      	<input type="file" name="art_pic">
			    </div>
		  	</div>		

		  	<!-- уведомление об успешном редактировании статьи -->
			<div class="message alert alert-success" name="successAlert"></div>


		  	<!-- уведомление об ошибке при заполнении формы -->
		  	<div class="message alert alert-danger" name="errorAlert"></div>

		  	<!-- скрытый блок для передачи id текущей статьи -->
			<input type="hidden" name="id" value="<?=$_GET['id']?>">

		   	<div class="form-group row">
			    <div class="col-md-10">
			      	<button type="button" class="btn btn-primary" name="update_article">Update</button>
			    </div>
		  	</div>
		</form>
	</div>
	
</div>

<!-- сайдбар -->
<?php require_once "./includes/sidebar.php"; ?>

<!-- футер -->
<?php require_once "./includes/footer.php"; ?>
