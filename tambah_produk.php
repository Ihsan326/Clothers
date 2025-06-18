<?php
require_once '../config/koneksi.php';
session_start();

// Ambil data kategori dan brand untuk dropdown
$db = new Database();
$conn = $db->getConnection();

$kategori = $conn->query("SELECT * FROM kategori");
$brand = $conn->query("SELECT * FROM brand");
?>

<?php include 'dashboard_admin.php'; ?>
<link rel="stylesheet" href="../assets/admin-style.css">

<div class="main-content">
    <title>Tambah Produk - Admin</title>
  <div class="form-produk">
    <form action="proses_tambah_produk.php" method="POST" enctype="multipart/form-data">
    <label>Nama Produk</label>
    <input type="text" name="nama_prod" required>

    <label>Kategori</label>
    <select name="id_kategori" required>
      <option value="">Pilih Kategori</option>
      <?php while ($kat = $kategori->fetch_assoc()) : ?>
        <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
      <?php endwhile; ?>
    </select>

    <label>Kategori 2</label>
    <select name="target_kategori">
      <option value="">-- Pilih Target --</option>
      <option value="MEN">Men</option>
      <option value="WOMEN">Women</option>
      <option value="KIDS">Kids</option>
    </select>

    <label>Brand</label>
    <select name="id_brand" required>
      <option value="">Pilih Brand</option>
      <?php while ($br = $brand->fetch_assoc()) : ?>
        <option value="<?= $br['id_brand'] ?>"><?= $br['nama_brand'] ?></option>
      <?php endwhile; ?>
    </select>

    <label>Kondisi</label>
    <select name="kondisi_prod" required>
      <option value="">Pilih Kondisi</option>
      <option value="BRAND NEW">BRAND NEW</option>
      <option value="PRELOVED">PRELOVED</option>
      <option value="DEFECT">DEFECT</option>
      <option value="DEAD STOCK">DEAD STOCK</option>
    </select>

    <h4>Stok</h4>
    <?php
    $ukuran = $conn->query("SELECT * FROM ukuran ORDER BY ukuran_label ASC");
    while ($u = $ukuran->fetch_assoc()) :
    ?>
    <div style="margin-bottom: 10px;">
        <label>Ukuran <?= htmlspecialchars($u['ukuran_label']) ?></label>
        <input type="number" name="stok_ukuran[<?= $u['id_ukuran'] ?>]" min="0" value="0">
    </div>
    <?php endwhile; ?>

    <label>Harga</label>
    <input type="number" name="harga_prod" required>

    <label>Deskripsi</label>
    <textarea name="deskripsi_prod" rows="5" required></textarea>

    <label>Thumbnail Produk</label>
    <input type="file" name="thumbnail_prod" accept="image/*" required>

    <label>Gambar Tambahan 1</label>
    <input type="file" name="img1_prod" accept="image/*">

    <label>Gambar Tambahan 2</label>
    <input type="file" name="img2_prod" accept="image/*">

    <label>Gambar Tambahan 3</label>
    <input type="file" name="img3_prod" accept="image/*">

    <button type="submit">Simpan Produk</button>
  </form>
</div>