<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/mitreisender.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare huette object
$object = new Mitreisender($db);
 
// set ID property of huette to be edited
$object->buchungID = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of huette to be edited
$stmt = $object->readForBooking();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $object_arr=array();
    $object_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $object_item=array(
            "mitreisenderID" => $mitreisenderID,
            "buchungID" => $buchungID,
            "vorname" => $vorname,
            "nachname" => $nachname,
            "geburtsdatum" => $geburtsdatum,
            "mitglied" => $mitglied
        );
 
        array_push($object_arr["records"], $object_item);
    }
 
    echo json_encode($object_arr);
}
else{
    echo json_encode(
        array("message" => "No objects found")
    );
}

?>