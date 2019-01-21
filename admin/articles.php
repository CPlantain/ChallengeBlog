<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "../config.php";
require_once "../functions/helpers.php";
require_once "../functions/helpers_db.php";
require_once "../functions/validation_helpers.php";

// проверка прав доступа пользователя
require_once "./adm_auth.php";

// открываем буферизацию
ob_start();

$title = 'All articles';
// шапка 
require_once "./includes/admin_header.php";
// боковое меню
require_once "./includes/admin_sidebar.php";
?>

<!-- основная часть -->
<div class="main_adm col-md-10 px-5 pt-4 pb-5">

	<!-- вывод всех статей -->
	<h3>Articles:</h3>
	<a href="./create_article.php" class="btn btn-primary mt-3">Create new article</a>

	<!-- постраничный вывод всех статей из бд -->
	<?php 

	// определяем количество постов на странице
	$posts_per_page = 3;

	// считаем количество постов в бд
	$sql = 'SELECT COUNT(*) FROM articles';
	$posts_cnt = getColumn($pdo, $sql);
	
	// получаем данные для постраничного вывода и выводим все посты
	$pag_data = pagination($pdo, $posts_cnt, $posts_per_page);
	$cur_page_first_post = $pag_data['cur_page_first_post'];

	$sql = 'SELECT a.id, category_id, user_id, title, description, content, pub_date, name, login, picture, hidden FROM articles a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id ORDER BY pub_date DESC LIMIT :limitVal, :offsetVal';
	$articles = getAllLimit($pdo, $sql, $cur_page_first_post, $posts_per_page);

	?>
	<table class="table_main table table-hover mt-4">
		<tr>
			<th>id</th>
			<th>Category</th>
			<th>Author</th>
			<th>Title</th>
			<th>Description</th>
			<th>Content</th>
			<th>Picture</th>
			<th></th>
		</tr>
						
		<?php							
			
		foreach($articles as $article): ?>
			<tr>
				<th><?= $article['id']; ?></th>
				<td><?= $article['name']; ?></td>
				<td><?= $article['login']; ?></td>
				<td><?= $article['title']; ?></td>
				<td><?= $article['description']; ?></td>
				<td><?= $article['content']; ?></td>
				
				<?php 
				// если у статьи есть картинка, выводим её
				if(checkImage($article['picture'])): ?>
											
					<td><img src="../uploads/article_images/<?= $article['picture']; ?>" class="img_adm rounded"></td>
				<? else : ?>
					<td><img class="img_hid"></td>

				<? endif; ?>	
				
				<td>
					<!-- кнопки удаления и редактирования статьи -->
					<a href="?delete_article=<?=  $article['id']; ?>" class="delete_btn btn btn-outline-light btn-sm ml-2 mb-2 float-right">&#9747;</a>

					<a href="./edit_article.php?id=<?=  $article['id']; ?>" class="edit_btn btn btn-light btn-sm mb-2 float-right">Edit</a>
					<?php
					if($article['hidden'] == 0): ?>

						<!-- если статья не скрыта, выводим кнопку "скрыть" -->
						<a href="?hide=<?=  $article['id']; ?>" class="edit_btn btn btn-light btn-sm float-right">Hide</a>
					<? else: ?>

						<!-- если скрыта, выводим кнопку "показать" -->
						<a href="?show=<?= $article['id']; ?>" class="edit_btn btn btn-light btn-sm float-right">Show</a>
					<? endif; ?>
				</td>				
			</tr>
		<? endforeach; ?>
	</table>

	<?php

	// если нажата кнопка "скрыть", меняем у статьи значение "hidden" на 1
	if((!empty($_GET['hide'])) && checkAdmin()){
		if(is_numeric($_GET['hide'])){

			$data = [ 'hidden' => 1, 'id' => $_GET['hide'] ];
			$sql = 'UPDATE articles SET hidden = :hidden WHERE id = :id';
			execute($pdo, $sql, $data);

			// перенаправляем пользователя на страницу статей, чтобы избежать повторной отправки формы и возвращаем содержимое буфера
			$redirect = $_SERVER['HTTP_REFERER'];
			header('Location: ' . $redirect);
			ob_get_flush();
		}
	}

	// если нажата кнопка "показать", меняем у статьи значение "hidden" на 0
	if((!empty($_GET['show'])) && checkAdmin()){
		if(is_numeric($_GET['show'])){

			$data = [ 'hidden' => 0, 'id' => $_GET['show'] ];
			$sql = 'UPDATE articles SET hidden = :hidden WHERE id = :id';
			execute($pdo, $sql, $data);

			// перенаправляем пользователя на страницу статей и возвращаем содержимое буфера
			$redirect = $_SERVER['HTTP_REFERER'];
			header('Location: ' . $redirect);
			ob_get_flush();
		}	
	}

	// проверяем, нажата ли кнопка удаления
	if((!empty($_GET['delete_article'])) && checkAdmin()){
		if(is_numeric($_GET['delete_article'])){

			// проверяем, есть ли у статьи комментарии
			$data = [ 'id' => $_GET['delete_article'] ];
			$sql = 'SELECT COUNT(*) as count FROM comments WHERE article_id = :id';
			$comments = getRow($pdo, $sql, $data);
			
			// если есть, удаляем их
			if($comments['count'] != 0){
				$sql = 'DELETE FROM comments WHERE article_id = :id';
				execute($pdo, $sql, $data);
			}

			// достаём имя изображения статьи
			$sql = 'SELECT picture FROM articles WHERE id = :id';
			$article = getRow($pdo, $sql, $data);

			// если у статьи было изображение, сначала удаляем его с сервера
			if(checkImage($article['picture'])){

				$path = '../uploads/article_images/';
				deletePic($article['picture'], $path);
			}

			// затем удаляем саму статью из базы данных
			$data = [ 'id' => $_GET['delete_article'] ];
			$sql = 'DELETE FROM articles WHERE id = :id';
			execute($pdo, $sql, $data);

			// после удаления перенаправляем пользователя на страницу статей и возвращаем содержимое буфера
			$redirect = $_SERVER['HTTP_REFERER'];
			header('Location: ' . $redirect);
			ob_get_flush();
		}
	}
	?>

	<!-- пагинация -->
	<?php
		// отключение кнопки назад
		$p = $pag_data['p'];
		$disabled = disableItem($p, $pag_data['pages_cnt']);
	?>
	<ul class="pagination">	
		<li class="page-item <?= $disabled['item_prev']; ?>">
	        <a class="page-link" href="./articles.php?p=<?= ($p - 1) ?>" aria-label="Previous">
	            <span aria-hidden="true">&laquo;</span>
	            <span class="sr-only">Previous</span>
	        </a>
	    </li>
		
		<?php 

		// вывод ссылок на страницы, активация текущего элемента
		for ($p = 1; $p <= $pag_data['pages_cnt']; $p++):

			$active = activateItem($p); 

			?>
			<li class="page-item <?= $active; ?>"><a class="page-link" href="./articles.php?p=<?= $p ?>"><?= $p; ?></a></li>

		<? endfor; ?>
		
		<?php

		// отключение кнопки вперед
		$p = $_GET['p'];
		$disabled = disableItem($p, $pag_data['pages_cnt']);
		?>
		<li class="page-item <?= $disabled['item_next']; ?>">
	        <a class="page-link" href="./articles.php?p=<?= ($p + 1) ?>" aria-label="Next">
	        	<span aria-hidden="true">&raquo;</span>
	        	<span class="sr-only">Next</span>
	        </a>
	    </li>
	</ul>	
	
</div>

<!-- подвал -->
<?php require_once "./includes/admin_footer.php"; ?>

