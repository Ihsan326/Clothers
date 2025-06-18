<?php
require_once '../config/koneksi.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location='login.php';</script>";
    exit;
}

// Ambil data user dari session
$id_user = $_SESSION['user']['uid'] ?? null;

// Validasi input
if (!isset($_POST['id_produk'], $_POST['id_ukuran'], $_POST['aksi'])) {
    echo "<script>alert('Data tidak lengkap.'); history.back();</script>";
    exit;
}

$id_produk = $_POST['id_produk'];
$id_ukuran = $_POST['id_ukuran'];
$aksi = $_POST['aksi'];

$db = new Database();
$conn = $db->getConnection();

// Cek stok berdasarkan ukuran
$stmt = $conn->prepare("SELECT stok FROM produk_stok WHERE id_prod = ? AND id_ukuran = ?");
$stmt->bind_param("ii", $id_produk, $id_ukuran);
$stmt->execute();
$result = $stmt->get_result();
$data_stok = $result->fetch_assoc();

if (!$data_stok || $data_stok['stok'] < 1) {
    echo "<script>alert('Stok habis atau ukuran tidak valid.'); history.back();</script>";
    exit;
}

// Aksi: Tambah ke keranjang
if ($aksi === "keranjang") {
    // Cek apakah produk sudah ada di keranjang (supaya tidak dobel)
    $cek_cart = $conn->prepare("SELECT * FROM cart WHERE uid = ? AND id_prod = ? AND id_ukuran = ?");
    $cek_cart->bind_param("sii", $id_user, $id_produk, $id_ukuran);
    $cek_cart->execute();
    $cek_result = $cek_cart->get_result();

    if ($cek_result->num_rows > 0) {
        // Update qty jika sudah ada
        $update_qty = $conn->prepare("UPDATE cart SET qty = qty + 1 WHERE uid = ? AND id_prod = ? AND id_ukuran = ?");
        $update_qty->bind_param("sii", $id_user, $id_produk, $id_ukuran);
        $update_qty->execute();
    } else {
        // Tambahkan item baru ke keranjang
        $stmt_cart = $conn->prepare("INSERT INTO cart (uid, id_prod, id_ukuran, qty) VALUES (?, ?, ?, 1)");
        $stmt_cart->bind_param("sii", $id_user, $id_produk, $id_ukuran);
        $stmt_cart->execute();
    }

    echo "<script>alert('Produk ditambahkan ke keranjang.'); window.location='keranjang.php';</script>";
    exit;
}

?>
