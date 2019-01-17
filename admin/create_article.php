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

$title = 'Create article';
// шапка 
require_once "includes/admin_header.php";
// боковое меню
require_once "includes/admin_sidebar.php";
?>

<!-- основная часть -->
<div class="main_adm col-md-10 px-5 pt-4 pb-5">

	<!-- форма создания новой статьи -->
	<h3>Create new article:</h3>

	<form method="POST" action="create_article.php" enctype="multipart/form-data">

		<div class="form-group row">
		    <div class="col-md-11">
		      	<input type="text" class="form-control" name="title" placeholder="Title" value="<?= $_POST['title']; ?>">
		    </div>
	  	</div>

	  	<div class="form-group row">
		    <div class="col-md-11">
		      	<select class="form-control custom-select" name="author">
				  	<option value="-1">Choose author...</option>
				  	<?php
				  	$sql = 'SELECT * FROM users';
				  	$users = getAllRows($pdo, $sql);

				  	foreach ($users as $user): ?>
				  		<option value="<?= $user['id']; ?>"><?= $user['login']; ?></option>
				  	<? endforeach; ?>
				</select>
		    </div>
	  	</div>

	  	<div class="form-group row">
		    <div class="col-md-11">
		      	<select class="form-control custom-select" name="category">
				  	<option value="-1">Choose category...</option>
				  	<?php
				  	$sql = 'SELECT * FROM categories';
				  	$categories = getAllRows($pdo, $sql);

				  	foreach ($categories as $category): ?>
				  		<option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
				  	<? endforeach; ?>
				</select>
		    </div>
	  	</div>

	  	<div class="form-group row">
		    <div class="col-md-11">
		      	<textarea class="form-control" name="description" cols="100" rows="2" placeholder="Article description..."><?= $_POST['description']; ?></textarea>
		    </div>
	  	</div>

	  	<div class="form-group row">
		    <div class="col-md-11">
		      	<textarea class="form-control" name="content" cols="100" rows="6" placeholder="Article text..."><?= $_POST['content']; ?></textarea>
		    </div>
	  	</div>

	  	<div class="form-group row">							    
		    <label class="col col-form-label">Article picture:</label>
		    <div class="col-md-11">
		      	<input type="file" name="art_pic" >
		    </div>
	  	</div>	

	  	<div class="form-group row">							    
		    <label class="col col-form-label">Publication Date:</label>
		    <div class="col-md-11">
		      	<input type="datetime-local" name="pub_date" value="<?= $_POST['pub_date']; ?>">
		    </div>
	  	</div>			

	   	<div class="form-group row">
		    <div class="col-md-10">
		      	<button type="submit" class="btn btn-primary" name="create_article">Create</button>
		    </div>
	  	</div>	
	</form>

	<?php
// обработчик формы создания статьи
	// проверяем отправлена ли форма создания
	if(isFormSend() && (isset($_POST['create_article'])) && checkAdmin()){

		// если да, сохраняем данные POST в переменную
		$form = $_POST;

		// фильтруем данные и сохраняем в переменные
		$title = filter($form['title']);
		$user_id = $form['author'];
		$category_id = $form['category'];
		$description = filter($form['description']);
		$content = filter($form['content']);
		$pub_date = $form['pub_date'];
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
			// если ошибок в первом шаге нет, проверяем, выбран ли автор
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

		// если дата публикации не задана, записываем текущее время
		if($pub_date == ''){
			$pub_date = date('Y-m-d H:i:s');
		}

		// если ошибки есть, выводим их
		if (!empty($errors)) showErrors($errors);

		// загружаем изображение
		$path = '../uploads/article_images/';
		$picName = uploadPic($picture, $path);
		
		// добавляем новую статью в базу данных
		$data = 
		[ 
			'user_id' => $user_id,
			'category_id' => $category_id,
			'title' => $title,
			'description' => $description,
			'content' => $content,
			'picture' => $picName,
			'pub_date' => $pub_date

		];

		$sql = 'INSERT INTO articles(user_id, category_id, title, description, content, picture, pub_date) VALUES(:user_id, :category_id, :title, :description, :content, :picture, :pub_date)';
		execute($pdo, $sql, $data);

		// перенаправляем пользователя на страницу добавления статьи, чтобы избежать повторной отправки формы и возвращаем содержимое буфера
		header('Location: http://blog/admin/create_article.php');
		ob_get_flush();
	}
	?>
	
</div>

<!-- подвал -->
<?php require_once "includes/admin_footer.php"; ?>

