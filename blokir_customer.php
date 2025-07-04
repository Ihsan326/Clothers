<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login_admin.php');
    exit();
}

if (isset($_GET['uid'])) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("UPDATE users SET status = 'diblokir' WHERE uid = ?");
    $stmt->bind_param("s", $_GET['uid']);
    $stmt->execute();

    echo "<script>alert('User telah diblokir.'); window.location='customer_admin.php';</script>";
}
?>
