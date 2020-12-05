<?php

if ($user_right[0] < 2) {
	Flight::forbidden();
} elseif (!isset($id)) {
	Flight::badRequest('id');
} elseif (!isset($data['item_name'])) {
	Flight::badRequest('item_name');
}

$query = $DB->prepare(" UPDATE my_items
						SET item_name = :item_name
						WHERE item_id = :item_id;");

$query->bindParam(':item_id', $id, PDO::PARAM_INT);
$query->bindParam(':item_name', $data['item_name'], PDO::PARAM_STR);
if (!$query->execute()) {
	Flight::error(
		new Exception(implode(' ', array_slice($query->errorInfo(), 2)))
	);
}

Flight::json([
	'data' => [
		'message' => 'updated',
		'time' => $now,
		'rowCount' => $query->rowCount(),
	],
]);
