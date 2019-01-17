<?php

	// обработка и получение нужных данных для постраничного вывода
	function pagination($pdo, $posts_cnt, $posts_per_page){
		
		// считаем количество страниц 
		$pages_cnt = ceil($posts_cnt / $posts_per_page);

		// определяем номер текущей страницы
		if (!isset($_GET['p'])){
			$p = 1;
		} else {
			$p = $_GET['p'];
		}

		// определяем первый пост на каждой странице
		$cur_page_first_post = ($p - 1) * $posts_per_page;

		// возвращаем все необходимые переменные
		$data = 
		[
			'pages_cnt' => $pages_cnt,
			'p' => $p,
			'cur_page_first_post' => $cur_page_first_post
		];

		return $data;
	}

	// отключение кнопок со стрелками в пагинации посредством добавления нужного класса через переменную
	function disableItem($p, $pages_cnt){

		// если текущая страница под номером 1, отключаем кнопку назад
		if ($p == 1){
			$item_prev = 'disabled';
		} 
		// если номер текущей страницы равен их количеству (номеру последней), откючаем кнопку вперёд
		else if ($p == $pages_cnt) {
			$item_next = 'disabled';
		} 
		// во всех иных случаях кнопки активны, значение класса пустое
		else {
			$item_prev = '';
			$item_next = '';
		}

		// возвращаем значения переменных для обеих кнопок
		$items = 
		[
			'item_prev' => $item_prev,
			'item_next' => $item_next
		];
		return $items;
	}

	// активация ссылки текущей страницы в пагинации
	function activateItem($p){

		// если номер страницы совпадает с текущим параметром из массива $_GET, меняем класс на активный
		if ($_GET['p'] == $p){
			$item = 'active';
		}
		// в остальных случаях значение класса пустое
		else {
			$item = '';
		}
		return $item;
	}

	// проверка на наличие изображения 
	function checkImage($image){
		if($image != null){
			return true;
		} else {
			return false;
		}
	}

	// проверка, авторизован ли пользователь
	function checkUser($user){
		if (isset($_SESSION[$user])){
			return true;
		} else {
			return false;
		}
	}

	// проверка автора
	function checkAuthor($author){
		// если пользователь авторизован и является автором статьи/владельцем выбранной учетной записи, возвращаем true 
		if(($_SESSION['user'] != null) && ($author == $_SESSION['user']['id'])){
			return true;
		} else {
			return false;
		}
	}

	// проверка, прошел ли пользователь авторизацию администратора
	function checkAdmin(){
		if ($_SESSION['admin'] == true){
			return true;
		} else {
			return false;
		}
	}

	// проверка на соответствие значений (для вывода всех категорий, кроме выбранной в списке категорий на странице редактирования статьи)
	function checkValue($checking, $current){
		// если проверяемое значение не равно текущему, возвращаем true
  		if($checking != $current){
  			return true;
  		} else {
  			return false;
  		}
  	}
