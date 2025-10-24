<?php
require_once 'functions.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if(deleteDefect($id)) {
        header("Location: ../pages/defects.php?delete=success");
        exit();
    } else {
        header("Location: ../pages/defects.php?delete=error");
        exit();
    }
} else {
    header("Location: ../pages/defects.php");
    exit();
}
?>