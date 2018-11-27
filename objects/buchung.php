<?php
class Buchung {
 
    // database connection and table name
    private $conn;
    private $table_name = "buchung";
 
    // object properties
    public $buchungID;
    public $buchenderID;
    public $buchenderVorname;
    public $buchenderNachname;
    public $huetteID;
    public $zimmerID;
    public $erwachsene;
    public $kinder;
    public $checkinDatum;
    public $checkoutDatum;
    public $buchungsDatum;
    public $zahlungsDatum;
    public $zahlungsartID; 

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

    // read products
    function read() {
     
        // select all query
        $query = "SELECT b.*, u.vorname as buchenderVorname, u.nachname as buchenderNachname
         FROM buchung b, user u WHERE b.buchenderID = u.userID ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    
    function readMonth() {
     
        // select all query
        $query = "SELECT b.*, u.vorname as buchenderVorname, u.nachname as buchenderNachname
         FROM buchung b, user u 
         WHERE b.buchenderID = u.userID 
            AND MONTH(b.checkinDatum)=? 
            AND YEAR(b.checkinDatum)=?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->bookingMonth);
        $stmt->bindParam(2, $this->bookingYear);

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
        //$this->buchenderID = $row['buchenderID'];
        $this->huetteID = $row['huetteID'];
        $this->zimmerID = $row['zimmerID'];
        $this->erwachsene = $row['erwachsene'];
        $this->kinder = $row['kinder'];
        $this->checkinDatum = $row['checkinDatum'];
        $this->checkoutDatum = $row['checkoutDatum'];
        $this->buchungsDatum = $row['buchungsDatum'];
        $this->zahlungsDatum = $row['zahlungsDatum'];
        $this->zahlungsartID = $row['zahlungsartID'];
    }

    // create new entry
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    huetteID=:huetteID, zimmerID=:zimmerID, buchenderID=:buchenderID,
                    checkinDatum=:checkinDatum, checkoutDatum=:checkoutDatum,
                    erwachsene=:erwachsene, kinder=:kinder,
                    buchungsDatum=:buchungsDatum, zahlungsDatum=:zahlungsDatum,
                    zahlungsartID=:zahlungsartID
                    ";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->huetteID=htmlspecialchars(strip_tags($this->huetteID));
        $this->zimmerID=htmlspecialchars(strip_tags($this->zimmerID));
        $this->buchenderID=htmlspecialchars(strip_tags($this->buchenderID));
        $this->checkinDatum=htmlspecialchars(strip_tags($this->checkinDatum));
        $this->checkoutDatum=htmlspecialchars(strip_tags($this->checkoutDatum));
        $this->erwachsene=htmlspecialchars(strip_tags($this->erwachsene));
        $this->kinder=htmlspecialchars(strip_tags($this->kinder));
        $this->buchungsDatum=htmlspecialchars(strip_tags($this->buchungsDatum));
        $this->zahlungsDatum=htmlspecialchars(strip_tags($this->zahlungsDatum));
        $this->zahlungsartID=htmlspecialchars(strip_tags($this->zahlungsartID));

     
        // bind values
        $stmt->bindParam(":huetteID", $this->huetteID);
        $stmt->bindParam(":zimmerID", $this->zimmerID);
        $stmt->bindParam(":buchenderID", $this->buchenderID);
        $stmt->bindParam(":checkinDatum", $this->checkinDatum);
        $stmt->bindParam(":checkoutDatum", $this->checkoutDatum);
        $stmt->bindParam(":erwachsene", $this->erwachsene);
        $stmt->bindParam(":kinder", $this->kinder);
        $stmt->bindParam(":buchungsDatum", $this->buchungsDatum);
        $stmt->bindParam(":zahlungsDatum", $this->zahlungsDatum);
        $stmt->bindParam(":zahlungsartID", $this->zahlungsartID);
     
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