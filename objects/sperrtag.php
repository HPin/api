<?php
class Sperrtag {
 
    // database connection and table name
    private $conn;
    private $table_name = "sperrtag";
 
    // object properties
    public $sperrtagID;
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
        $query = "SELECT * FROM " . $this->table_name . " ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    function readMonth() {
     
        // select all query
        $query = "SELECT * FROM sperrtag 
         WHERE MONTH(startDatum)=? 
         AND YEAR(startDatum)=?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->bookingMonth);
        $stmt->bindParam(2, $this->bookingYear);

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
                    sperrtagID=:sperrtagID, startDatum=:startDatum, 
                    endDatum=:endDatum, info=:info
                    ";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->sperrtagID=htmlspecialchars(strip_tags($this->sperrtagID));
        $this->startDatum=htmlspecialchars(strip_tags($this->startDatum));
        $this->endDatum=htmlspecialchars(strip_tags($this->endDatum));
        $this->info=htmlspecialchars(strip_tags($this->info));
        
        // bind values
        $stmt->bindParam(":sperrtagID", $this->sperrtagID);
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