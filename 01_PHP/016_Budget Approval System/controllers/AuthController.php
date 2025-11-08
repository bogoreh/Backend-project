<?php
session_start();
require_once '../models/Database.php';
require_once '../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($data) {
        $this->user->username = $data['username'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];

        if($this->user->register()) {
            $_SESSION['success'] = "Registration successful. Please login.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed.";
        }
    }

    public function login($data) {
        $this->user->username = $data['username'];
        $this->user->password = $data['password'];

        if($this->user->login()) {
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['username'] = $this->user->username;
            $_SESSION['role'] = $this->user->role;
            header("Location: ../views/dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Handle form submissions
if($_POST) {
    $auth = new AuthController();
    
    if(isset($_POST['register'])) {
        $auth->register($_POST);
    } elseif(isset($_POST['login'])) {
        $auth->login($_POST);
    }
}

if(isset($_GET['logout'])) {
    $auth = new AuthController();
    $auth->logout();
}
?>