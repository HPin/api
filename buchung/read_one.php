<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/buchung.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare huette object
$buchung = new Buchung($db);
 
// set ID property of huette to be edited
$buchung->buchungID = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of huette to be edited
$buchung->readOne();
 
// create array
$buchung_arr=array(
    "buchungID" => $buchung->buchungID,
    "buchenderID" => $buchung->buchenderID,
    "buchenderVorname" => $buchung->buchenderVorname,
    "buchenderNachname" => $buchung->buchenderNachname,
    "huetteID" => $buchung->huetteID,
    "zimmerID" => $buchung->zimmerID,
    "erwachsene" => $buchung->erwachsene,
    "kinder" => $buchung->kinder,
    "checkinDatum" => $buchung->checkinDatum,
    "checkoutDatum" => $buchung->checkoutDatum,
    "buchungsDatum" => $buchung->buchungsDatum,
    "zahlungsDatum" => $buchung->zahlungsDatum,
    "zahlungsartID" => $buchung->zahlungsartID
);
 
// make it json format
print_r(json_encode($buchung_arr));
?>