<?php

if ($user_right[0] < 2) {
	Flight::forbidden();
} elseif (!isset($id)) {
	Flight::badRequest('id');
} elseif (!isset($line)) {
	Flight::badRequest('line');
}

/**
 * Check if there is any foreign key items that would create
 * a 500 error if we delete the items in the table my_items
 * The fk_items are extracted so that they could be displayed
 * in front-end to end-users. The http code returned is 409
 */
$check = $DB->prepare(" SELECT 	fk_item_code,
								fk_item_name
						FROM my_fk_items
						INNER JOIN my_items_details ON my_items_details.item_id = my_fk_items.item_id
						WHERE my_fk_items.item_id = :item_id
						ORDER BY fk_item_code, fk_item_name;");
$check->bindValue(':item_id', $id, PDO::PARAM_STR);
if (!$check->execute()) {
	Flight::error(
		new Exception(implode(' ', array_slice($check->errorInfo(), 2)))
	);
}

if ($check->rowCount() > 0) {
	$items = [];
	while ($row = $check->fetch(PDO::FETCH_OBJ)) {
		$items[] = $row;
	}

	// we return a type of conflict (delete) and related items
	Flight::conflict('delete', $items);
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
