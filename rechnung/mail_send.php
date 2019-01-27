<?php
// NOTE: this implementation currently uses a free GMAIL account
// this is limited to 99 messages per day
// For the final product you should use your own custom mail server
// you only need to change the host parameter and username and password

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
require_once('../lib/phpmailer/PHPMailerAutoload.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // PHPMailer setup:
    $mail = new PHPMailer(true);    // true enables exceptions

    try {
        $mail->CharSet ="UTF-8";
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com'; // change this to your own host
        $mail->Port = '465';
        $mail->isHTML();
        $mail->Username = 'naturfreundelinz@gmail.com'; // change this
        $mail->Password = 'nfl!nz77';                   // change this
        $mail->SetFrom('noreply@naturfreunde.at', 'noreply@naturfreunde.at');
        

        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        //$newUserID = $buchung->getUserID();

        // set buchung property values
        $mail->AddAddress($data->receiver);
        $mail->Subject = $data->subject;

        // $string = nl2br($data->message);
        // $mail->Body = "$string";
        $mail->Body = $data->message;
        
        $mail->addAttachment('img.jpg', 'rechnung.jpg');    // Optional name
        //$mail->addStringAttachment("rechnung", 'myfile.pdf');

        //$buchung->buchungsDatum = date('Y-m-d H:i:s');
        //$buchung->zahlungsDatum = date('Y-m-d');

        
        // send the mail
        if($mail->Send()) {
            echo '{';
                echo '"success": "Sent message."';
            echo '}';
        } else {
            echo '{';
                echo '"problem": "Unable to send mail."';
            echo '}';
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}


?>