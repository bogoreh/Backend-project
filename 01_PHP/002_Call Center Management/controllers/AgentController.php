<?php
require_once '../models/Database.php';
require_once '../models/Agent.php';

class AgentController {
    private $db;
    private $agent;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->agent = new Agent($this->db);
    }

    public function index() {
        $stmt = $this->agent->read();
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $agents;
    }

    public function create($data) {
        $this->agent->name = $data['name'];
        $this->agent->email = $data['email'];
        $this->agent->phone = $data['phone'];
        $this->agent->department = $data['department'];
        $this->agent->status = $data['status'];

        if($this->agent->create()) {
            return true;
        }
        return false;
    }

    public function update($data) {
        $this->agent->id = $data['id'];
        $this->agent->name = $data['name'];
        $this->agent->email = $data['email'];
        $this->agent->phone = $data['phone'];
        $this->agent->department = $data['department'];
        $this->agent->status = $data['status'];

        if($this->agent->update()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $this->agent->id = $id;
        if($this->agent->delete()) {
            return true;
        }
        return false;
    }
}
?>