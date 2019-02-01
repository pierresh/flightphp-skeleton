<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");         
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
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
		require_once('./db_connect.php'); // db_connect contains the 4 functions DBconnect, ObjectUser, UserRights, checkSession

		$DB = DBconnect($jwt);
		Flight::set('DB', $DB);
		
		if ($DB != false) {
			$o_user = ObjectUser($jwt);
			$user_right = UserRights($o_user->role);

			Flight::set('o_user', $o_user);
			Flight::set('user_right', $user_right);
		} else {
			$session = checkSession($jwt);
			Flight::json(array(	'message'=>'SESSION_EXPIRED',
								'keys'=>$session
							), 401);
			die();
		}
	}

/* ************ */

Flight::route('/', function(){
    echo 'Welcome to Flight PHP skeleton!';
});

Flight::route('GET /@module/@name', function($module, $name){ 
	if (file_exists('./'.$module.'/'.$name.'_get.php')) { require_once('./'.$module.'/'.$name.'_get.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND GET #2', 'more'=>'./'.$module.'/'.$name.'_get.php'), 501); }
});
Flight::route('GET /@module/@name/@id', function($module, $name, $id){ 
	if (file_exists('./'.$module.'/'.$name.'_get.php')) { require_once('./'.$module.'/'.$name.'_get.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND GET #3', 'url'=>'./'.$module.'/'.$name.'_get.php'), 501); }
});
Flight::route('GET /@module/@name/@id/@sub_name', function($module, $name, $id, $sub_name){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_get.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_get.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND GET #4', 'url'=>'./'.$module.'/'.$name.'_'.$sub_name.'_get.php'), 501); }
});

Flight::route('GET /@module/@name/@id/@sub_name/@line', function($module, $name, $id, $sub_name, $line){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_get.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_get.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND GET #5', 'url'=>'./'.$module.'/'.$name.'_'.$sub_name.'_get.php'), 501); }
});

Flight::route('POST /@name/', function($name){ 
	if (file_exists('./'.$name.'_post.php')) { require_once('./'.$name.'_post.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND POST #1'), 501); }
});
Flight::route('POST /@module/@name/', function($module, $name){ 
	if (file_exists('./'.$module.'/'.$name.'_post.php')) { require_once('./'.$module.'/'.$name.'_post.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND POST #2'), 501); }
});
Flight::route('POST /@module/@name/@id', function($module, $name, $id){ 
	if (file_exists('./'.$module.'/'.$name.'_post.php')) { require_once('./'.$module.'/'.$name.'_post.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND POST #3'), 501); }
});
Flight::route('POST /@module/@name/@id/@sub_name', function($module, $name, $id, $sub_name){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_post.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_post.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND POST #4', 'url'=>'./'.$module.'/'.$name.'_'.$sub_name.'_post.php'), 501); }
});


Flight::route('PUT /@module/@name/', function($module, $name){ 
	if (file_exists('./'.$module.'/'.$name.'_put.php')) { require_once('./'.$module.'/'.$name.'_put.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND PUT #2', 'url'=>'./'.$module.'/'.$name.'_put.php'), 501); }
});
Flight::route('PUT /@module/@name/@id', function($module, $name, $id){ 
	if (file_exists('./'.$module.'/'.$name.'_put.php')) { require_once('./'.$module.'/'.$name.'_put.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND PUT #3', 'url'=>'./'.$module.'/'.$name.'_put.php'), 501); }
});
Flight::route('PUT /@module/@name/@id/@sub_name', function($module, $name, $id, $sub_name){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_put.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_put.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND PUT #4', 'url'=>'./'.$module.'/'.$name.'_'.$sub_name.'_put.php'), 501); }
});
Flight::route('PUT /@module/@name/@id/@sub_name/@line', function($module, $name, $id, $sub_name, $line){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_put.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_put.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND PUT #5', 'url'=>'./'.$module.'/'.$name.'_'.$sub_name.'_put.php'), 501); }
});


Flight::route('PATCH /@module/@name/@id', function($module, $name, $id){ 
	if (file_exists('./'.$module.'/'.$name.'_patch.php')) { require_once('./'.$module.'/'.$name.'_patch.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND PATCH #3', 'key'=>'./'.$module.'/'.$name.'_patch.php'), 501); }
});
Flight::route('PATCH /@module/@name/@id/@sub_name', function($module, $name, $id, $sub_name){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_patch.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_patch.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND PATCH #4', 'key'=>'./'.$module.'/'.$name.'_'.$sub_name.'_patch.php' ), 501); }
});

Flight::route('PATCH /@module/@name/@id/@sub_name/@line', function($module, $name, $id, $sub_name, $line){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_patch.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_patch.php'); }
	else { Flight::json(array( 'message'=>'NOT_FOUND PATCH #5', 'key'=>'./'.$module.'/'.$name.'_'.$sub_name.'_patch.php' ), 501); }
});

Flight::route('DELETE /@name/', function($name){ 
	if (file_exists('./'.$name.'_delete.php')) { require_once('./'.$name.'_delete.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND DELETE #1'), 501); }
});
Flight::route('DELETE /@module/@name/@id', function($module, $name, $id){ 
	if (file_exists('./'.$module.'/'.$name.'_delete.php')) { require_once('./'.$module.'/'.$name.'_delete.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND DELETE #3', 'file'=>'./'.$module.'/'.$name.'_delete.php' ), 501); }
});

Flight::route('DELETE /@module/@name/@id/@sub_name/@line', function($module, $name, $id, $sub_name, $line){ 
	if (file_exists('./'.$module.'/'.$name.'_'.$sub_name.'_delete.php')) { require_once('./'.$module.'/'.$name.'_'.$sub_name.'_delete.php'); }
	else { Flight::json(array('message'=>'NOT_FOUND DELETE #5', 'file'=>'./'.$module.'/'.$name.'_'.$sub_name.'_delete.php' ), 501); }
});

Flight::map('error', function($ex){
	Flight::json(array('message'=>basename($ex->getFile()).': line '.$ex->getLine().': '.$ex->getMessage()), 500);
});

Flight::map('notFound', function(){
	Flight::json(array('message'=>'ROUTE NOT FOUND'), 404);
});

Flight::start();
?>