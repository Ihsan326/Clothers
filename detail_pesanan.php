<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID pesanan tidak ditemukan'); window.location='riwayat_pesanan.php';</script>";
    exit();
}

$id_pesanan = $_GET['id'];
$uid = $_SESSION['user']['uid'];

$db = new Database();
$conn = $db->getConnection();

// Ambil data pesanan utama
$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = ? AND uid = ?");
$stmt->bind_param("ss", $id_pesanan, $uid);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();

if (!$pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan'); window.location='riwayat_pesanan.php';</script>";
    exit();
}

// Ambil daftar produk di pesanan ini
$stmtProduk = $conn->prepare("
    SELECT dp.*, p.nama_prod, p.thumbnail_prod, u.ukuran_label
    FROM detail_pesanan dp
    JOIN produk p ON dp.id_prod = p.id_prod
    LEFT JOIN ukuran u ON dp.id_ukuran = u.id_ukuran
    WHERE dp.id_pesanan = ?
");
$stmtProduk->bind_param("s", $id_pesanan);
$stmtProduk->execute();
$resultProduk = $stmtProduk->get_result();

$total = 0;
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/navbar.php'; ?>
<link rel="stylesheet" href="../assets/style.css">

<div class="profile-layout">
    <?php include '../partials/sidebar_profile.php'; ?>

    <div class="user-profile-container expanded">
        <h2>Detail Pesanan - <?= htmlspecialchars($id_pesanan) ?></h2>

        <!-- STATUS -->
        <p>Status: <span class="status-tag <?= $pesanan['status_pesanan'] ?>"><?= ucwords($pesanan['status_pesanan']) ?></span></p>
        <p>Metode Pembayaran: <?= htmlspecialchars($pesanan['metode_pembayaran']) ?></p>
        <p>Metode Pengiriman: <?= htmlspecialchars($pesanan['metode_pengiriman']) ?></p>
        <?php if ($pesanan['metode_pengiriman'] !== 'Ambil di Toko'): ?>
            <p>Alamat Pengiriman: <?= nl2br(htmlspecialchars($pesanan['alamat_pengiriman'])) ?></p>
        <?php endif; ?>

        <hr>

        <div class="cart-wrapper">
            <?php while($item = $resultProduk->fetch_assoc()): 
                $subtotal = $item['harga_satuan'] * $item['jumlah'];
                $total += $subtotal;
            ?>
                <div class="cart-card">
                    <img src="../assets/product_img/<?= htmlspecialchars($item['thumbnail_prod']) ?>" class="cart-img">
                    <div class="cart-info">
                        <h3><?= htmlspecialchars($item['nama_prod']) ?></h3>
                        <p>Ukuran: <?= htmlspecialchars($item['ukuran_label']) ?></p>
                        <p>Jumlah: <?= $item['jumlah'] ?></p>
                        <p class="cart-price">Rp <?= number_format($subtotal, 0, ',', '.') ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <hr>
        <h3>Ringkasan Pembayaran</h3>
        <p>Harga Produk: Rp <?= number_format($total, 0, ',', '.') ?></p>
        <p>Ongkos Kirim: 
            <?php 
            $ongkir = 0;
            $stmtOngkir = $conn->prepare("SELECT biaya FROM pengiriman WHERE metode = ?");
            $stmtOngkir->bind_param("s", $pesanan['metode_pengiriman']);
            $stmtOngkir->execute();
            $resOngkir = $stmtOngkir->get_result()->fetch_assoc();
            $ongkir = $resOngkir ? $resOngkir['biaya'] : 0;
            echo 'Rp ' . number_format($ongkir, 0, ',', '.');
            $total_bayar = $total + $ongkir;
            ?>
        </p>
        <p><strong>Total Pembayaran: Rp <?= number_format($total_bayar, 0, ',', '.') ?></strong></p>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
