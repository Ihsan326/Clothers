<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login_admin.php');
    exit();
}

if (!isset($_GET['uid']) || !isset($_GET['status'])) {
    echo "Permintaan tidak valid.";
    exit();
}

$uid = $_GET['uid'];
$status = $_GET['status'];

if (!in_array($status, ['aktif', 'nonaktif'])) {
    echo "Status tidak valid.";
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("UPDATE users SET status = ? WHERE uid = ?");
$stmt->bind_param("ss", $status, $uid);

if ($stmt->execute()) {
    echo "<script>alert('Status akun berhasil diubah.'); window.location='customer_admin.php';</script>";
} else {
    echo "Gagal mengubah status.";
}
?>