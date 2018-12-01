<?php
class Sperrtag {
 
    // database connection and table name
    private $conn;
    private $table_name = "sperrtag";
 
    // object properties
    public $sperrtagID;
    public $huetteID;
    public $startDatum;
    public $endDatum;
    public $info;

    // additional properties for query
    public $bookingMonth;
    public $bookingYear;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read() {
     
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " 
            WHERE huetteID = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        $stmt->bindParam(1, $this->huetteID);        

        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    function readMonth() {
     
        // select all query
        $query = "SELECT * FROM sperrtag WHERE 
            huetteID = ? AND
            (MONTH(startDatum)=? OR MONTH(endDatum)=?)
            AND 
            (YEAR(startDatum)=? OR YEAR(endDatum)=?)";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->huetteID);
        $stmt->bindParam(2, $this->bookingMonth);
        $stmt->bindParam(3, $this->bookingMonth);
        $stmt->bindParam(4, $this->bookingYear);
        $stmt->bindParam(5, $this->bookingYear);

        // execute query
        $stmt->execute();
     
        return $stmt;
    }



    // create new entry
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    sperrtagID=:sperrtagID, 
                    huetteID=:huetteID,
                    startDatum=:startDatum, 
                    endDatum=:endDatum, 
                    info=:info
                    ";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->sperrtagID=htmlspecialchars(strip_tags($this->sperrtagID));
        $this->huetteID=htmlspecialchars(strip_tags($this->huetteID));
        $this->startDatum=htmlspecialchars(strip_tags($this->startDatum));
        $this->endDatum=htmlspecialchars(strip_tags($this->endDatum));
        $this->info=htmlspecialchars(strip_tags($this->info));
        
        // bind values
        $stmt->bindParam(":sperrtagID", $this->sperrtagID);
        $stmt->bindParam(":huetteID", $this->huetteID);
        $stmt->bindParam(":startDatum", $this->startDatum);
        $stmt->bindParam(":endDatum", $this->endDatum);
        $stmt->bindParam(":info", $this->info);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
    
}
?>