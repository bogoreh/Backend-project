<?php
class RecipeController {
    private $recipeModel;

    public function __construct($db) {
        $this->recipeModel = new Recipe($db);
    }

    public function index() {
        $stmt = $this->recipeModel->read();
        include 'views/list.php';
    }

    public function add() {
        if($_POST) {
            $this->recipeModel->title = $_POST['title'];
            $this->recipeModel->ingredients = $_POST['ingredients'];
            $this->recipeModel->instructions = $_POST['instructions'];
            $this->recipeModel->cooking_time = $_POST['cooking_time'];
            $this->recipeModel->difficulty = $_POST['difficulty'];

            if($this->recipeModel->create()) {
                header("Location: index.php");
            }
        }
        include 'views/add.php';
    }

    public function edit() {
        $this->recipeModel->id = $_GET['id'];
        
        if($_POST) {
            $this->recipeModel->title = $_POST['title'];
            $this->recipeModel->ingredients = $_POST['ingredients'];
            $this->recipeModel->instructions = $_POST['instructions'];
            $this->recipeModel->cooking_time = $_POST['cooking_time'];
            $this->recipeModel->difficulty = $_POST['difficulty'];

            if($this->recipeModel->update()) {
                header("Location: index.php");
            }
        } else {
            $this->recipeModel->readOne();
        }
        include 'views/edit.php';
    }

    public function delete() {
        $this->recipeModel->id = $_GET['id'];
        if($this->recipeModel->delete()) {
            header("Location: index.php");
        }
    }
}
?>