<?php

if ($user_right[0] < 2) {
	Flight::forbidden();
} elseif (!isset($data['my_required_data'])) {
	Flight::badRequest('my_required_data');
}

$query = $DB->prepare("	INSERT INTO my_items (item_name)
						VALUES (:items_name);");
$query->bindParam(':items_name', $data['items_name']);
if (!$query->execute()) {
	Flight::error(new Exception(errorInfo($query)));
}

$item_id = $DB->lastInsertId();

Flight::json(
	[
		'data' => [
			'message' => 'created',
			'time' => $now,
			'rowCount' => $query->rowCount(),
			'id' => (int) $item_id,
		],
	],
	201
);
