<?php
class Buchung {
 
    // database connection and table name
    private $conn;
    private $table_name = "buchung";
 
    // object properties
    public $buchungID;
    public $huetteID;
    public $zimmerID;
    public $erwachsene;
    public $jugendliche;
    public $kinder;
    public $checkinDatum;
    public $checkoutDatum;
    public $buchungsDatum;
    public $preis;
    public $zahlungsDatum;
    public $zahlungsartID;
    public $fruehstuecksanzahl;
    public $bvorname;
    public $bnachname;
    public $bgeburtsdatum;
    public $badresse;
    public $bplz;
    public $bort;
    public $btelefonnummer;
    public $bmail;
    public $bmitglied;


    // additional properties for query
    public $bookingMonth;
    public $bookingYear;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getUserID() {
        return $this->conn->lastInsertId();
    }

    // read bookings
    function read() {
     
        // select all query
        $query = "SELECT * FROM buchung";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    
    function readMonth() {
     
        // select all query
        $query = "SELECT * FROM buchung b WHERE 
            (MONTH(b.checkinDatum)=? OR MONTH(b.checkoutDatum)=?) AND 
            (YEAR(b.checkinDatum)=? OR YEAR(b.checkoutDatum)=?)";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->bookingMonth);
        $stmt->bindParam(2, $this->bookingMonth);
        $stmt->bindParam(3, $this->bookingYear);
        $stmt->bindParam(4, $this->bookingYear);

        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // read one specific entry
    function readOne(){
     
        // query to read single record
        $query = $query = "SELECT * FROM " . $this->table_name . " b
                    WHERE b.buchungID = ?
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
        $this->buchungID = $row['buchungID'];
        $this->huetteID = $row['huetteID'];
        $this->zimmerID = $row['zimmerID'];
        $this->erwachsene = $row['erwachsene'];
        $this->jugendliche = $row['jugendliche'];
        $this->kinder = $row['kinder'];
        $this->checkinDatum = $row['checkinDatum'];
        $this->checkoutDatum = $row['checkoutDatum'];
        $this->buchungsDatum = $row['buchungsDatum'];
        $this->preis = $row['preis'];
        $this->zahlungsDatum = $row['zahlungsDatum'];
        $this->zahlungsartID = $row['zahlungsartID'];
        $this->fruehstuecksanzahl = $row['fruehstuecksanzahl'];
        $this->bvorname = $row['bvorname'];
        $this->bnachname = $row['bnachname'];
        $this->bgeburtsdatum = $row['bgeburtsdatum'];
        $this->badresse = $row['badresse'];
        $this->bplz = $row['bplz'];
        $this->bort = $row['bort'];
        $this->btelefonnummer = $row['btelefonnummer'];
        $this->bmail = $row['bmail'];
        $this->bmitglied = $row['bmitglied'];
    
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