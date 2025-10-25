<?php
class CallRecord {
    private $conn;
    private $table = "calls";

    public $id;
    public $customer_id;
    public $agent_id;
    public $call_type;
    public $duration;
    public $status;
    public $notes;
    public $call_time;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT c.*, cust.name as customer_name, a.name as agent_name 
                 FROM " . $this->table . " c
                 LEFT JOIN customers cust ON c.customer_id = cust.id
                 LEFT JOIN agents a ON c.agent_id = a.id
                 ORDER BY c.call_time DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 SET customer_id=:customer_id, agent_id=:agent_id, call_type=:call_type, 
                     duration=:duration, status=:status, notes=:notes, call_time=:call_time";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":agent_id", $this->agent_id);
        $stmt->bindParam(":call_type", $this->call_type);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":notes", $this->notes);
        $stmt->bindParam(":call_time", $this->call_time);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

public function readSingle() {
    $query = "SELECT c.*, cust.name as customer_name, a.name as agent_name 
              FROM " . $this->table . " c
              LEFT JOIN customers cust ON c.customer_id = cust.id
              LEFT JOIN agents a ON c.agent_id = a.id
              WHERE c.id = ? LIMIT 0,1";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    return $stmt;
}

public function update() {
    $query = "UPDATE " . $this->table . " 
              SET customer_id=:customer_id, agent_id=:agent_id, call_type=:call_type, 
                  duration=:duration, status=:status, notes=:notes, call_time=:call_time
              WHERE id=:id";
    
    $stmt = $this->conn->prepare($query);
    
    $stmt->bindParam(":customer_id", $this->customer_id);
    $stmt->bindParam(":agent_id", $this->agent_id);
    $stmt->bindParam(":call_type", $this->call_type);
    $stmt->bindParam(":duration", $this->duration);
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":notes", $this->notes);
    $stmt->bindParam(":call_time", $this->call_time);
    $stmt->bindParam(":id", $this->id);
    
    if($stmt->execute()) {
        return true;
    }
    return false;
}
?>