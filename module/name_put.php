<?php

if ($user_right[0] < 2) {
	Flight::json(['message' => 'FORBIDDEN'], 403);
} elseif (!isset($id)) {
	Flight::json(['message' => 'BAD REQUEST', 'key' => 'id'], 400);
} elseif (!isset($data['item_name'])) {
	Flight::json(['message' => 'BAD REQUEST', 'key' => 'item_name'], 400);
} else {
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
}
