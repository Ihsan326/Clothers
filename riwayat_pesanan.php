<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$uid = $_SESSION['user']['uid'];
$status_filter = $_GET['status'] ?? 'proses';

// Ambil daftar pesanan user
$query = "
    SELECT * FROM pesanan
    WHERE uid = ?
";

if ($status_filter === 'selesai') {
    $query .= " AND status_pesanan = 'selesai'";
} else {
    $query .= " AND status_pesanan IN ('pending','dibayar','dikirim')";
}

$query .= " ORDER BY tanggal_pesan DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $uid);
$stmt->execute();
$pesanan_result = $stmt->get_result();
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/navbar.php'; ?>
<link rel="stylesheet" href="../assets/style.css">

<div class="profile-layout">
    <?php include '../partials/sidebar_profile.php'; ?>

    <div class="user-profile-container expanded">
        <div class="profile-tabs-wrapper">
            <div class="profile-tabs">
                <a href="?status=proses" class="tab-link <?= $status_filter === 'selesai' ? '' : 'active' ?>">Dalam Proses</a>
                <a href="?status=selesai" class="tab-link <?= $status_filter === 'selesai' ? 'active' : '' ?>">Selesai</a>
            </div>
        </div>

        <div class="cart-wrapper">
            <?php if ($pesanan_result->num_rows > 0): ?>
                <?php while($pesanan = $pesanan_result->fetch_assoc()): ?>
                    <?php
                    // Ambil produk dalam 1 pesanan
                    $stmtDetail = $conn->prepare("
                        SELECT dp.jumlah, pr.nama_prod, pr.thumbnail_prod
                        FROM detail_pesanan dp
                        JOIN produk pr ON dp.id_prod = pr.id_prod
                        WHERE dp.id_pesanan = ?
                        LIMIT 2
                    ");
                    $stmtDetail->bind_param("s", $pesanan['id_pesanan']);
                    $stmtDetail->execute();
                    $produk_result = $stmtDetail->get_result();
                    ?>

                    <div class="cart-card">
                        <div style="display:flex; align-items:center; gap:20px;">
                            <?php while($prod = $produk_result->fetch_assoc()): ?>
                                <img src="../assets/product_img/<?= htmlspecialchars($prod['thumbnail_prod']) ?>" class="cart-img" alt="<?= htmlspecialchars($prod['nama_prod']) ?>" style="width: 80px; height: 80px;">
                            <?php endwhile; ?>
                        </div>

                        <div class="cart-info">
                            <h3>ID Pesanan: <?= $pesanan['id_pesanan'] ?></h3>
                            <p>Tanggal: <?= date('d M Y', strtotime($pesanan['tanggal_pesan'])) ?></p>
                            <p>Metode Pengiriman: <?= htmlspecialchars($pesanan['metode_pengiriman']) ?></p>
                        </div>

                        <div class="cart-actions" style="text-align:right;">
                            <span class="status-tag <?= $pesanan['status_pesanan'] ?>">
                                <?= ucwords($pesanan['status_pesanan']) ?>
                            </span>
                            <br><br>
                            <a href="detail_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>" class="btn">Lihat Detail</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="cart-empty">Belum ada pesanan untuk ditampilkan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
