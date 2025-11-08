<?php
session_start();

if (!isset($_SESSION['resume_data'])) {
    header('Location: index.php');
    exit;
}

$data = $_SESSION['resume_data'];
$template = $data['template'];

// Include the selected template
include "templates/$template.php";

// Generate PDF (optional - you can add TCPDF or Dompdf later)
if (isset($_POST['download'])) {
    // Here you can implement PDF generation
    // For now, we'll just show a message
    echo "<script>alert('PDF download feature can be added with libraries like TCPDF or Dompdf');</script>";
}
?>