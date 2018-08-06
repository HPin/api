<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/zimmer.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare huette object
$object = new Zimmer($db);
 
// set ID property of huette to be edited
$object->zimmerID = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of huette to be edited
$object->readOne();
 
// create array
$object_arr=array(
    "zimmerID" => $object->zimmerID,
    "zimmerkategorieID" => $object->zimmerkategorieID,
    "huetteID" => $object->huetteID,
    "plaetze" => $object->plaetze,
    "bezeichnung" => $object->bezeichnung
);
 
// make it json format
print_r(json_encode($object_arr));
?>