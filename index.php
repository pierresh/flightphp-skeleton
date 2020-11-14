<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
		header(
			'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS'
		);
	}
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
		header(
			"Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}"
		);
	}
	exit(0);
}

require './vendor/autoload.php';

/* ************
// Here is the place to:
// 1. handle the Authorization
// 2. create the database connection, set $DB to false is the user is not authorized
// 3. get the connected user data
// 4. get the user rights for the connected user

// Example:
    $headers = getallheaders();
    if (isSet($headers['Authorization'])) {
        list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
        require_once './db_connect.php'; // db_connect contains the 4 functions DBconnect, ObjectUser, UserRights, checkSession

        // DBconnect return true if the connection is successul and the database connection is then registered as folllow:
        // try {
        //	Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=test','user','pass'));
		// }
		// catch(Exception $e) {
		//	Flight::error($e);
		// }

        if (DBconnect($jwt) != false) {
            $o_user = ObjectUser($jwt);
            $user_right = UserRights($o_user->role);

            Flight::set('o_user', $o_user);
            Flight::set('user_right', $user_right);
        } else {

            // checkSession is used to determine why the session is expired (ie. connected from another device)
            $session = checkSession($jwt);
            Flight::json(array( 'message'=>'SESSION_EXPIRED',
                                'keys'=>$session
                            ), 401);
            die();
        }
    } else {
        // In case there is a login, we can redirect to the file that manage the authentification
        require_once './login.php';
        die();
    }

/* ************ */

Flight::route('/', function () {
	echo 'Welcome to Flight PHP skeleton!';
});

Flight::route(
	'GET|POST|PUT|DELETE|PATCH /@module/@name(/@id(/@sub_name(/@line)))',
	function ($module, $name, $id, $sub_name, $line) {
		$request = Flight::request();
		if ($line != '' || $sub_name != '') {
			$api_file = './' . $module . '/' . $name . '_' . $sub_name . '_' . strtolower($request->method) . '.php';
		} else {
			$api_file = './' . $module . '/' . $name . '_' . strtolower($request->method) . '.php';
		}
		if (file_exists($api_file)) {
			$now = Date('Y-m-d H:i:s');
			$DB = Flight::db();
			$r = Flight::request();
			$data = $r->data->getData();

			$o_user = Flight::get('o_user');
			$user_right = Flight::get('user_right');

			require_once $api_file;
		} else {
			Flight::json(
				[
					'error' => [
						'code' => 501,
						'message' => 'NOT_FOUND ' . $request->method,
						'more' => $api_file,
					],
				],
				501
			);
		}
	}
);

Flight::map('error', function ($ex) {
	// Need to record log of the error meet by the API

	$DB = Flight::db();
	$o_user = Flight::get('o_user');

	$result = $DB->prepare("INSERT INTO log_error (error_datetime, user_id, error_file, error_message)
							VALUES (NOW(), :user_id, :error_file, :error_message);");
	$result->bindvalue(':user_id', $o_user->user_id, PDO::PARAM_INT);
	$result->bindvalue(':error_file', basename($ex->getFile()), PDO::PARAM_STR);
	$result->bindvalue(':error_message', 'line ' . $ex->getLine() . ': ' . trim($ex->getMessage()), PDO::PARAM_STR);
	$result->execute();

	Flight::json('API ERROR', 500);
	exit();
});

Flight::map('notFound', function () {
    Flight::json('RESOURCE NOT FOUND', 404);
    exit();
});

Flight::start();
