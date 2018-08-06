<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/zimmer.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$zimmer = new Zimmer($db);
 
// query 
$stmt = $zimmer->read();
$num = $stmt->rowCount();

 
// check if more than 0 records found
if($num>0){
 
    // products array
    $zimmer_arr=array();
    $zimmer_arr["records"]=array();
    
    

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $zimmer_item=array(
            "zimmerID" => $zimmerID,
            "zimmerkategorieID" => $zimmerkategorieID,
            "huetteID" => $huetteID,
            "plaetze" => $plaetze,
            "bezeichnung" => $bezeichnung
        );
 
        array_push($zimmer_arr["records"], $zimmer_item);
    }
 
    echo json_encode($zimmer_arr);
}
 
else{
    echo json_encode(
        array("message" => "Keine Zimmer gefunden.")
    );
}
?>