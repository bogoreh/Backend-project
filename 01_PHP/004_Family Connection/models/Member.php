<?php
class Member {
    private $conn;
    private $table = "members";

    public $id;
    public $family_id;
    public $name;
    public $email;
    public $phone;
    public $relationship;
    public $birth_date;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT m.*, f.family_name 
                  FROM " . $this->table . " m 
                  LEFT JOIN families f ON m.family_id = f.id 
                  ORDER BY m.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByFamily($family_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE family_id = ? ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $family_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET family_id=:family_id, name=:name, email=:email, 
                      phone=:phone, relationship=:relationship, birth_date=:birth_date";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize data
        $this->family_id = htmlspecialchars(strip_tags($this->family_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->relationship = htmlspecialchars(strip_tags($this->relationship));
        $this->birth_date = htmlspecialchars(strip_tags($this->birth_date));
        
        // Bind parameters
        $stmt->bindParam(":family_id", $this->family_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":relationship", $this->relationship);
        $stmt->bindParam(":birth_date", $this->birth_date);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>