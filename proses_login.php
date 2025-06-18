<?php
require_once '../config/koneksi.php';
require_once '../service/UserService.php';

session_start();

// Tangkap input dari form
$usrname  = $_POST['usrname'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi input tidak kosong
if (empty($usrname) || empty($password)) {
    header("Location: login.php?error=empty");
    exit();
}

// Buat koneksi dan service
$db = new Database();
$conn = $db->getConnection();
$userService = new UserService($conn);

// Cek login
$user = $userService->login($usrname, $password);

if ($user) {
    $_SESSION['user'] = $user;
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=invalid");
    exit();
}
