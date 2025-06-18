<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login_admin.php');
    exit();
}

if (!isset($_GET['uid'])) {
    echo "UID tidak ditemukan.";
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$stmt->bind_param("s", $_GET['uid']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User tidak ditemukan.";
    exit();
}

include 'dashboard_admin.php';
?>

<link rel="stylesheet" href="../assets/admin-style.css">

<div class="main-content">
  <h2>Detail Customer</h2>
  <p><strong>UID:</strong> <?= $user['uid'] ?></p>
  <p><strong>Nama:</strong> <?= $user['nama_depan'] . ' ' . $user['nama_belakang'] ?></p>
  <p><strong>Username:</strong> <?= $user['usrname'] ?></p>
  <p><strong>Email:</strong> <?= $user['email'] ?></p>
  <p><strong>Status:</strong> <?= $user['status'] ?? 'aktif' ?></p>
  <?php if ($user['foto_profil']): ?>
    <p><strong>Foto Profil:</strong><br>
      <img src="../assets/foto_profil/<?= $user['foto_profil'] ?>" width="100" style="border-radius: 10px;">
    </p>
  <?php endif; ?>
</div>
