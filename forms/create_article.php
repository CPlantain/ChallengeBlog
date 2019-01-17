<?php 
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверяем, была ли отправлена форма
if(isFormSend()) {
	// для удобства сохраняю массив _POST в переменную
	$form = $_POST;

	// фильтрация и сохранение данных в переменные
	$category_id = $form['category'];
	$user_id = $_SESSION['user']['id'];
	$title = filter($form['title']);
	$description = filter($form['description']);
	$content = filter($form['content']);
	$picture = $_FILES['art_pic'];

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
		// проверка, была ли выбрана категория
		if($category_id < 0) {
			$errors['category'] = 'Please choose the category';
		}

		// валидация изображения, если оно было загружено
		if($picture) {
			if(!validatePic($picture)) {
				$errors['picture'] = 'Wrong file loaded';
			}
		} 
	}

	// вывод ошибок, если они есть
	if (!empty($errors)) showErrors($errors);

	// загружаем изображение на сервер
	$path = '../uploads/article_images/';
	$picName = uploadPic($picture, $path);
	
	// добавление статьи в базу данных
	$data = 
	[ 
		'category_id' => $category_id,
		'user_id' => $user_id,
		'title' => $title,
		'description' => $description,
		'content' => $content,
		'picture' => $picName

	];

	$sql = 'INSERT INTO articles(category_id, user_id, title, description, content, picture) VALUES(:category_id, :user_id, :title, :description, :content, :picture)';
	execute($pdo, $sql, $data);

	// выводим сообщение об успешной загрузке
	echo ('success');
}