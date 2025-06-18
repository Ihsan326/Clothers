<?php
require_once '../config/koneksi.php';

function uploadFile($fieldname, $folder = '../assets/product_img/') {
  if (!empty($_FILES[$fieldname]['name'])) {
    $namaFile = uniqid() . '_' . $_FILES[$fieldname]['name'];
    $targetPath = $folder . $namaFile;
    move_uploaded_file($_FILES[$fieldname]['tmp_name'], $targetPath);
    return $namaFile;
  }
  return null;
}

$db = new Database();
$conn = $db->getConnection();

$nama_prod = $_POST['nama_prod'];
$id_kategori = $_POST['id_kategori'];
$id_brand = $_POST['id_brand'];
$kondisi = $_POST['kondisi_prod'];
$harga = $_POST['harga_prod'];
$deskripsi = $_POST['deskripsi_prod'];

$thumbnail = uploadFile('thumbnail_prod');
$img1 = uploadFile('img1_prod');
$img2 = uploadFile('img2_prod');
$img3 = uploadFile('img3_prod');

$stmt = $conn->prepare("INSERT INTO produk (nama_prod, id_kategori, id_brand, kondisi_prod, harga_prod, deskripsi_prod, thumbnail_prod, img1_prod, img2_prod, img3_prod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("siisdsssss", $nama_prod, $id_kategori, $id_brand, $kondisi, $harga, $deskripsi, $thumbnail, $img1, $img2, $img3);

if ($stmt->execute()) {
    echo "<script>alert('Produk berhasil ditambahkan.'); window.location='produk_admin.php';</script>";
} else {
    echo "<script>alert('Gagal menambahkan produk.'); window.location='tambah_produk.php';</script>";
}

$id_produk_baru = $conn->insert_id;

if (!empty($_POST['stok_ukuran'])) {
  foreach ($_POST['stok_ukuran'] as $id_ukuran => $stok) {
    $stok = intval($stok);
    if ($stok > 0) {
      $stmt_stok = $conn->prepare("INSERT INTO produk_stok (id_prod, id_ukuran, stok) VALUES (?, ?, ?)");
      $stmt_stok->bind_param("iii", $id_produk_baru, $id_ukuran, $stok);
      $stmt_stok->execute();
    }
  }
}
