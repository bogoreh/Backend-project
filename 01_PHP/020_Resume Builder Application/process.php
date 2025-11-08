<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    $photoPath = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . $_FILES['photo']['name'];
        $photoPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    // Store data in session
    $_SESSION['resume_data'] = [
        'personal' => [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'summary' => $_POST['summary'],
            'photo' => $photoPath
        ],
        'education' => $_POST['education'] ?? [],
        'experience' => $_POST['experience'] ?? [],
        'skills' => $_POST['skills'] ?? [],
        'template' => $_POST['template']
    ];

    // Redirect to download page
    header('Location: download.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>