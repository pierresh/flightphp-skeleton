<?php

if ($user_right[0] < 2) {
	Flight::forbidden();
} elseif (!isset($id)) {
	Flight::badRequest('id');
} elseif (!isset($line)) {
	Flight::badRequest('line');
}

$query = $DB->prepare("	DELETE
						FROM my_items_details
						WHERE my_items_details.details_line = :line;");

$results = [];
$lines = explode(',', $line);

foreach ($lines as $value) {
	$query->bindvalue(':line', $value, PDO::PARAM_INT);
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
