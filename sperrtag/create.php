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
include_once '../objects/sperrtag.php';
 
$database = new Database();
$db = $database->getConnection();
 
$object = new Sperrtag($db);
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // set object property values
    $object->startDatum = $data->startDatum;
    $object->endDatum = $data->endDatum;
    $object->huetteID = $data->huetteID;
    $object->info = $data->info;

    // create the object
    if($object->create()){
        echo '{';
            echo '"message": "object was created."';
        echo '}';
    }
    
    // if unable to create the object, tell the user
    else{
        echo '{';
            echo '"message": "Unable to create Booking."';
        echo '}';
    }
}
?>