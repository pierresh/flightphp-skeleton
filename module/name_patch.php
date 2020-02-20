<?php
	$now = Date('Y-m-d H:i:s');
	$DB = Flight::db();
	$o_user = Flight::get('o_user');
	$user_right = Flight::get('user_right');
	$r = Flight::request();
	$data = $r->data->getData();

	// Input data should be:
	// 1. field (which column of the table will be modified)
	// 2. value (the new value for that field)

	if ($user_right[0]] < 2) {
		Flight::json(array('message'=>'FORBIDDEN'), 403);
	} else if (!isSet($id)) {
		Flight::json(array('message'=>'BAD REQUEST', 'key'=>'id'), 400);
	} else if (!isSet($data['field'])) {
		Flight::json(array('message'=>'BAD REQUEST', 'key'=>'field'), 400);
	} else if (!isSet($data['value'])) {
		Flight::json(array('message'=>'BAD REQUEST', 'key'=>'value'), 400);
	} else {

		// In order avoid to set a lot of queries (one by field potentially modified by this API), the field is sanitized then will be used in the query
		// Inspired from https://stackoverflow.com/questions/23482104/can-i-use-a-pdo-prepared-statement-to-bind-an-identifier-a-table-or-field-name

		$allowed = array('item_name',
						 'item_status');

		$index = array_search($data['field'], $allowed);

		if ($index === false) {
			Flight::error(new Exception('Field not found: '. $data['field']));
			die();
		}

		// The query is executed only if the $data['field'] has been found in $allowed
		$query = $DB->prepare(" UPDATE my_items 
								SET ".$allowed[$index]." = :value
								WHERE item_id = :item_id;");

		$query->bindParam(':item_id', $id, PDO::PARAM_INT);
		$query->bindParam(':value', $data['value'], PDO::PARAM_STR);
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
