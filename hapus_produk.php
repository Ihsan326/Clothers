<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login_admin.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM produk WHERE id_prod = ?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    echo "<script>alert('Produk berhasil dihapus.'); window.location='produk_admin.php';</script>";
} else {
    echo "Gagal menghapus produk.";
}
?>
