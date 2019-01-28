<?php

include_once '../config/database.php';
include_once 'huette.php';
include_once 'buchung.php';
include_once 'zimmer.php';

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
    public $buchungID;
    public $datum;

    // mail properties
    public $receiver;
    public $subject;
    public $message;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function send() {
        $database = new Database();
        $db = $database->getConnection();

        // create huette object and fill its parameters
        $huette = new Huette($db);
        $huette->huetteID = $this->huetteID;
        $huette->readOne();

        // create buchung object and fill its parameters
        $buchung = new Buchung($db);
        $buchung->buchungID = $this->buchungID;
        $buchung->readOne();

        // get one room and fill its parameters
        $zimmer = new Zimmer($db);
        $zimmer->zimmerID = $buchung->zimmerID;
        $zimmer->readOne();

        // get number of nights:
        $date1 = new DateTime($buchung->checkinDatum);
        $date2 = new DateTime($buchung->checkoutDatum);
        $numberOfNights= $date2->diff($date1)->format("%a"); 

        // invoice date format:
        $this->datum = date_create($this->datum);

        // get prices for the stay
        $fruehstueckGesamtpreis = $buchung->fruehstuecksanzahl * $huette->fruehstueckspreis * $numberOfNights;

        $erwachseneNights = $buchung->erwachsene * $numberOfNights;
        $erwachseneGesamtpreis = $zimmer->preisErw * $erwachseneNights;

        $jugendlicheNights = $buchung->jugendliche * $numberOfNights;
        $jugendlicheGesamtpreis = $zimmer->preisJgd * $jugendlicheNights;

        $kinderNights = $buchung->kinder * $numberOfNights;
        $kinderGesamtpreis = 0;

        $anzahlung = ($fruehstueckGesamtpreis + $erwachseneGesamtpreis + $jugendlicheGesamtpreis + $kinderGesamtpreis) * 0.5;

        $gesamtpreisBrutto = $fruehstueckGesamtpreis + $erwachseneGesamtpreis + $jugendlicheGesamtpreis + $kinderGesamtpreis - $anzahlung;
        $gesamtpreisNetto = $gesamtpreisBrutto / 1.1;
        $steuer = $gesamtpreisBrutto - $gesamtpreisNetto;

        $itemCounter = 0;
        $formatter = new NumberFormatter('de_AT',  NumberFormatter::CURRENCY);

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
                    <h3>'.$huette->name.'</h3>
                    <pre>
                        Max Musterpächter
                        '.$huette->adresse.'
                        '.$huette->plz.' '.$huette->ort.'
                        '.$huette->telefonnummer.'
                        '.$huette->mail.'
                    </pre>
                </td>
            </tr>

        </table>

        <table width="100%">
            <tr>
                <td>'.$buchung->bvorname.' '.$buchung->bnachname.'</td>
            </tr>
            <tr>
                <td>'.$buchung->badresse.'</td>
            </tr>
            <tr>
                <td>'.$buchung->bplz.' '.$buchung->bort.'</td>
            </tr>
        </table>

        <br>
        <p><strong>Rechnungsdatum:</strong> '.date_format($this->datum,"d.m.Y").'</p>

        <h1>Rechnung Nr. '.$this->rechnungID.'</h1>

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
        ';


        if ($fruehstueckGesamtpreis > 0) {
            $itemCounter++;
            $html_code .= '
            <tr>
                <th scope="row">'.$itemCounter.'</th>
                <td>Frühstück</td>
                <td align="right">'.$buchung->fruehstuecksanzahl.'</td>
                <td align="right">'.$formatter->formatCurrency($huette->fruehstueckspreis, 'EUR').'</td>
                <td align="right">'.$formatter->formatCurrency($fruehstueckGesamtpreis, 'EUR').'</td>
            </tr>
            ';
        }

        if ($erwachseneNights > 0) {
            $itemCounter++;
            $html_code .= '
            <tr>
                <th scope="row">'.$itemCounter.'</th>
                <td>Übernachtung Erwachsene</td>
                <td align="right">'.$erwachseneNights.'</td>
                <td align="right">'.$formatter->formatCurrency($zimmer->preisErw, 'EUR').'</td>
                <td align="right">'.$formatter->formatCurrency($erwachseneGesamtpreis, 'EUR').'</td>
            </tr>
            ';
        }

        if ($jugendlicheNights > 0) {
            $itemCounter++;
            $html_code .= '
            <tr>
                <th scope="row">'.$itemCounter.'</th>
                <td>Übernachtung Jugendliche</td>
                <td align="right">'.$jugendlicheNights.'</td>
                <td align="right">'.$formatter->formatCurrency($zimmer->preisJgd, 'EUR').'</td>
                <td align="right">'.$formatter->formatCurrency($jugendlicheGesamtpreis, 'EUR').'</td>
            </tr>
            ';
        }

        if ($kinderNights > 0) {
            $itemCounter++;
            $html_code .= '
            <tr>
                <th scope="row">'.$itemCounter.'</th>
                <td>Übernachtung Kinder</td>
                <td align="right">'.$kinderNights.'</td>
                <td align="right">€ 0,00</td>
                <td align="right">'.$formatter->formatCurrency($kinderGesamtpreis, 'EUR').'</td>
            </tr>
            ';
        }

        if ($anzahlung > 0) {
            $itemCounter++;
            $html_code .= '
            <tr>
                <th scope="row">'.$itemCounter.'</th>
                <td>Anzahlung</td>
                <td align="right"></td>
                <td align="right"></td>
                <td align="right">- '.$formatter->formatCurrency($anzahlung, 'EUR').'</td>
            </tr>
            ';
        }
            
        $html_code .= '
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td align="right">Netto €</td>
                    <td align="right">'.$formatter->formatCurrency($gesamtpreisNetto, 'EUR').'</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td align="right">10 % USt €</td>
                    <td align="right">'.$formatter->formatCurrency($steuer, 'EUR').'</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td align="right">Gesamt (brutto) €</td>
                    <td align="right" class="gray">'.$formatter->formatCurrency($gesamtpreisBrutto, 'EUR').'</td>
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
        $stmt->bindParam(1, $this->rechnungID);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->rechnungID = $row['rechnungID'];
        $this->huetteID = $row['huetteID'];
        $this->buchungID = $row['buchungID'];
        $this->datum = $row['datum'];
    }

    // create new entry
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    huetteID=:huetteID, buchungID=:buchungID, datum=:datum
                    ";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->huetteID=htmlspecialchars(strip_tags($this->huetteID));
        $this->buchungID=htmlspecialchars(strip_tags($this->buchungID));
        $this->datum=htmlspecialchars(strip_tags($this->datum));


     
        // bind values
        $stmt->bindParam(":huetteID", $this->huetteID);
        $stmt->bindParam(":buchungID", $this->buchungID);
        $stmt->bindParam(":datum", $this->datum);
     
        // execute query
        if($stmt->execute()){
            $this->rechnungID = $this->conn->insert_id;
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