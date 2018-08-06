<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/buchung.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$buchung = new Buchung($db);

$buchung->bookingMonth = isset($_GET['month']) ? $_GET['month'] : die();
$buchung->bookingYear = isset($_GET['year']) ? $_GET['year'] : die();
 
// query products
$stmt = $buchung->readMonth();
$num = $stmt->rowCount();

 
// check if more than 0 record found
if($num>0){
 
    // products array
    $buchung_arr=array();
    $buchung_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $buchung_item=array(
            "buchungID" => $buchungID,
            "buchenderID" => $buchenderID,
            "buchenderVorname" => $buchenderVorname,
            "buchenderNachname" => $buchenderNachname,
            "huetteID" => $huetteID,
            "zimmerID" => $zimmerID,
            "erwachsene" => $erwachsene,
            "kinder" => $kinder,
            "checkinDatum" => $checkinDatum,
            "checkoutDatum" => $checkoutDatum,
            "buchungsDatum" => $buchungsDatum,
            "zahlungsDatum" => $zahlungsDatum,
            "zahlungsartID" => $zahlungsartID
        );
 
        array_push($buchung_arr["records"], $buchung_item);
    }
 
    echo json_encode($buchung_arr);
}
 
else{
    echo json_encode(
        array("message" => "Keine Buchungen gefunden")
    );
}
?>