<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/rechnung.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare object
$object = new Rechnung($db);
 
// set ID property of object to be edited
$object->rechnungID = isset($_GET['id']) ? $_GET['id'] : die();
 
// read properties of object:
$object->readOne();

// create pdf
$object->downloadPDF();

echo 'created';
?>