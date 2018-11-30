<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// include database
include_once '../config/database.php';

if(!isset($_POST)) die();

session_start();
$response = [];
$database = new Database();
$conn = $database->getConnection();
$table_name = "paechter";

// get posted data
$data = file_get_contents("php://input");
$json = json_decode($data, true); // decode the JSON into an associative array

$username = $json['username'];
$password = $json['password'];

$query = "SELECT * FROM " . $table_name . " WHERE mail=? AND passwort=?";

// prepare query
$stmt = $conn->prepare($query);

// sanitize
$username=htmlspecialchars(strip_tags($username));
$password=htmlspecialchars(strip_tags($password));

// bind values
$stmt->bindParam(1, $username);
$stmt->bindParam(2, $password);

// execute query with previously bound parameters
$stmt->execute();

// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// extract information and set flags
if ($row['mail'] != '') {
	$response['status'] = 'loggedin';
	$response['huetteID'] = $row['huetteID'];
	$response['firstname'] = $row['vorname'];
	$response['lastname'] = $row['nachname'];
	$response['phone'] = $row['telefonnummer'];
	$response['user'] = $username;
	$response['uuid'] = md5(uniqid());
	$_SESSION['uuid'] = $response['uuid'];
	$_SESSION['user'] = $username;
} else {
	$response['status'] = 'error';
}

echo json_encode($response);