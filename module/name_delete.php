<?php
	$now = Date('Y-m-d H:i:s');
	$DB = Flight::db();
	$r = Flight::request();
	$data = $r->data->getData();

	if ($user_right[0] < 2) {
		Flight::json(array('message'=>'FORBIDDEN'), 403);
	} else if (!isSet($id)) {
		Flight::json(array('message'=>'BAD_REQUEST', 'key'=>'id'), 400);
	} else {

		$query = $DB->prepare("DELETE
								FROM my_items
								WHERE my_items.item_id = :id;");
		$query->bindvalue(':id', $id, PDO::PARAM_INT);
		if ($query->execute()) {
			Flight::json(array('data'=>array(	'message'=>'deleted',
												'rowCount'=>$query->rowCount(),
												'time'=>$now
											)));
		} else {
			Flight::error(new Exception(implode(' ',array_slice($query->errorInfo(), 2))));
		}

	}

?>
