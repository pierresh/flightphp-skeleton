<?php

if ($user_right[0] < 2) {
	Flight::forbidden();
} elseif (!isset($data['my_required_data'])) {
	Flight::badRequest('my_required_data');
}

$query = $DB->prepare("	INSERT INTO my_items (item_name)
						VALUES (:items_name);");
$query->bindParam(':items_name', $data['items_name'], PDO::PARAM_STR);
if (!$query->execute()) {
	Flight::error(
		new Exception(implode(' ', array_slice($query->errorInfo(), 2)))
	);
}

$item_id = $DB->lastInsertId();

Flight::json(
	[
		'data' => [
			'message' => 'created',
			'time' => $now,
			'rowCount' => $query->rowCount(),
			'id' => intval($item_id),
		],
	],
	201
);
