<?php
class Budget {
    private $conn;
    private $table = "budgets";

    public $id;
    public $user_id;
    public $title;
    public $description;
    public $amount;
    public $department;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 SET user_id=:user_id, title=:title, description=:description, 
                     amount=:amount, department=:department, status='pending'";
        
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->department = htmlspecialchars(strip_tags($this->department));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":department", $this->department);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT b.*, u.username 
                 FROM " . $this->table . " b 
                 LEFT JOIN users u ON b.user_id = u.id 
                 ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT b.*, u.username 
                 FROM " . $this->table . " b 
                 LEFT JOIN users u ON b.user_id = u.id 
                 WHERE b.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>