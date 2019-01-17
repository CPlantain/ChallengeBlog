-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 17 2019 г., 03:07
-- Версия сервера: 5.7.23
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `blog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `pub_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `picture` varchar(255) DEFAULT NULL,
  `hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `category_id`, `user_id`, `title`, `description`, `content`, `pub_date`, `picture`, `hidden`) VALUES
(1, 2, 1, 'The Forest', 'Erat felis egestas fusce fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Eu curae mauris hendrerit convallis. A vitae ac ante facilisis adipiscing ipsum platea scelerisque luctus. Fermentum nonummy aenean risus dictum hac. Nibh pellentesque auctor. Cubilia? Consequat mus. Ridiculus class. Tempor orci lacus sem adipiscing. Lorem at taciti orci bibendum lorem. Et sollicitudin. Faucibus augue nisl facilisi. Duis ve habitasse lorem! Dis duis morbi morbi tristique massa mi convallis accumsan neque. Erat maecenas aliquam nunc commodo magnis luctus eni est. Odio cursus augue curae felis viverra vel mauris sem cubilia nec. Natoque metus dignissim ultricies tortor orci faucibus neque. Lacinia. Hymenaeos cum auctor ut? Vestibulum urna. Mattis. Orci nulla magnis. Suscipit nam suspendisse dis libero eni elit ullamcorper suscipit scelerisque amet. Sodales montes. Diam feugiat mi ullamcorper diam fusce accumsan sem! Pede. A augue potenti. Torquent sagittis ad metus. In congue integer bibendum velit neque nostra. Placerat donec. Lacinia proin praesent erat lectus.', '2018-12-20 10:13:09', '38d349cefcb0a931a5d5de89f6199b36.jpeg', 0),
(2, 3, 4, 'Running man', 'Erat felis egestas fusce fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Eu curae mauris hendrerit convallis. A vitae ac ante facilisis adipiscing ipsum platea scelerisque luctus. Fermentum nonummy aenean risus dictum hac. Nibh pellentesque auctor. Cubilia? Consequat mus. Ridiculus class. Tempor orci lacus sem adipiscing. Lorem at taciti orci bibendum lorem. Et sollicitudin. Faucibus augue nisl facilisi. Duis ve habitasse lorem! Dis duis morbi morbi tristique massa mi convallis accumsan neque. Erat maecenas aliquam nunc commodo magnis luctus eni est. Odio cursus augue curae felis viverra vel mauris sem cubilia nec. Natoque metus dignissim ultricies tortor orci faucibus neque. Lacinia. Hymenaeos cum auctor ut? Vestibulum urna. Mattis. Orci nulla magnis. Suscipit nam suspendisse dis libero eni elit ullamcorper suscipit scelerisque amet. Sodales montes. Diam feugiat mi ullamcorper diam fusce accumsan sem! Pede. A augue potenti. Torquent sagittis ad metus. In congue integer bibendum velit neque nostra. Placerat donec. Lacinia proin praesent erat lectus.', '2018-12-20 10:14:35', '4182f993873a3d5ff679b07015f8d8e3.jpeg', 0),
(3, 0, 2, 'About Secrets You Never Knew', 'Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Eu curae mauris hendrerit convallis. A vitae ac ante facilisis adipiscing ipsum platea scelerisque luctus. Fermentum nonummy aenean risus dictum hac. Nibh pellentesque auctor. Cubilia? Consequat mus. Ridiculus class. Tempor orci lacus sem adipiscing. Lorem at taciti orci bibendum lorem. Et sollicitudin. Faucibus augue nisl facilisi. Duis ve habitasse lorem! Dis duis morbi morbi tristique massa mi convallis accumsan neque. Erat maecenas aliquam nunc commodo magnis luctus eni est. Odio cursus augue curae felis viverra vel mauris sem cubilia nec. Natoque metus dignissim ultricies tortor orci faucibus neque. Lacinia. Hymenaeos cum auctor ut? Vestibulum urna. Mattis. Orci nulla magnis. Suscipit nam suspendisse dis libero eni elit ullamcorper suscipit scelerisque amet. Sodales montes. Diam feugiat mi ullamcorper diam fusce accumsan sem! Pede. A augue potenti. Torquent sagittis ad metus. In congue integer bibendum velit neque nostra. Placerat donec. Lacinia proin praesent erat lectus.', '2018-12-20 10:15:07', '76413e91edb4af9bc67e8f6c1c32ed2a.jpeg', 0),
(4, 11, 3, 'About Sherlock Holmes', 'Erat felis egestas fusce fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Eu curae mauris hendrerit convallis. A vitae ac ante facilisis adipiscing ipsum platea scelerisque luctus. Fermentum nonummy aenean risus dictum hac. Nibh pellentesque auctor. Cubilia? Consequat mus. Ridiculus class. Tempor orci lacus sem adipiscing. Lorem at taciti orci bibendum lorem. Et sollicitudin. Faucibus augue nisl facilisi. Duis ve habitasse lorem! Dis duis morbi morbi tristique massa mi convallis accumsan neque. Erat maecenas aliquam nunc commodo magnis luctus eni est. Odio cursus augue curae felis viverra vel mauris sem cubilia nec. Natoque metus dignissim ultricies tortor orci faucibus neque. Lacinia. Hymenaeos cum auctor ut? Vestibulum urna. Mattis. Orci nulla magnis. Suscipit nam suspendisse dis libero eni elit ullamcorper suscipit scelerisque amet. Sodales montes. Diam feugiat mi ullamcorper diam fusce accumsan sem! Pede. A augue potenti. Torquent sagittis ad metus. In congue integer bibendum velit neque nostra. Placerat donec. Lacinia proin praesent erat lectus.', '2018-12-20 10:16:58', '72058b4f70ced146b853b39dad89da63.jpeg', 0),
(5, 2, 4, 'The Secret of Sunflowers', 'Erat felis egestas fusce fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Eu curae mauris hendrerit convallis. A vitae ac ante facilisis adipiscing ipsum platea scelerisque luctus. Fermentum nonummy aenean risus dictum hac. Nibh pellentesque auctor. Cubilia? Consequat mus. Ridiculus class. Tempor orci lacus sem adipiscing. Lorem at taciti orci bibendum lorem. Et sollicitudin. Faucibus augue nisl facilisi. Duis ve habitasse lorem! Dis duis morbi morbi tristique massa mi convallis accumsan neque. Erat maecenas aliquam nunc commodo magnis luctus eni est. Odio cursus augue curae felis viverra vel mauris sem cubilia nec. Natoque metus dignissim ultricies tortor orci faucibus neque. Lacinia. Hymenaeos cum auctor ut? Vestibulum urna. Mattis. Orci nulla magnis. Suscipit nam suspendisse dis libero eni elit ullamcorper suscipit scelerisque amet. Sodales montes. Diam feugiat mi ullamcorper diam fusce accumsan sem! Pede. A augue potenti. Torquent sagittis ad metus. In congue integer bibendum velit neque nostra. Placerat donec. Lacinia proin praesent erat lectus.', '2018-12-20 10:18:35', '248eebeab1ce40080916c597099308ca.jpeg', 0),
(6, 2, 1, 'Snowy mountains', 'Erat felis egestas fusce fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Eu curae mauris hendrerit convallis. A vitae ac ante facilisis adipiscing ipsum platea scelerisque luctus. Fermentum nonummy aenean risus dictum hac. Nibh pellentesque auctor. Cubilia? Consequat mus. Ridiculus class. Tempor orci lacus sem adipiscing. Lorem at taciti orci bibendum lorem. Et sollicitudin. Faucibus augue nisl facilisi. Duis ve habitasse lorem! Dis duis morbi morbi tristique massa mi convallis accumsan neque. Erat maecenas aliquam nunc commodo magnis luctus eni est. Odio cursus augue curae felis viverra vel mauris sem cubilia nec. Natoque metus dignissim ultricies tortor orci faucibus neque. Lacinia. Hymenaeos cum auctor ut? Vestibulum urna. Mattis. Orci nulla magnis. Suscipit nam suspendisse dis libero eni elit ullamcorper suscipit scelerisque amet. Sodales montes. Diam feugiat mi ullamcorper diam fusce accumsan sem! Pede. A augue potenti. Torquent sagittis ad metus. In congue integer bibendum velit neque nostra. Placerat donec. Lacinia proin praesent erat lectus.', '2018-12-20 10:19:14', 'b74b44bb8158916e7286b66a22736317.jpeg', 0),
(7, 2, 2, 'River travelling', 'Fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Eu curae mauris hendrerit convallis. A vitae ac ante facilisis adipiscing ipsum platea scelerisque luctus. Fermentum nonummy aenean risus dictum hac. Nibh pellentesque auctor. Cubilia? Consequat mus. Ridiculus class. Tempor orci lacus sem adipiscing. Lorem at taciti orci bibendum lorem. Et sollicitudin. Faucibus augue nisl facilisi. Duis ve habitasse lorem! Dis duis morbi morbi tristique massa mi convallis accumsan neque. Erat maecenas aliquam nunc commodo magnis luctus eni est. Odio cursus augue curae felis viverra vel mauris sem cubilia nec. Natoque metus dignissim ultricies tortor orci faucibus neque. Lacinia. Hymenaeos cum auctor ut? Vestibulum urna. Mattis. Orci nulla magnis. Suscipit nam suspendisse dis libero eni elit ullamcorper suscipit scelerisque amet. Sodales montes. Diam feugiat mi ullamcorper diam fusce accumsan sem! Pede. A augue potenti. Torquent sagittis ad metus. In congue integer bibendum velit neque nostra. Placerat donec. Lacinia proin praesent erat lectus.', '2018-12-20 10:20:52', '1faca058ec6ac9ea54286ad09c09d4fc.jpeg', 0),
(8, 4, 2, 'My holidays', 'Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Litora laoreet aenean duis sapien. Tellus tempor dis varius. Viverra elementum ut auctor sed vestibulum quisque bibendum hymenaeos nunc. Augue nisi semper malesuada diam pellentesque leo viverra purus amet fusce penatibus augue nisi. Felis fames. Arcu. Platea convallis urna integer nisi dignissim purus donec at ante rutrum aliquet eni convallis ac. Penatibus ornare litora mattis cursus metus id duis. Mauris inceptos conubia vestibulum orci suscipit natoque convallis aptent fusce litora facilisis suscipit aliquet montes. Est laoreet hendrerit. Hac hymenaeos semper sagittis sem ipsum posuere justo netus. Rhoncus per commodo justo aliquet mi sodales facilisis natoque curae ac dolor primis inceptos nonummy! Aliquet varius adipiscing neque tincidunt quis sed ornare convallis condimentum malesuada. Etiam non nisl orci magnis. Aliquet magnis ligula suspendisse arcu luctus in quisque gravida. Tortor vestibulum suspendisse varius ad quisque neque vestibulum libero. Congue euismod porta interdum pretium laoreet porta.', '2018-12-21 11:48:47', '12aa887627f549ca9bb01a907289628d.jpeg', 0),
(9, 10, 4, 'How I Improved My Cooking In One Day', 'Felis egestas fusce fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollis dui suspendisse adipiscing ut fusce.fuygugt7ouersgwer', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Litora laoreet aenean duis sapien. Tellus tempor dis varius. Viverra elementum ut auctor sed vestibulum quisque bibendum hymenaeos nunc. Augue nisi semper malesuada diam pellentesque leo viverra purus amet fusce penatibus augue nisi. Felis fames. Arcu. Platea convallis urna integer nisi dignissim purus donec at ante rutrum aliquet eni convallis ac. Penatibus ornare litora mattis cursus metus id duis. Mauris inceptos conubia vestibulum orci suscipit natoque convallis aptent fusce litora facilisis suscipit aliquet montes. Est laoreet hendrerit. Hac hymenaeos semper sagittis sem ipsum posuere justo netus. Rhoncus per commodo justo aliquet mi sodales facilisis natoque curae ac dolor primis inceptos nonummy! Aliquet varius adipiscing neque tincidunt quis sed ornare convallis condimentum malesuada. Etiam non nisl orci magnis. Aliquet magnis ligula suspendisse arcu luctus in quisque gravida. Tortor vestibulum suspendisse varius ad quisque neque vestibulum libero. Congue euismod porta interdum pretium laoreet porta.', '2018-12-21 11:49:54', 'c13180aee9dab84bc3c2fbf0f07fb81d.jpeg', 0),
(10, 4, 1, 'Summer Adventures', 'Erat felis egestas fusce fermentum nec condimentum augue. Morbi dui eu platea at. Primis lobortis convallis egestas ridiculus curae quis vivamus laoreet sed maecenas. Urna rhoncus nec nostra elementum conubia mattis mollit fusce.rgrtg', 'Lorem ipsum dolor sit amet consectetuer adipiscing elit. Litora laoreet aenean duis sapien. Tellus tempor dis varius. Viverra elementum ut auctor sed vestibulum quisque bibendum hymenaeos nunc. Augue nisi semper malesuada diam pellentesque leo viverra purus amet fusce penatibus augue nisi. Felis fames. Arcu. Platea convallis urna integer nisi dignissim purus donec at ante rutrum aliquet eni convallis ac. Penatibus ornare litora mattis cursus metus id duis. Mauris inceptos conubia vestibulum orci suscipit natoque convallis aptent fusce litora facilisis suscipit aliquet montes. Est laoreet hendrerit. Hac hymenaeos semper sagittis sem ipsum posuere justo netus. Rhoncus per commodo justo aliquet mi sodales facilisis natoque curae ac dolor primis inceptos nonummy! Aliquet varius adipiscing neque tincidunt quis sed ornare convallis condimentum malesuada. Etiam non nisl orci magnis. Aliquet magnis ligula suspendisse arcu luctus in quisque gravida. Tortor vestibulum suspendisse varius ad quisque neque vestibulum libero. Congue euismod porta interdum pretium laoreet porta.erg', '2018-12-21 11:50:45', '4edfe85678c2113fd8c1e33a6a7eabb1.jpeg', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(0, 'Unnamed'),
(2, 'Nature'),
(3, 'Sport'),
(4, 'Lifestyle'),
(10, 'Cooking'),
(11, 'Literature'),
(15, 'IT and Technologies');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `article_id` int(11) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `pub_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `article_id`, `content`, `pub_date`) VALUES
(1, 1, 5, 'Cool article!', '2018-12-22 15:37:38'),
(2, 3, 5, 'nice', '2018-12-22 15:37:38'),
(4, 3, 8, 'test comment', '2018-12-22 15:37:38'),
(7, 4, 9, 'test', '2018-12-29 19:22:17'),
(12, 2, 9, 'I like this', '2019-01-16 19:12:09');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `isAdmin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `avatar`, `isAdmin`) VALUES
(1, 'Nickel', 'test@mail.ru', '$2y$10$Ad3QOyVl53Fzkzam/uK/mesNG9W7K2OLzfUaBz5lsu3svfoyLd.Rq', NULL, 0),
(2, 'starscraper', 'tester@goo.ru', '$2y$10$Ad3QOyVl53Fzkzam/uK/mesNG9W7K2OLzfUaBz5lsu3svfoyLd.Rq', NULL, 0),
(3, 'kermit', 'test.test@mail.ru', '$2y$10$Ad3QOyVl53Fzkzam/uK/mesNG9W7K2OLzfUaBz5lsu3svfoyLd.Rq', NULL, 0),
(4, 'admin', 'tester@test.ru', '$2y$10$f6pQ01eKOTNpK.syv1qaNurboYEvVzDrIaXeoL6Fi8sTu6smEoTCu', '458e47a238ee07c512af618f1163722b.png', 1),
(5, 'Mr.Sandman', 'test@test.ru', '$2y$10$Ad3QOyVl53Fzkzam/uK/mesNG9W7K2OLzfUaBz5lsu3svfoyLd.Rq', NULL, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comments_ibfk_2` (`article_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
