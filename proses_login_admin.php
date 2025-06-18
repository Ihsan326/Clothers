<?php
require_once '../config/koneksi.php';
session_start();

$db = new Database();
$conn = $db->getConnection();

$username = $_POST['usrname_admin'];
$email = $_POST['email_admin'];
$password = $_POST['password_admin'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE usrname_admin = ? AND email_admin = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin && password_verify($password, $admin['password_admin'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['uid_admin'] = $admin['uid_admin'];
    $_SESSION['nama_admin'] = $admin['nama_admin'];
    header("Location: admin.php");
} else {
    header("Location: login_admin.php?error=Username/email/password salah!");
}
