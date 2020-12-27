<?php

// Input data should be:
// 1. field (which column of the table will be modified)
// 2. value (the new value for that field)

if ($user_right[0] < 2) {
	Flight::forbidden();
} elseif (!isset($id)) {
	Flight::badRequest('id');
} elseif (!isset($data['field'])) {
	Flight::badRequest('field');
} elseif (!isset($data['value'])) {
	Flight::badRequest('value');
}

// In order avoid to set a lot of queries (one by field potentially modified by this API), the field is sanitized then will be used in the query
// Inspired from https://stackoverflow.com/questions/23482104/can-i-use-a-pdo-prepared-statement-to-bind-an-identifier-a-table-or-field-name
$allowed = ['item_name', 'item_status'];

$index = array_search($data['field'], $allowed);

if ($index === false) {
	Flight::error(new Exception('Field not found: ' . $data['field']));
	die();
}

// The query is executed only if the $data['field'] has been found in $allowed
$query = $DB->prepare(" UPDATE my_items
						SET " . $allowed[$index] . " = :value
						WHERE item_id = :item_id;"
);

$results = [];
$ids = explode(',', $id);

foreach ($ids as $value) {
	$query->bindParam(':item_id', $value, PDO::PARAM_INT);
	$query->bindParam(':value', $data['value'], PDO::PARAM_STR);
	if (!$query->execute()) {
		Flight::error(new Exception(errorInfo($query)));
	}

	if ($query->rowCount() > 0) {
		$results[] = $value;
	}
}

Flight::json([
	'data' => [
		'message' => 'updated',
		'results' => $results,
		'time' => $now,
	],
]);
