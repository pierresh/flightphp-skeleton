<?php
	$now = Date('Y-m-d H:i:s');
	$DB = Flight::db();
	$r = Flight::request();
	$data = $r->data->getData();

	if ($user_right[0] < 2) {
		Flight::json(array('message'=>'FORBIDDEN'), 403);
	} else if (!isSet($id)) {
		Flight::json(array('message'=>'BAD_REQUEST', 'key'=>'id'), 400);
	} else if (!isSet($line)) {
		Flight::json(array('message'=>'BAD_REQUEST', 'key'=>'line'), 400);
	} else {

		$query = $DB->prepare("	DELETE
								FROM my_items_details
								WHERE my_items_details.details_line = :line;");

		$results = array();
		$lines = explode(',', $line);

		foreach ($lines as $value){
			$query->bindvalue(':line', $value, PDO::PARAM_INT);
			if (!$query->execute()) {
				Flight::error(new Exception(implode(' ',array_slice($query->errorInfo(), 2))));
			} else {
				if ($query->rowCount() > 0) {
					$results[] = $value;
				}
			}
		}

		Flight::json(array('data'=>array(	'message'=>'deleted',
											'results'=>$results,
											'time'=>$now
										)));

	}

?>
