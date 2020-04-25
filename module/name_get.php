<?php

$now = Date('Y-m-d H:i:s');
$DB = Flight::db();
$o_user = Flight::get('o_user');
$user_right = Flight::get('user_right');

if ($user_right[0] < 1) {
    Flight::json(array('message' => 'FORBIDDEN'), 403);
} elseif (isset($id)) {
    $query = $DB->prepare("	SELECT *
							FROM my_items
							WHERE my_items.item_id = :item_id;");
    $query->bindParam(':item_id', $id, PDO::PARAM_INT);
    if (!$query->execute()) {
        Flight::error(new Exception(implode(' ', array_slice($query->errorInfo(), 2))));
    }

    if ($query->rowCount() == 0) {
        Flight::json(array(
                            'message' => 'NOT_FOUND',
                            'key' => $id
        ), 404);
        die();
    }

    $item = $query->fetch(PDO::FETCH_OBJ);
    $item->item_id = intval($item->item_id);
    Flight::json(array('data' => array('item' => $item)));
} else {
    if (!isset($_GET['p'])) {
        $page = 1;
    } else {
        $page = $_GET['p'];
    }

    if (!isset($_GET['q'])) {
        $_GET['q'] = '';
    }
    $itemsPerPage = 15;
    $offset = (15 * ($page - 1));

    $query = $DB->prepare("	SELECT 	item_id AS id,
									item_name AS name
							FROM my_items
							WHERE item_id LIKE :q
							OR item_name LIKE :q
							ORDER BY item_id DESC
							LIMIT :offset, :itemsPerPage;");
    $query->bindvalue(':q', '%' . $_GET['q'] . '%', PDO::PARAM_STR);
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $query->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
    if (!$query->execute()) {
        Flight::error(new Exception(implode(' ', array_slice($query->errorInfo(), 2))));
    }

    $items = array();
    while ($row = $query->fetch(PDO::FETCH_OBJ)) {
        $item->id = intval($item->id);
        $items[] = $row;
    }

    Flight::json(array('data' => array(
                                    'pageIndex' => $page,
                                    'itemsPerPage' => $itemsPerPage,
                                    'items' => $items,
                                    'rowCount' => $query->rowCount()
                                )));
}
