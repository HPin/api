<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate object
include_once '../objects/rechnung.php';
 
$database = new Database();
$db = $database->getConnection();
 
$object = new Rechnung($db);
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // set object property values
    $object->receiver = $data->receiver;
    $object->subject = $data->subject;
    $object->message = $data->message;

    // create invoice and send mail
    $object->send();
}
?>