<?php
class Family {
    private $conn;
    private $table = "families";

    public $id;
    public $family_name;
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
        $query = "INSERT INTO " . $this->table . " SET family_name=:family_name";
        $stmt = $this->conn->prepare($query);
        
        $this->family_name = htmlspecialchars(strip_tags($this->family_name));
        
        $stmt->bindParam(":family_name", $this->family_name);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>