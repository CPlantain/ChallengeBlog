<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверяем, отправлена ли форма
if(isFormSend()) {
	// для удобства сохраняю массив _POST в переменную
	$form = $_POST;

	// фильтрация и сохранение данных в переменные
	$id = $form['id'];
	$category_id = $form['category'];
	$user_id = $_SESSION['user']['id'];
	$title = filter($form['title']);
	$description = filter($form['description']);
	$content = filter($form['content']);
	$picture = $_FILES['art_pic'];

	$data = [ 'id' => $id ];
	$sql = 'SELECT user_id, picture FROM articles WHERE id = :id'; 
	$article = getRow($pdo, $sql, $data);

	// проверяем права доступа пользователя к статье
	if(!checkAuthor($article['user_id'])){
		// в случае отказа адресуем на главную
		header('Location: ./index.php');
		exit();
	}
	else {
		// валидация и сбор ошибок в массив
		$required = ['title', 'description', 'content'];
		$errors = [];
		$isError = false;

		foreach ($form as $key => $value) {
			if(in_array($key, $required)) {
				// проверка заполненности полей
				if(!checkRequired($required, $key, $value)) {
					$errors[$key] = 'This value is too short';
					$isError = true;
				}
			}
		}

		if(!$isError) {
			// если ошибок нет, проверяем, выбрана ли категория
			if($category_id < 0) {
				$errors['category'] = 'Please choose the category';
			}

			// если была загружена картинка, валидация картинки
			if($picture) {
				if(!validatePic($picture)) {
					$errors['picture'] = 'Wrong file loaded';
				}
			} 
		}

		// выводим ошибки, если они есть
		if (!empty($errors)) showErrors($errors);

		// если у статьи уже есть изображение, сначала удаляем его с сервера
		if(checkImage($article['picture']) && $picture){

			$path = '../uploads/article_images/';
			deletePic($article['picture'], $path);
		}

		// загружаем новое на сервер
		$path = '../uploads/article_images/';
		$picName = uploadPic($picture, $path);

		// если пользователь не загрузил картинку к статье оставляем имя изображения старым
		if($picName == null){
			$picName = $article['picture'];
		}

		// обновление данных выбранной статьи
		$data = 
		[
			'category_id' => $category_id, 
			'user_id' => $user_id, 
			'title' => $title,
			'description' => $description,
			'content' => $content,
			'picture' => $picName,
			'id' => $id
		];

		$sql = 'UPDATE articles SET category_id = :category_id, user_id = :user_id, title = :title, description = :description, content = :content, picture = :picture WHERE id = :id';
		execute($pdo, $sql, $data);

		echo ('success');
	}	
}

