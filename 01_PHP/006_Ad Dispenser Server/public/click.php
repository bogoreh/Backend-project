<?php
require_once __DIR__ . '/../includes/AdManager.php';

if (isset($_GET['ad_id']) && isset($_GET['redirect'])) {
    $adId = (int)$_GET['ad_id'];
    $redirectUrl = $_GET['redirect'];
    
    $adManager = new AdManager();
    $adManager->handleAdClick($adId);
    
    header("Location: $redirectUrl");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>