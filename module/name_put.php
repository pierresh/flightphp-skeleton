<?php
	$now = Date('Y-m-d H:i:s');
	$DB = Flight::get('DB');
	$o_user = Flight::get('o_user');
	$user_right = Flight::get('user_right');
	$r = Flight::request();
	$data = $r->data->getData();

	if ($user_right[0] < 2) {
		Flight::json(array('message'=>'FORBIDDEN'), 403);
	} else if (!isSet($id)) {
		Flight::json(array('message'=>'BAD REQUEST', 'key'=>'id'), 400);
	} else if (!isSet($data['item_name'])) {
		Flight::json(array('message'=>'BAD REQUEST', 'key'=>'item_name'), 400);
	} else {
		$query = $DB->prepare(" UPDATE my_items
								SET item_name = :item_name
								WHERE item_id = :item_id;");

		$query->bindParam(':item_id', $id, PDO::PARAM_INT);
		$query->bindParam(':item_name', $data['item_name'], PDO::PARAM_STR);
		if ($query->execute()) {
			Flight::json(array('data'=>array(	'message'=>'updated',
												'time'=>$now,
												'rowCount'=>$query->rowCount()
											)));
		} else {
			Flight::error(new Exception(implode(' ',array_slice($query->errorInfo(), 2))));
		}

	}
?>
