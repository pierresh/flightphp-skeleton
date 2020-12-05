<?php

if ($user_right[0] < 2) {
	Flight::forbidden();
} elseif (!isset($id)) {
	Flight::badRequest('id');
}

$query = $DB->prepare("	DELETE
						FROM my_items
						WHERE my_items.item_id = :id;");

$results = [];
$ids = explode(',', $id);

foreach ($ids as $value) {
	$query->bindvalue(':id', $value, PDO::PARAM_INT);
	if (!$query->execute()) {
		Flight::error(
			new Exception(implode(' ', array_slice($query->errorInfo(), 2)))
		);
	}

	if ($query->rowCount() > 0) {
		$results[] = $value;
	}
}

Flight::json([
	'data' => [
		'message' => 'deleted',
		'results' => $results,
		'time' => $now,
	],
]);
