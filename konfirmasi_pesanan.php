<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pesanan'])) {
    $id = $_POST['id_pesanan'];

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE pesanan SET status_pengambilan = 'sudah diambil', status_pesanan = 'selesai' WHERE id_pesanan = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    echo "<script>alert('Pengambilan berhasil dikonfirmasi'); window.location='ambilditoko_admin.php';</script>";
    exit();
}
?>
