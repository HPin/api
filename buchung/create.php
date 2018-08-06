<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate buchung object
include_once '../objects/buchung.php';
 
$database = new Database();
$db = $database->getConnection();
 
$buchung = new Buchung($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
//$newUserID = $buchung->getUserID();

// set buchung property values
$buchung->huetteID = $data->huetteID;
$buchung->zimmerID = $data->zimmerID;
$buchung->buchenderID = $data->buchenderID;
$buchung->erwachsene = $data->erwachsene;
$buchung->kinder = $data->kinder;
$buchung->checkinDatum = $data->checkinDatum;
$buchung->checkoutDatum = $data->checkoutDatum;
$buchung->buchungsDatum = date('Y-m-d H:i:s');
$buchung->zahlungsDatum = date('Y-m-d');
$buchung->zahlungsartID = $data->zahlungsartID;


// create the buchung
if($buchung->create()){
    echo '{';
        echo '"message": "Buchung was created."';
    echo '}';
}
 
// if unable to create the buchung, tell the user
else{
    echo '{';
        echo '"message": "Unable to create Buchung."';
    echo '}';
}
?>