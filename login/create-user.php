<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database
include_once '../config/database.php';
include_once '../objects/buchender.php';

$database = new Database();
$db = $database->getConnection();
 
$buchender = new Buchender($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set property values
$buchender->vorname = $data->vorname;
$buchender->nachname = $data->nachname;
$buchender->adresse = $data->adresse;
$buchender->plz = $data->plz;
$buchender->ort = $data->ort;
$buchender->telefonnummer = $data->telefonnummer;
$buchender->mail = $data->mail;

 
// create the buchung
if($buchender->create()){
    echo '{';
        echo '"message": "User was created."';
    echo '}';
}
 
// if unable to create the buchung, tell the user
else{
    echo '{';
        echo '"message": "Unable to create User."';
    echo '}';
}
?>