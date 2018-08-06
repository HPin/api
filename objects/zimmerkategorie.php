<?php
class Zimmer {
 
    // database connection and table name
    private $conn;
    private $table_name = "zimmerkategorie";
 
    // object properties
    public $zimmerkategorieID;
    public $name;
 
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
                    WHERE x.zimmerkategorieID = ?
                    LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->zimmerkategorieID);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->zimmerkategorieID = $row['zimmerkategorieID'];
        $this->name = $row['name'];
    }

    

}
?>