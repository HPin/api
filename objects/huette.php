<?php
class Huette {
 
    // database connection and table name
    private $conn;
    private $table_name = "huette";
 
    // object properties
    public $huetteID;
    public $name;
    public $adresse;
    public $plz;
    public $ort;
    public $plaetze;
    public $telefonnummer;
    public $mail;
    public $preis;
    public $imageurl;
    public $fruehstueckspreis;

    // helper properties
    public $categoryID;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read() {
     
        // select all query
        $query = "SELECT * FROM ".$this->table_name." ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // used when filling up the update product form
    function readOne(){
     
        // query to read single record
        $query = $query = "SELECT * FROM " . $this->table_name . " h
                    WHERE h.huetteID = ?
                    LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->huetteID);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->huetteID = $row['huetteID'];
        $this->name = $row['name'];
        $this->adresse = $row['adresse'];
        $this->plz = $row['plz'];
        $this->ort = $row['ort'];
        $this->plaetze = $row['plaetze'];
        $this->telefonnummer = $row['telefonnummer'];
        $this->mail = $row['mail'];
        $this->preis = $row['preis'];
        $this->imageurl = $row['imageurl'];
        $this->fruehstueckspreis = $row['fruehstueckspreis'];
    }


     function readZimmer() {
        $query = "SELECT z.zimmerID FROM zimmer z
                    WHERE z.huetteID = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->huetteID);

        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    function readZimmerkategorien() {
        $query = "SELECT DISTINCT kat.* FROM zimmerkategorie kat, zimmer z
                    WHERE z.huetteID = ? 
                    AND z.zimmerkategorieID = kat.zimmerkategorieID";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->huetteID);

        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    function readAllOfCategory() {
        $query = "SELECT * FROM zimmer z
                    WHERE z.huetteID = ? 
                    AND z.zimmerkategorieID = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->huetteID);
        $stmt->bindParam(2, $this->categoryID);

        // execute query
        $stmt->execute();
     
        return $stmt;
    }

}
?>