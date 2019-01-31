<?php
	$now = Date('Y-m-d H:i:s');
	$DB = Flight::get('DB');
	$r = Flight::request();
	$data = $r->data->getData();

	if ($DB == false) {
		Flight::json(array('message'=>'SESSION_EXPIRED'), 401);
	} else if ($user_right[0]] < 2) {
		Flight::json(array('message'=>'FORBIDDEN'), 403);
	} else if (!isSet($data['my_required_data'])) {
		Flight::json(array('message'=>'BAD REQUEST', 'key'=>'my_required_data')), 400);
	} else {
		$query = $DB->prepare("	INSERT INTO my_items (item_name)
								VALUES (:items_name);");
		$query->bindParam(':items_name', $data['items_name'], PDO::PARAM_STR);	
		if ($query->execute()) {
			$item_id = $DB->lastInsertId();
			Flight::json(array('data'=>array(	'message'=>'created', 
												'time'=>$now,
												'rowCount'=>$query->rowCount(),
												'id'=>$item_id
											)));
		} else {
			Flight::json(array('message'=>implode(' ',array_slice($query->errorInfo(), 2))), 500);
		}
		
	}
?>
