<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || !isset($_POST['id_pesanan'])) {
    header("Location: ambilditoko_admin.php");
    exit();
}

$id_pesanan = $_POST['id_pesanan'];

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("UPDATE pesanan SET status_pesanan = 'selesai' WHERE id_pesanan = ?");
$stmt->bind_param("s", $id_pesanan);

if ($stmt->execute()) {
    $_SESSION['notif'] = "Pesanan berhasil dikonfirmasi sebagai selesai.";
} else {
    $_SESSION['notif'] = "Gagal mengonfirmasi pesanan.";
}

header("Location: ambilditoko_admin.php");
exit();
