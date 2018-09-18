<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/huette.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$huette = new Huette($db);

$huette->huetteID = isset($_GET['id']) ? $_GET['id'] : die();
 
// query products
$stmt = $huette->readZimmer();
$num = $stmt->rowCount();

 
// check if more than 0 record found
if($num>0){
 
    // products array
    $huetten_arr=array();
    $huetten_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $huette_item=array(
            "zimmerID" => $zimmerID
        );
 
        array_push($huetten_arr["records"], $huette_item);
    }
 
    echo json_encode($huetten_arr);
}
 
else{
    echo json_encode(
        array("message" => "Keine Huetten gefunden.")
    );
}
?>