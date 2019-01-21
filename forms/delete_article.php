<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// достаём данные об id пользователя и имени изображения из базы данных
$id = $_GET['id'];

$data = [ 'id' => $id ];
$sql = 'SELECT user_id, picture FROM articles WHERE id = :id';
$article = getRow($pdo, $sql, $data);

// проверяем права доступа пользователя к этой статье
if(!checkAuthor($article['user_id'])){
	// в случае отказа адресуем на главную
	header('Location: ./index.php');
	exit();
}

// если пользователь прошел проверку смотрим, есть ли у статьи комментарии
else {
	$data = [ 'article_id' => $id ];
	$sql = 'SELECT COUNT(*) as count FROM comments WHERE article_id = :article_id';
	$comments = getRow($pdo, $sql, $data);
	
	// если есть, удаляем их
	if($comments['count'] != 0){
		$sql = 'DELETE FROM comments WHERE article_id = :article_id';
		execute($pdo, $sql, $data);
	}

	// если у статьи есть изображение, удаляем его с сервера
	if(checkImage($article['picture'])){

		$path = '../uploads/article_images/';
		deletePic($article['picture'], $path);
	}

	// затем удаляем саму статью из базы данных
	$data = [ 'id' => $id ];
	$sql = 'DELETE FROM articles WHERE id = :id';
	execute($pdo, $sql, $data);

	// возвращаем пользователя на страницу блога текущего пользователя
	
	header('Location: ../blog.php?user_id=' . $_SESSION['user']['id']);
}
