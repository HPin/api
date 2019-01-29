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
require_once '../lib/dompdf/lib/html5lib/Parser.php';
require_once '../lib/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once '../lib/dompdf/lib/php-svg-lib/src/autoload.php';
require_once '../lib/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;


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
?>