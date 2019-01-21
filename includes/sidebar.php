<!--"шаблон" боковой колонки сайта -->

<div class="col-md-3 offset-md-1">
	<aside class="sidebar p-3">

		<!-- вывод названий категорий -->
		<p>Categories:</p>
		<ul>
			<?php

			$sql = 'SELECT id, name FROM categories ORDER BY id DESC';
			$categories = getAllRows($pdo, $sql);

			foreach ($categories as $category): ?>
				<li>
					<a href="./category.php?id=<?= $category['id']; ?>"><?= $category['name']; ?></a>
				</li>
			<? endforeach; ?>
		</ul>
	</aside>
	
	<aside class="sidebar p-3 mt-4">

		<!-- вывод трёх последних добавленных статей, исключая скрытые -->
		<p>Recent posts:</p>
		<?php

		$sql = 'SELECT a.id, category_id, user_id, title, description, pub_date, name, login, picture, hidden FROM articles a JOIN categories c ON a.category_id = c.id JOIN users u ON a.user_id = u.id WHERE hidden = :hidden ORDER BY pub_date DESC LIMIT 3';

		$data = 
		[
			'hidden' => 0
		];

		$articles = getAllRows($pdo, $sql, $data);
		
		foreach ($articles as $article): 

			if(checkImage($article['picture'])): ?>
	    		<div class="px-2">
	    			<img src="./uploads/article_images/<?= $article['picture']; ?>" class="sidebar_img img-thumbnail my-2" alt="">
	    		</div>
			<? endif; ?>

			<h3 class="sidebar_h3"><?= $article['title']; ?></h3>
			<p><?= $article['description']; ?></p>
			<a class="btn btn-outline-primary btn-sm mr-2 mb-4" href="./article.php?id=<?=$article['id']?>">Read more...<a><br>
			<small>
				<a href="./category.php?id=<?= $article['category_id']; ?>"><?= $article['name']; ?></a>
			</small><br>
			<p class="sidebar_login" class="mb-2">
				<a href="./blog.php?user_id=<?= $article['user_id']?>">@<?= $article['login']; ?></a>
				at <?=date("d.m.y",strtotime($article['pub_date']))?>
			</p>
			<hr>
		<? endforeach; ?>
	</aside>
</div>