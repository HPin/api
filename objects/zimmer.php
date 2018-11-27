<?php
class Zimmer {
 
    // database connection and table name
    private $conn;
    private $table_name = "zimmer";
 
    // object properties
    public $zimmerID;
    public $zimmerkategorieID;
    public $huetteID;
    public $plaetze;
    public $preisErw;
    public $preisJgd;
    public $bezeichnung;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read all
    function read() {
     
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    function readOne(){
     
        // query to read single record
        $query = $query = "SELECT * FROM " . $this->table_name . " x
                    WHERE x.zimmerID = ?
                    LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->zimmerID);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->zimmerID = $row['zimmerID'];
        $this->zimmerkategorieID = $row['zimmerkategorieID'];
        $this->huetteID = $row['huetteID'];
        $this->preisErw = $row['preisErw'];
        $this->preisJgd = $row['preisJgd'];
        $this->plaetze = $row['plaetze'];
        $this->bezeichnung = $row['bezeichnung'];
    }

}
?>