<?php
class Recipe {
    private $conn;
    private $table = "recipes";

    public $id;
    public $title;
    public $ingredients;
    public $instructions;
    public $cooking_time;
    public $difficulty;
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
                 SET title=:title, ingredients=:ingredients, instructions=:instructions, 
                     cooking_time=:cooking_time, difficulty=:difficulty";
        
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->ingredients = htmlspecialchars(strip_tags($this->ingredients));
        $this->instructions = htmlspecialchars(strip_tags($this->instructions));
        $this->cooking_time = htmlspecialchars(strip_tags($this->cooking_time));
        $this->difficulty = htmlspecialchars(strip_tags($this->difficulty));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":ingredients", $this->ingredients);
        $stmt->bindParam(":instructions", $this->instructions);
        $stmt->bindParam(":cooking_time", $this->cooking_time);
        $stmt->bindParam(":difficulty", $this->difficulty);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->title = $row['title'];
            $this->ingredients = $row['ingredients'];
            $this->instructions = $row['instructions'];
            $this->cooking_time = $row['cooking_time'];
            $this->difficulty = $row['difficulty'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                 SET title=:title, ingredients=:ingredients, instructions=:instructions, 
                     cooking_time=:cooking_time, difficulty=:difficulty 
                 WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->ingredients = htmlspecialchars(strip_tags($this->ingredients));
        $this->instructions = htmlspecialchars(strip_tags($this->instructions));
        $this->cooking_time = htmlspecialchars(strip_tags($this->cooking_time));
        $this->difficulty = htmlspecialchars(strip_tags($this->difficulty));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":ingredients", $this->ingredients);
        $stmt->bindParam(":instructions", $this->instructions);
        $stmt->bindParam(":cooking_time", $this->cooking_time);
        $stmt->bindParam(":difficulty", $this->difficulty);
        $stmt->bindParam(":id", $this->id);

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