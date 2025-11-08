<?php
class Separation {
    private $conn;
    private $table_name = "separations";

    public $id;
    public $employee_name;
    public $employee_id;
    public $department;
    public $separation_date;
    public $reason;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET employee_name=:employee_name, employee_id=:employee_id, 
                    department=:department, separation_date=:separation_date, 
                    reason=:reason, status=:status";

        $stmt = $this->conn->prepare($query);

        $this->employee_name = htmlspecialchars(strip_tags($this->employee_name));
        $this->employee_id = htmlspecialchars(strip_tags($this->employee_id));
        $this->department = htmlspecialchars(strip_tags($this->department));
        $this->reason = htmlspecialchars(strip_tags($this->reason));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":employee_name", $this->employee_name);
        $stmt->bindParam(":employee_id", $this->employee_id);
        $stmt->bindParam(":department", $this->department);
        $stmt->bindParam(":separation_date", $this->separation_date);
        $stmt->bindParam(":reason", $this->reason);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>