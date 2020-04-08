<?php
	$now = Date('Y-m-d H:i:s');
	$DB = Flight::get('DB');
	$r = Flight::request();
	$data = $r->data->getData();

	if ($user_right[0] < 2) {
		Flight::json(array('message'=>'FORBIDDEN'), 403);
	} else if (!isSet($id)) {
		Flight::json(array('message'=>'BAD_REQUEST', 'key'=>'id'), 400);
	} else if (!isSet($line)) {
		Flight::json(array('message'=>'BAD_REQUEST', 'key'=>'line'), 400);
	} else {

		$query = $DB->prepare("DELETE
								FROM my_items_details
								WHERE my_items_details.details_line = :line;;");
		$query->bindvalue(':line', $line, PDO::PARAM_INT);
		if ($query->execute()) {
			Flight::json(array('data'=>array(	'message'=>'deleted',
												'time'=>$now
											)));
		} else {
			Flight::error(new Exception(implode(' ',array_slice($query->errorInfo(), 2))));
		}

	}

?>
