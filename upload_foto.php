<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$db = new Database();
$conn = $db->getConnection();
$uid = $user['uid'];

if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
    $ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
    $fileName = 'user_' . $uid . '_' . time() . '.' . $ext;
    $targetDir = '../uploads/';
    $targetPath = $targetDir . $fileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $targetPath)) {
        $stmt = $conn->prepare("UPDATE users SET foto_profil = ? WHERE uid = ?");
        $stmt->bind_param("ss", $fileName, $uid);
        $stmt->execute();

        $_SESSION['user']['foto_profil'] = $fileName;

        header("Location: user_profile.php"); // GANTI NAMA YANG BENAR
        exit();
    } else {
        echo "Gagal mengunggah file.";
    }
} else {
    echo "Tidak ada file yang diunggah atau terjadi kesalahan.";
}
