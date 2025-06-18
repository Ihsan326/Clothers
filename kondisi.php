<?php
require_once '../config/koneksi.php';
session_start();

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_kategori'])) {
    $nama = trim($_POST['nama_kategori']);
    if (!empty($nama)) {
        $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
    }
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $conn->query("DELETE FROM kategori WHERE id_kategori = $id");
}
?>

<link rel="stylesheet" href="../assets/admin-style.css">

<?php include 'dashboard_admin.php'; ?>
<div class="main-content">
    <h2>On Progress...</h2>
    <h3>Just wait for a few time please :)</h3>
</div>