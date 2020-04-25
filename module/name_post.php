<?php

$now = Date('Y-m-d H:i:s');
$DB = Flight::db();
$o_user = Flight::get('o_user');
$user_right = Flight::get('user_right');
$r = Flight::request();
$data = $r->data->getData();

if ($user_right[0] < 2) {
    Flight::json(array('message' => 'FORBIDDEN'), 403);
} elseif (!isset($data['my_required_data'])) {
    Flight::json(array('message' => 'BAD REQUEST', 'key' => 'my_required_data'), 400);
} else {
    $query = $DB->prepare("	INSERT INTO my_items (item_name)
							VALUES (:items_name);");
    $query->bindParam(':items_name', $data['items_name'], PDO::PARAM_STR);
    if (!$query->execute()) {
        Flight::error(new Exception(implode(' ', array_slice($query->errorInfo(), 2))));
    }

    $item_id = $DB->lastInsertId();

    Flight::json(array('data' => array(
                                    'message' => 'created',
                                    'time' => $now,
                                    'rowCount' => $query->rowCount(),
                                    'id' => intval($item_id)
                                )), 201);
}
