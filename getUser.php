<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';
require __DIR__.'/AuthMiddleware.php';
require __DIR__.'/classes/Log.php';

$status = $userId = "";
$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth($conn, $allHeaders);

$return = $auth->isValid();
if($return['success']==1){
	$status = "Success";
	$userId = $return['id'];
}else{
	$userId = null;
	$status = "Failed";
}

$log = new Log($userId,$status);
$log->addLog();
echo json_encode($return);