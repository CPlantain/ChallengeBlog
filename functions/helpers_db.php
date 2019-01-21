<?php

	// получение одной строки
	function getRow($pdo, $sql, $data = null){
		try{
			$result = $pdo->prepare($sql);
			$result->execute($data);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}catch(PDOException $e){
			echo('Something got wrong with database, sorry');
		}
	}

	// получение всех строк из запроса
	function getAllRows($pdo, $sql, $data = null){
		try{
			$result = $pdo->prepare($sql);
			$result->execute($data);
			$rows = $result->fetchAll(PDO::FETCH_ASSOC);
			return $rows;
		}catch(PDOException $e){
			echo('Something got wrong with database, sorry');
		}
		
	}

	// выполнение запроса без возвращения результата
	function execute($pdo, $sql, $data = null){
		try{
			$result = $pdo->prepare($sql);
			$result->execute($data);
		}catch(PDOException $e){
			echo('Something got wrong with database, sorry');
		}
	}
	
	// получение одного столбца
	function getColumn($pdo, $sql, $data = null){
		try{
			$result = $pdo->prepare($sql);
			$result->execute($data);
			$columns = $result->fetchColumn();
			return $columns;
		}catch(PDOException $e){
			echo('Something got wrong with database, sorry');
		}
	}

	// получение всех строк из SQL запроса с LIMIT
	function getAllLimit($pdo, $sql, $limitVal, $offsetVal, $data = null){
		try{
			$result = $pdo->prepare($sql);

			// если данные переданы, связываем их ключи со значениями
			if($data != null) {
					foreach ($data as $key => $value) {
						$result->BindValue($key, $value);
				}
			}

			// если нет, обрабатываем значения LIMIT и OFFSET
			$result->BindValue(':limitVal', $limitVal, PDO::PARAM_INT);
			$result->BindValue(':offsetVal', $offsetVal, PDO::PARAM_INT);
			$result->execute();
			$articles = $result->fetchAll(PDO::FETCH_ASSOC);
			return $articles;
		}catch(PDOException $e){
			echo('Something got wrong with database, sorry');
		}
	}