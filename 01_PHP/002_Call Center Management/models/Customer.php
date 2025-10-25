<?php
class Customer {
    private $conn;
    private $table = "customers";

    public $id;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 SET name=:name, email=:email, phone=:phone, address=:address";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

public function readSingle() {
    $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    return $stmt;
}

public function update() {
    $query = "UPDATE " . $this->table . " 
              SET name=:name, email=:email, phone=:phone, address=:address
              WHERE id=:id";
    
    $stmt = $this->conn->prepare($query);
    
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":phone", $this->phone);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":id", $this->id);
    
    if($stmt->execute()) {
        return true;
    }
    return false;
}
?>