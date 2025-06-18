<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$uid = $user['uid'];

if (!isset($_POST['cart_ids']) || !is_array($_POST['cart_ids'])) {
    echo "<script>alert('Tidak ada produk yang diproses.'); window.location='keranjang.php';</script>";
    exit();
}

$cart_ids = $_POST['cart_ids'];
$pengiriman_raw = $_POST['pengiriman'] ?? '';
list($pengiriman_id, $pengiriman_metode) = explode('|', $pengiriman_raw);
$alamat = $_POST['alamat'] ?? null;
$metode = $_POST['metode_pembayaran'] ?? 'COD';

$db = new Database();
$conn = $db->getConnection();

// 1. Buat ID Pesanan unik
$id_pesanan = 'ORD' . uniqid();

// 2. Insert ke tabel pesanan
// 2. Insert ke tabel pesanan lengkap
$stmtPesanan = $conn->prepare("
    INSERT INTO pesanan 
    (id_pesanan, uid, tanggal_pesan, status_pesanan, metode_pengiriman, alamat_pengiriman, metode_pembayaran, id_pengiriman)
    VALUES (?, ?, NOW(), 'pending', ?, ?, ?, ?)
");
$stmtPesanan->bind_param(
    "sssssi",
    $id_pesanan,
    $uid,
    $pengiriman_metode,
    $alamat,
    $metode,
    $pengiriman_id
);

// asumsi $pengiriman menyimpan ID pengiriman, bukan nama metode
$stmtPesanan->execute();

// 3. Ambil detail cart untuk disimpan ke detail_pesanan
$id_list = implode(',', array_fill(0, count($cart_ids), '?'));
$types = str_repeat('i', count($cart_ids));

$stmtCart = $conn->prepare("
    SELECT cart.id_prod, cart.id_ukuran, cart.qty, produk.harga_prod
    FROM cart
    JOIN produk ON cart.id_prod = produk.id_prod
    WHERE cart.id_cart IN ($id_list)
");
$stmtCart->bind_param($types, ...$cart_ids);
$stmtCart->execute();
$result = $stmtCart->get_result();

// 4. Simpan ke detail_pesanan
$stmtDetail = $conn->prepare("
    INSERT INTO detail_pesanan (id_pesanan, id_prod, id_ukuran, jumlah, harga_satuan)
    VALUES (?, ?, ?, ?, ?)
");

while ($item = $result->fetch_assoc()) {
    $stmtDetail->bind_param(
        "siiid",
        $id_pesanan,
        $item['id_prod'],
        $item['id_ukuran'],
        $item['qty'],
        $item['harga_prod']
    );
    $stmtDetail->execute();
}

// 5. Hapus dari keranjang
$stmtHapus = $conn->prepare("DELETE FROM cart WHERE id_cart IN ($id_list)");
$stmtHapus->bind_param($types, ...$cart_ids);
$stmtHapus->execute();

// 6. (Opsional) Simpan pengiriman kalau bukan "Ambil di Toko"
// Bisa kamu sesuaikan nanti kalau pengiriman disimpan di tabel lain

// 7. Redirect ke halaman riwayat
echo "<script>alert('Pesanan berhasil dibuat!'); window.location='riwayat_pesanan.php';</script>";
exit();
