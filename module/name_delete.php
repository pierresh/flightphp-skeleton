<?php

if ($user_right[0] < 2) {
    Flight::json(array('message' => 'FORBIDDEN'), 403);
} elseif (!isset($id)) {
    Flight::json(array('message' => 'BAD_REQUEST', 'key' => 'id'), 400);
} else {
    $query = $DB->prepare("	DELETE
							FROM my_items
							WHERE my_items.item_id = :id;");

    $results = array();
    $ids = explode(',', $id);

    foreach ($ids as $value) {
        $query->bindvalue(':id', $value, PDO::PARAM_INT);
        if (!$query->execute()) {
            Flight::error(new Exception(implode(' ', array_slice($query->errorInfo(), 2))));
        } else {
            if ($query->rowCount() > 0) {
                $results[] = $value;
            }
        }
    }

    Flight::json(array('data' => array(
                                        'message' => 'deleted',
                                        'results' => $results,
                                        'time' => $now
                                    )));
}
