<?php
// подключаем файл конфигурации и файлы с нужными функциями
require_once "config.php";
require_once "functions/helpers.php";
require_once "functions/helpers_db.php";

$title = 'Main';

// шапка
require_once "includes/header.php";

?>
<!-- основная часть -->
<div class="main col-md-8 px-5 py-4">

	<!-- постраничный вывод всех статей из бд -->
	<?php 

	// определяем количество постов на странице
	$posts_per_page = 4;

	// считаем количество постов в бд, без учёта скрытых
	$sql = 'SELECT COUNT(*) FROM articles WHERE hidden = :hidden';
	$data = [ 'hidden' => 0 ];
	$posts_cnt = getColumn($pdo, $sql, $data);

	// получаем данные для постраничного вывода и выводим все посты с LIMIT
	$pag_data = pagination($pdo, $posts_cnt, $posts_per_page);
	$cur_page_first_post = $pag_data['cur_page_first_post'];

	$data =[ 'hidden' => 0 ];
	$sql = 'SELECT a.id, category_id, user_id, title, description, pub_date, name, login, picture, hidden FROM articles a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id WHERE hidden = :hidden ORDER BY pub_date DESC LIMIT :limitVal, :offsetVal';
	$articles = getAllLimit($pdo, $sql, $cur_page_first_post, $posts_per_page, $data);

	// вывод самих статей
	foreach ($articles as $article): ?>

		<h2><?= $article['title']?></h2>
		<p class="small">
			<a href="/category.php?id=<?= $article['category_id']?>"><?= $article['name']?></a>
		</p>

		<?php 
    	// если у статьи есть картинка, выводим её
    	if(checkImage($article['picture'])): ?>

	    	<div class="px-5">
	    		<img src="/uploads/article_images/<?= $article['picture']; ?>" class="imgart img-thumbnail my-2" alt="">
	    	</div>

	    <? endif; ?>
		
		<p><?= $article['description']?></p>
		<a class="btn btn-outline-primary mr-2 mb-4" href="article.php?id=<?= $article['id']?>">Read more...</a>
		<p>
			<a href="/blog.php?user_id=<?=$article['user_id']?>">
			@<?= $article['login']?></a> 
			at <?= date("d.m.Y", strtotime($article['pub_date'])); ?>
		</p>
		<hr>
		
	<? endforeach; ?>

	<!-- пагинация -->
	<?php
	// отключение кнопки назад
	$p = $pag_data['p'];
	$disabled = disableItem($p, $pag_data['pages_cnt']);		
	?>

	<ul class="pagination">	
		<li class="page-item <?= $disabled['item_prev']; ?>">
	        <a class="page-link" href="/index.php?p=<?= ($p - 1) ?>" aria-label="Previous">
	            <span aria-hidden="true">&laquo;</span>
	            <span class="sr-only">Previous</span>
	        </a>
	    </li>
		
		<?php 

		// вывод ссылок на страницы, активация текущего элемента
		for ($p = 1; $p <= $pag_data['pages_cnt']; $p++):

			$active = activateItem($p); ?>

			<li class="page-item <?= $active; ?>">
				<a class="page-link" href="/index.php?p=<?= $p ?>"><?= $p; ?></a>
			</li>

		<? endfor; ?>
		
		<?php

		// отключение кнопки вперед
		$p = $_GET['p'];
		$disabled = disableItem($p, $pag_data['pages_cnt']);

		?>
		<li class="page-item <?= $disabled['item_next']; ?>">
	        <a class="page-link" href="/index.php?p=<?= ($p + 1) ?>" aria-label="Next">
	        	<span aria-hidden="true">&raquo;</span>
	        	<span class="sr-only">Next</span>
	        </a>
	    </li>
	</ul>
	
</div>

<!-- сайдбар -->
<? require_once "includes/sidebar.php"; ?>

<!-- футер -->
<? require_once "includes/footer.php"; ?>
