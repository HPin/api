<?php
class User {
 
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    //public $userID;
    public $vorname;
    public $nachname;
    public $geburtsdatum;
    public $adresse;
    public $plz;
    public $ort;
    public $telefonnummer;
    public $mail;
 
    public function __construct($db){
        $this->conn = $db;
    }

     // create new entry
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    vorname=:vorname, nachname=:nachname, geburtsdatum=:geburtsdatum, adresse=:adresse,
                    plz=:plz, ort=:ort, telefonnummer=:telefonnummer, mail=:mail
                    ";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->vorname=htmlspecialchars(strip_tags($this->vorname));
        $this->nachname=htmlspecialchars(strip_tags($this->nachname));
        $this->geburtsdatum=htmlspecialchars(strip_tags($this->geburtsdatum));
        $this->adresse=htmlspecialchars(strip_tags($this->adresse));
        $this->plz=htmlspecialchars(strip_tags($this->plz));
        $this->ort=htmlspecialchars(strip_tags($this->ort));
        $this->telefonnummer=htmlspecialchars(strip_tags($this->telefonnummer));
        $this->mail=htmlspecialchars(strip_tags($this->mail));

     
        // bind values
        $stmt->bindParam(":vorname", $this->vorname);
        $stmt->bindParam(":nachname", $this->nachname);
        $stmt->bindParam(":geburtsdatum", $this->geburtsdatum);
        $stmt->bindParam(":adresse", $this->adresse);
        $stmt->bindParam(":plz", $this->plz);
        $stmt->bindParam(":ort", $this->ort);
        $stmt->bindParam(":telefonnummer", $this->telefonnummer);
        $stmt->bindParam(":mail", $this->mail);
     

        // execute query
        if($stmt->execute()){
            return $this->conn->lastInsertId();
            //return true;
        }
        //return false;
    }

}
?>