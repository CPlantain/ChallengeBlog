<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверка прав доступа пользователя
require_once "adm_auth.php";

// открываем буферизацию
ob_start();

// сохраняем id выбранной статьи в переменную
$id = $_GET['id'];

// получаем из бд все данные о статье и передаем ее название в title
$data = [ 'id' => $id ];
$sql = 'SELECT category_id, user_id, title, description, content, name, login, picture FROM articles a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id WHERE a.id = :id'; 
$article = getRow($pdo, $sql, $data);

$title = 'Edit ' . $article['title'];
// шапка 
require_once "includes/admin_header.php";
// боковое меню
require_once "includes/admin_sidebar.php";
?>

<!-- основная часть -->
<div class="main_adm col-md-10 px-5 pt-4 pb-5">
	
	<!-- форма редактирования статьи -->
	<h3>Edit article:</h3>

	<form method="POST" action="edit_article.php?id=<?= $id; ?>" enctype="multipart/form-data">

		<div class="form-group row">
		    <div class="col-md-11">
		      	<input type="text" class="form-control" name="title" placeholder="Title" value="<?= $article['title']; ?>">
		    </div>
	  	</div>

	  	<div class="form-group row">
		    <div class="col-md-11">
		      	<select class="form-control custom-select" name="author">
		      		<option value="<?= $article['user_id']; ?>"><?= $article['login']; ?></option>
					  	<?php
					  	// вывод всех пользователей, кроме текущего, в список select
					  	$sql = 'SELECT * FROM users';
					  	$users = getAllRows($pdo, $sql);

					  	foreach ($users as $user): 
					  		if(checkValue($user['id'], $article['user_id'])): ?>
					  			<option value="<?= $user['id']; ?>"><?= $user['login']; ?></option>
					  		<? endif; ?>
					  	<? endforeach; ?>
				</select>
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
				    	<img src="../uploads/article_images/<?= $article['picture']; ?>" class="img-thumbnail my-2" style="width: 400px">

				    <? endif; ?>
		      	<p class="col-form-label">Change or upload picture:</p>
				<input type="file" name="art_pic">
		    </div>
	  	</div>	

	   	<div class="form-group row">
		    <div class="col-md-10">
		      	<button type="submit" class="btn btn-primary" name="update_article">Update</button>
		    </div>
	  	</div>	
	</form>

	<?php
// обработчик формы редактирования статьи
	// проверяем отправлена ли форма
	if(isFormSend() && (isset($_POST['update_article'])) && checkAdmin()){

		// если да, сохраняем данные POST в переменную
		$form = $_POST;

		// фильтруем данные и сохраняем в переменные
		$title = filter($form['title']);
		$user_id = $form['author'];
		$category_id = $form['category'];
		$description = filter($form['description']);
		$content = filter($form['content']);
		$picture = $_FILES['art_pic'];

		// обязательные поля и массив с ошибками
		$required = ['title', 'description', 'content'];
		$errors = [];
		$isError = false;

		// валидация формы
		foreach ($form as $key => $value) {
			if(in_array($key, $required)){
				// проверка заполненности полей
				if(!checkRequired($required, $key, $value)) {
					$errors[$key] = 'This value is too short';
					$isError = true;
				}
			}
		}
		if(!$isError){
			// если ошибок не было, проверяем, выбран ли автор
			if($user_id <= 0){
				$errors['author'] = 'Please choose the author';
			}

			// и категория
			if($category_id < 0){
				$errors['category'] = 'Please choose the category';
			}

			// если загружено изображение, валидация изображения
			if($picture['name'] != '') {
				if(!validatePic($picture)) {
					$errors['picture'] = 'Wrong file loaded';
				}
			} 
		}

		// выводим ошибки, если они были
		if (!empty($errors)) showErrors($errors);

		// если у статьи уже есть изображение, сначала удаляем его с сервера
		if(checkImage($article['picture']) && $picture['name'] != ''){

			$path = '../uploads/article_images/';
			deletePic($article['picture'], $path);
		}

		// загружаем новое изображение
		$path = '../uploads/article_images/';
		$picName = uploadPic($picture, $path);
	
		// если пользователь не загрузил картинку к статье оставляем ее имя прежним
		if($picName == null){
			$picName = $article['picture'];
		}

		// добавляем новую статью в базу данных
		$data = 
		[
			'user_id' => $user_id, 
			'category_id' => $category_id, 
			'title' => $title,
			'description' => $description,
			'content' => $content,
			'picture' => $picName,
			'id' => $id
		];

		$sql = 'UPDATE articles SET user_id = :user_id, category_id = :category_id, title = :title, description = :description, content = :content, picture = :picture WHERE id = :id';
		execute($pdo, $sql, $data);

		// перенаправляем пользователя на страницу редактирования выбранной статьи и возвращаем содержимое буфера
		header('Location: http://blog/admin/edit_article.php?id=' . $_GET['id']);
		ob_get_flush();
	}
	?>

</div>

<!-- подвал -->
<?php require_once "includes/admin_footer.php"; ?>

