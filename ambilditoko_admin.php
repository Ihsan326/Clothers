<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Ambil pesanan dengan metode Ambil di Toko dan belum selesai
$query = "
    SELECT p.*, u.nama_depan, u.nama_belakang, peng.metode 
    FROM pesanan p
    JOIN users u ON p.uid = u.uid
    JOIN pengiriman peng ON p.id_pengiriman = peng.id_pengiriman
    WHERE peng.metode = 'Ambil di Toko' AND p.status_pesanan != 'selesai'
    ORDER BY p.tanggal_pesan DESC
";
$result = $conn->query($query);
if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<?php include 'dashboard_admin.php'; ?>
<link rel="stylesheet" href="../assets/admin-style.css">

<div class="main-content">
    <h2>Konfirmasi Ambil di Toko</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Nama Customer</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_pesanan']) ?></td>
                        <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($row['tanggal_pesan'])) ?></td>
                        <td>
                            <span class="status-tag <?= $row['status_pengambilan'] === 'sudah diambil' ? 'done' : 'pending' ?>">
                                <?= ucwords($row['status_pengambilan'] ?? 'belum diambil') ?>
                            </span>
                        </td>
                        <td>
                            <a href="admin_detailpesanan.php?id=<?= $row['id_pesanan'] ?>" class="btn">Lihat Detail</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada pesanan Ambil di Toko yang belum dikonfirmasi.</p>
    <?php endif; ?>
</div>
