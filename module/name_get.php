<?php
	$now = Date('Y-m-d H:i:s');
	$DB = Flight::db();
	$o_user = Flight::get('o_user');
	$user_right = Flight::get('user_right');

	if ($user_right[0] < 1) {
		Flight::json(array('message'=>'FORBIDDEN'), 403);
	} else if (isSet($id)) {
		$query = $DB->prepare("	SELECT *
								FROM my_items
								WHERE my_items.item_id = :item_id;");
		$query->bindParam(':item_id', $id, PDO::PARAM_INT);
		if ($query->execute()) {
			if ($query->rowCount() == 0) {
				Flight::json(array(	'message'=>'NOT_FOUND',
									'key'=>$id
				), 404);
			}

			$item = $query->fetch(PDO::FETCH_OBJ);

			Flight::json(array('data'=>array('item'=>$item)));
		} else {
			Flight::error(new Exception(implode(' ',array_slice($query->errorInfo(), 2))));
		}
	} else {
		if (!isSet($_GET['p'])) { $page = 1; }
		else { $page = $_GET['p']; }

		if (!isSet($_GET['q'])) { $_GET['q'] = ''; }
		$itemsPerPage = 15;
		$offset = (15*($page-1));

		$query = $DB->prepare("	SELECT 	item_id AS id,
										item_name AS name
								FROM my_items
								WHERE item_id LIKE :q
								OR item_name LIKE :q
								ORDER BY item_id DESC
								LIMIT :offset, :itemsPerPage;");
		$query->bindvalue(':q','%'.$_GET['q'].'%',PDO::PARAM_STR);
		$query->bindParam(':offset', $offset, PDO::PARAM_INT);
		$query->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
		if ($query->execute()) {
			$items = array();
			while ($row = $query->fetch(PDO::FETCH_OBJ)) {
				$items[] = $row;
			}

			Flight::json(array('data'=>array('pageIndex'=>$page,
											 'itemsPerPage'=>$itemsPerPage,
											 'items'=>$items,
											 'rowCount'=>$query->rowCount()
											)));
		} else {
			Flight::error(new Exception(implode(' ',array_slice($query->errorInfo(), 2))));
		}
	}

?>
