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
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set property values
//$user->userID = $data->userID;
$user->vorname = $data->vorname;
$user->nachname = $data->nachname;
$user->geburtsdatum = $data->geburtsdatum;
$user->adresse = $data->adresse;
$user->plz = $data->plz;
$user->ort = $data->ort;
$user->telefonnummer =  $data->telefonnummer;
$user->mail = $data->mail;

 
$newUserID = $user->create();
echo "$newUserID";

// create

/*
if($user->create()){
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
*/
?>