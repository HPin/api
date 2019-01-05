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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    //$newUserID = $buchung->getUserID();

    // set buchung property values
    $buchung->huetteID = $data->huetteID;
    $buchung->zimmerID = $data->zimmerID;
    $buchung->erwachsene = $data->erwachsene;
    $buchung->jugendliche = $data->jugendliche;
    $buchung->kinder = $data->kinder;
    $buchung->checkinDatum = $data->checkinDatum;
    $buchung->checkoutDatum = $data->checkoutDatum;
    $buchung->buchungsDatum = date('Y-m-d H:i:s');
    $buchung->preis = $data->preis;
    $buchung->zahlungsDatum = date('Y-m-d');
    $buchung->zahlungsartID = $data->zahlungsartID;
    $buchung->fruehstuecksanzahl = $data->fruehstuecksanzahl;
    $buchung->bvorname = $data->bvorname;
    $buchung->bnachname = $data->bnachname;
    $buchung->bgeburtsdatum = $data->bgeburtsdatum;
    $buchung->badresse = $data->badresse;
    $buchung->bplz = $data->bplz;
    $buchung->bort = $data->bort;
    $buchung->btelefonnummer = $data->btelefonnummer;
    $buchung->bmail = $data->bmail;
    $buchung->bmitglied = $data->bmitglied;


    // create the buchung
    if($buchung->create()){
        // create array
        $buchung_arr=array(
            "buchungID" => $buchung->buchungID,
            "huetteID" => $buchung->huetteID,
            "zimmerID" => $buchung->zimmerID,
            "erwachsene" => $buchung->erwachsene,
            "jugendliche" => $buchung->jugendliche,
            "kinder" => $buchung->kinder,
            "checkinDatum" => $buchung->checkinDatum,
            "checkoutDatum" => $buchung->checkoutDatum,
            "buchungsDatum" => $buchung->buchungsDatum,
            "preis" => $buchung->preis,
            "zahlungsDatum" => $buchung->zahlungsDatum,
            "zahlungsartID" => $buchung->zahlungsartID,
            "fruehstuecksanzahl" => $buchung->fruehstuecksanzahl,
            "bvorname" => $buchung->bvorname,
            "bnachname" => $buchung->bnachname,
            "bgeburtsdatum" => $buchung->bgeburtsdatum,
            "badresse" => $buchung->badresse,
            "bplz" => $buchung->bplz,
            "bort" => $buchung->bort,
            "btelefonnummer" => $buchung->btelefonnummer,
            "bmail" => $buchung->bmail,
            "bmitglied" => $buchung->bmitglied
        );
        
        // make it json format
        print_r(json_encode($buchung_arr));
    }
    
    // if unable to create the buchung, tell the user
    else{
        echo '{';
            echo '"message": "Unable to create Booking."';
        echo '}';
    }
}
?>