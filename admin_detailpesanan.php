<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID pesanan tidak ditemukan'); window.location='ambilditoko_admin.php';</script>";
    exit();
}

$id_pesanan = $_GET['id'];

$db = new Database();
$conn = $db->getConnection();

// Ambil data pesanan
$stmt = $conn->prepare("
    SELECT p.*, u.nama_depan, u.nama_belakang, u.email
    FROM pesanan p
    JOIN users u ON p.uid = u.uid
    WHERE p.id_pesanan = ?
");
$stmt->bind_param("s", $id_pesanan);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();

if (!$pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan'); window.location='ambilditoko_admin.php';</script>";
    exit();
}

// Ambil produk dari pesanan
$stmtProduk = $conn->prepare("
    SELECT dp.*, pr.nama_prod, pr.thumbnail_prod, uk.ukuran_label
    FROM detail_pesanan dp
    JOIN produk pr ON dp.id_prod = pr.id_prod
    LEFT JOIN ukuran uk ON dp.id_ukuran = uk.id_ukuran
    WHERE dp.id_pesanan = ?
");
$stmtProduk->bind_param("s", $id_pesanan);
$stmtProduk->execute();
$produkResult = $stmtProduk->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan - Admin</title>
    <link rel="stylesheet" href="../assets/admin-style.css">
</head>
<body>

<?php include 'dashboard_admin.php'; ?>

<div class="main-content">
    <h2>Detail Pesanan: <?= htmlspecialchars($id_pesanan) ?></h2>

    <p><strong>Nama Pembeli:</strong> <?= htmlspecialchars($pesanan['nama_depan'] . ' ' . $pesanan['nama_belakang']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($pesanan['email']) ?></p>
    <p><strong>Metode Pengiriman:</strong> <?= $pesanan['metode_pengiriman'] ?></p>
    <p><strong>Metode Pembayaran:</strong> <?= $pesanan['metode_pembayaran'] ?></p>
    <p><strong>Status Pengambilan:</strong> 
        <span style="font-weight: bold; color: <?= $pesanan['status_pengambilan'] === 'sudah diambil' ? 'green' : 'orange' ?>">
            <?= ucwords($pesanan['status_pengambilan'] ?? 'belum diambil') ?>
        </span>
    </p>

    <hr>

    <h3>Produk dalam Pesanan:</h3>
    <?php 
    $total = 0;
    while($item = $produkResult->fetch_assoc()):
        $subtotal = $item['jumlah'] * $item['harga_satuan'];
        $total += $subtotal;
    ?>
        <div class="cart-card">
            <img src="../assets/product_img/<?= htmlspecialchars($item['thumbnail_prod']) ?>" class="cart-img">
            <div class="cart-info">
                <h3><?= htmlspecialchars($item['nama_prod']) ?></h3>
                <p>Ukuran: <?= $item['ukuran_label'] ?></p>
                <p>Jumlah: <?= $item['jumlah'] ?></p>
                <p class="cart-price">Rp <?= number_format($subtotal, 0, ',', '.') ?></p>
            </div>
        </div>
    <?php endwhile; ?>

    <hr>
    <h3>Total Pembayaran: Rp <?= number_format($total, 0, ',', '.') ?></h3>

    <?php if ($pesanan['status_pengambilan'] !== 'sudah diambil'): ?>
        <form method="post" action="konfirmasi_pesanan.php" style="margin-top: 20px;">
            <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan) ?>">
            <button type="submit" class="btn btn-verify" onclick="return confirm('Konfirmasi bahwa pesanan sudah diambil?')">
                ✔ Konfirmasi Pengambilan
            </button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
