<?php

require_once('../lib/phpmailer/PHPMailerAutoload.php');
require_once '../lib/dompdf/lib/html5lib/Parser.php';
require_once '../lib/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once '../lib/dompdf/lib/php-svg-lib/src/autoload.php';
require_once '../lib/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

class Rechnung {
 
    // database connection and table name
    private $conn;
    private $table_name = "rechnung";
 
    // object properties
    public $rechnungID;
    public $huetteID;

    // mail properties
    public $receiver;
    public $subject;
    public $message;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function send() {
        $html_code = '
        <!doctype html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <title>Rechnung</title>
            <link rel="stylesheet" href="invoice.css">
        </head>
        <body>

        <table width="100%">
            <tr>
                <td valign="top"><img src="logo.jpg" alt="" width="150"/></td>
                <td align="right">
                    <h3>Rohrauerhaus</h3>
                    <pre>
                        Max Musterpächter
                        Grünau 40
                        4582 Spital am Pyhrn
                        066412345678
                        max@rohrauerhaus.at
                    </pre>
                </td>
            </tr>

        </table>

        <table width="100%">
            <tr>
                <td><strong>Datum:</strong> 23.01.2019</td>
            </tr>
        </table>

        <h1>Rechnung Nr. 13</h1>

        <table width="100%">
            <thead style="background-color: lightgray;">
            <tr>
                <th>#</th>
                <th>Beschreibung</th>
                <th>Menge</th>
                <th>Preis pro Einheit €</th>
                <th>Gesamt €</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Übernachtung Erwachsene</td>
                <td align="right">6</td>
                <td align="right">50,00</td>
                <td align="right">300,00</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>Frühstück</td>
                <td align="right">10</td>
                <td align="right">7,50</td>
                <td align="right">75,00</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>Anzahlung</td>
                <td align="right"></td>
                <td align="right"></td>
                <td align="right">-75,00</td>
            </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td align="right">Netto €</td>
                    <td align="right">240,00</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td align="right">Steuer €</td>
                    <td align="right">60,00</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td align="right">Gesamt (brutto) €</td>
                    <td align="right" class="gray">300,00</td>
                </tr>
            </tfoot>
        </table>

        </body>
        </html>
        ';


        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html_code);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $file_name = md5(rand()) . '.pdf';
        $file = $dompdf->output();
        file_put_contents($file_name, $file);


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
            $mail->AddAddress($this->receiver);
            $mail->Subject = $this->subject;

            // $string = nl2br($data->message);
            // $mail->Body = "$string";
            $mail->Body = $this->message;
            
            $mail->addAttachment($file_name, 'rechnung.pdf');    // Optional name
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

    // read bookings
    function read() {
     
        // select all query
        $query = "SELECT * FROM rechnung";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // read one specific entry
    function readOne(){
     
        // query to read single record
        $query = $query = "SELECT * FROM " . $this->table_name . " r
                    WHERE r.rechnungID = ?
                    LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->buchungID);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->rechnungID = $row['rechnungID'];
    }

    // create new entry
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    huetteID=:huetteID, zimmerID=:zimmerID, erwachsene=:erwachsene, jugendliche=:jugendliche, kinder=:kinder,
                    checkinDatum=:checkinDatum, checkoutDatum=:checkoutDatum, buchungsDatum=:buchungsDatum, preis=:preis, zahlungsDatum=:zahlungsDatum,
                    zahlungsartID=:zahlungsartID, fruehstuecksanzahl=:fruehstuecksanzahl, bvorname=:bvorname, bnachname=:bnachname,
                    bgeburtsdatum=:bgeburtsdatum, badresse=:badresse, bplz=:bplz, bort=:bort, btelefonnummer=:btelefonnummer,
                    bmail=:bmail, bmitglied=:bmitglied
                    ";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->huetteID=htmlspecialchars(strip_tags($this->huetteID));
        $this->zimmerID=htmlspecialchars(strip_tags($this->zimmerID));
        $this->erwachsene=htmlspecialchars(strip_tags($this->erwachsene));
        $this->jugendliche=htmlspecialchars(strip_tags($this->jugendliche));
        $this->kinder=htmlspecialchars(strip_tags($this->kinder));
        $this->checkinDatum=htmlspecialchars(strip_tags($this->checkinDatum));
        $this->checkoutDatum=htmlspecialchars(strip_tags($this->checkoutDatum));
        $this->buchungsDatum=htmlspecialchars(strip_tags($this->buchungsDatum));
        $this->preis=htmlspecialchars(strip_tags($this->preis));
        $this->zahlungsDatum=htmlspecialchars(strip_tags($this->zahlungsDatum));
        $this->zahlungsartID=htmlspecialchars(strip_tags($this->zahlungsartID));
        $this->fruehstuecksanzahl = htmlspecialchars(strip_tags($this->fruehstuecksanzahl));
        $this->bvorname = htmlspecialchars(strip_tags($this->bvorname));
        $this->bnachname = htmlspecialchars(strip_tags($this->bnachname));
        $this->bgeburtsdatum = htmlspecialchars(strip_tags($this->bgeburtsdatum));
        $this->badresse = htmlspecialchars(strip_tags($this->badresse));
        $this->bplz = htmlspecialchars(strip_tags($this->bplz));
        $this->bort = htmlspecialchars(strip_tags($this->bort));
        $this->btelefonnummer = htmlspecialchars(strip_tags($this->btelefonnummer));
        $this->bmail = htmlspecialchars(strip_tags($this->bmail));
        $this->bmitglied = htmlspecialchars(strip_tags($this->bmitglied));


     
        // bind values
        $stmt->bindParam(":huetteID", $this->huetteID);
        $stmt->bindParam(":zimmerID", $this->zimmerID);
        $stmt->bindParam(":erwachsene", $this->erwachsene);
        $stmt->bindParam(":jugendliche", $this->jugendliche);
        $stmt->bindParam(":kinder", $this->kinder);
        $stmt->bindParam(":checkinDatum", $this->checkinDatum);
        $stmt->bindParam(":checkoutDatum", $this->checkoutDatum);
        $stmt->bindParam(":buchungsDatum", $this->buchungsDatum);
        $stmt->bindParam(":preis", $this->preis);
        $stmt->bindParam(":zahlungsDatum", $this->zahlungsDatum);
        $stmt->bindParam(":zahlungsartID", $this->zahlungsartID);
        $stmt->bindParam(":fruehstuecksanzahl", $this->fruehstuecksanzahl);
        $stmt->bindParam(":bvorname", $this->bvorname);
        $stmt->bindParam(":bnachname", $this->bnachname);
        $stmt->bindParam(":bgeburtsdatum", $this->bgeburtsdatum);
        $stmt->bindParam(":badresse", $this->badresse);
        $stmt->bindParam(":bplz", $this->bplz);
        $stmt->bindParam(":bort", $this->bort);
        $stmt->bindParam(":btelefonnummer", $this->btelefonnummer);
        $stmt->bindParam(":bmail", $this->bmail);
        $stmt->bindParam(":bmitglied", $this->bmitglied);
     
        // execute query
        if($stmt->execute()){
            $this->buchungID = $this->conn->insert_id;
            return true;
        }
     
        return false;
    }


    /*
    // update
    function update(){
     
        $uID = $this->conn->lastInsertId();

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    buchenderID=$uID
                WHERE
                    buchungID = :buchungID";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->buchenderID=htmlspecialchars(strip_tags($this->buchenderID));
     
        // bind new values
        $stmt->bindParam(':buchenderID', $this->buchenderID);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }
    */

}
?>