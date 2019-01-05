<?php
class Mitreisender {
 
    // database connection and table name
    private $conn;
    private $table_name = "mitreisender";
 
    // object properties
    public $mitreisenderID;
    public $buchungID;
    public $vorname;
    public $nachname;
    public $geburtsdatum;
    public $mitglied;
 
    public function __construct($db){
        $this->conn = $db;
    }

     // create new entry
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    buchungID=:buchungID, vorname=:vorname, nachname=:nachname,
                    geburtsdatum=:geburtsdatum, mitglied=:mitglied
                    ";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->buchungID=htmlspecialchars(strip_tags($this->buchungID));
        $this->vorname=htmlspecialchars(strip_tags($this->vorname));
        $this->nachname=htmlspecialchars(strip_tags($this->nachname));
        $this->geburtsdatum=htmlspecialchars(strip_tags($this->geburtsdatum));
        $this->mitglied=htmlspecialchars(strip_tags($this->mitglied));

     
        // bind values
        $stmt->bindParam(":buchungID", $this->buchungID);
        $stmt->bindParam(":vorname", $this->vorname);
        $stmt->bindParam(":nachname", $this->nachname);
        $stmt->bindParam(":geburtsdatum", $this->geburtsdatum);
        $stmt->bindParam(":mitglied", $this->mitglied);

     

        // execute query
        if($stmt->execute()){
            // return $this->conn->lastInsertId();
            return true;
        }
        return false;
    }


    // read one specific entry
    function readForBooking(){
    
        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " m
                    WHERE m.buchungID = ?";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // bind id of product to be updated
        $stmt->bindParam(1, $this->buchungID);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}
?>