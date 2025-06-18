<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login_admin.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Ambil data brand
$brandQuery = $conn->query("SELECT * FROM brand");
$brandList = $brandQuery->fetch_all(MYSQLI_ASSOC);

// Ambil data kategori
$kategoriQuery = $conn->query("SELECT * FROM kategori");
$kategoriList = $kategoriQuery->fetch_all(MYSQLI_ASSOC);


if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit();
}

$id = $_GET['id'];

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_prod = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

// Ambil daftar semua ukuran
$ukuranList = $conn->query("SELECT * FROM ukuran")->fetch_all(MYSQLI_ASSOC);

// Ambil stok ukuran untuk produk ini
$stmtStok = $conn->prepare("SELECT * FROM produk_stok WHERE id_prod = ?");
$stmtStok->bind_param("s", $id);
$stmtStok->execute();
$stokResult = $stmtStok->get_result();

$stokUkuran = [];
while ($row = $stokResult->fetch_assoc()) {
    $stokUkuran[$row['id_ukuran']] = $row['stok'];
}

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit();
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_prod'];
    $harga = $_POST['harga_prod'];
    $kondisi = $_POST['kondisi_prod'];
    $kategori = $_POST['id_kategori'];
    $target = $_POST['target_kategori'];
    $brand = $_POST['id_brand'];
    $deskripsi = $_POST['deskripsi_prod'];
    $uploadDir = '../assets/product_img/';
    $thumbnailBaru = $produk['thumbnail_prod'];
    $imgBaru = [$produk['img1_prod'], $produk['img2_prod'], $produk['img3_prod']];

    // Handle thumbnail jika diubah
    if (!empty($_FILES['thumbnail_prod']['name'])) {
        $thumbnailBaru = uniqid('thumb_') . '_' . basename($_FILES['thumbnail_prod']['name']);
        move_uploaded_file($_FILES['thumbnail_prod']['tmp_name'], $uploadDir . $thumbnailBaru);
    }

    // Handle gambar tambahan
    for ($i = 1; $i <= 3; $i++) {
        if (!empty($_FILES["img{$i}_prod"]['name'])) {
            $newFile = uniqid("img{$i}_") . '_' . basename($_FILES["img{$i}_prod"]['name']);
            move_uploaded_file($_FILES["img{$i}_prod"]['tmp_name'], $uploadDir . $newFile);
            $imgBaru[$i - 1] = $newFile;
        }
    }

    $stmt = $conn->prepare("UPDATE produk SET nama_prod=?, harga_prod=?, kondisi_prod=?, id_kategori=?, target_kategori=?, id_brand=?, deskripsi_prod=?, thumbnail_prod=?, img1_prod=?, img2_prod=?, img3_prod=? WHERE id_prod=?");
    $stmt->bind_param("sisissssssss", $nama, $harga, $kondisi, $kategori, $target, $brand, $deskripsi, $thumbnailBaru, $imgBaru[0], $imgBaru[1], $imgBaru[2], $id);
    $stmt->execute();

    echo "<script>alert('Produk berhasil diperbarui!'); window.location='produk_admin.php';</script>";
}

if (isset($_POST['stok_ukuran']) && is_array($_POST['stok_ukuran'])) {
    foreach ($_POST['stok_ukuran'] as $id_ukuran => $stok) {
        $id_ukuran = intval($id_ukuran);
        $stok = intval($stok);

        // Cek apakah sudah ada data
        $cekStmt = $conn->prepare("SELECT * FROM produk_stok WHERE id_prod = ? AND id_ukuran = ?");
        $cekStmt->bind_param("si", $id, $id_ukuran);
        $cekStmt->execute();
        $cekResult = $cekStmt->get_result();

        if ($cekResult->num_rows > 0) {
            // Update stok
            $updStmt = $conn->prepare("UPDATE produk_stok SET stok = ? WHERE id_prod = ? AND id_ukuran = ?");
            $updStmt->bind_param("isi", $stok, $id, $id_ukuran);
            $updStmt->execute();
        } else {
            // Insert stok baru
            $insStmt = $conn->prepare("INSERT INTO produk_stok (id_prod, id_ukuran, stok) VALUES (?, ?, ?)");
            $insStmt->bind_param("sii", $id, $id_ukuran, $stok);
            $insStmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link rel="stylesheet" href="../assets/admin-style.css">
</head>
<body>

<?php include 'dashboard_admin.php'; ?>

<div class="main-content">
  <div class="form-produk">
    <h2>Edit Produk</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id_prod" value="<?= $produk['id_prod'] ?>">

      <label for="nama_prod">Nama Produk</label>
      <input type="text" name="nama_prod" id="nama_prod" value="<?= htmlspecialchars($produk['nama_prod']) ?>" required>

      <label for="id_brand">Brand</label>
      <select name="id_brand" id="id_brand" required>
        <option value="">Pilih Brand</option>
        <?php foreach($brandList as $brand): ?>
          <option value="<?= $brand['id_brand'] ?>" <?= $produk['id_brand'] == $brand['id_brand'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($brand['nama_brand']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="id_kategori">Kategori</label>
        <select name="id_kategori" id="id_kategori" required>
        <option value="">Pilih Kategori</option>
        <?php foreach($kategoriList as $kat): ?>
            <option value="<?= $kat['id_kategori'] ?>" <?= $produk['id_kategori'] == $kat['id_kategori'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($kat['nama_kategori']) ?>
            </option>
        <?php endforeach; ?>
        </select>

      <label>Kategori 2</label>
      <select name="target_kategori">
        <option value="">Pilih gender</option>
        <option value="MEN"><?= $produk['target_kategori'] == 'MEN' ? 'selected' : '' ?>Men</option>
        <option value="WOMEN"><?= $produk['target_kategori'] == 'WOMEN' ? 'selected' : '' ?>Women</option>
        <option value="KIDS"><?= $produk['target_kategori'] == 'KIDS' ? 'selected' : '' ?>Kids</option>
        <option value="UNISEX"><?= $produk['target_kategori'] == 'UNISEX' ? 'selected' : '' ?>Unisex</option>
      </select>


      <label for="kondisi_prod">Kondisi</label>
      <select name="kondisi_prod" id="kondisi_prod" required>
        <option value="">Pilih Kondisi</option>
        <option value="BRAND NEW" <?= $produk['kondisi_prod'] == 'BRAND NEW' ? 'selected' : '' ?>>BRAND NEW</option>
        <option value="PRELOVED" <?= $produk['kondisi_prod'] == 'PRELOVED' ? 'selected' : '' ?>>PRELOVED</option>
        <option value="DEFECT" <?= $produk['kondisi_prod'] == 'DEFECT' ? 'selected' : '' ?>>DEFLECT</option>
        <option value="DEAD STOCK" <?= $produk['kondisi_prod'] == 'DEAD STOCK' ? 'selected' : '' ?>>DEAD STOCK</option>
      </select>

      <label for="harga_prod">Harga</label>
      <input type="number" name="harga_prod" id="harga_prod" value="<?= $produk['harga_prod'] ?>" required>

      <label for="deskripsi_prod">Deskripsi</label>
      <textarea name="deskripsi_prod" id="deskripsi_prod" rows="5"><?= htmlspecialchars($produk['deskripsi_prod']) ?></textarea>

      <label>Thumbnail Saat Ini:</label>
      <img src="../assets/product_img/<?= $produk['thumbnail_prod'] ?>" alt="Thumbnail" width="100px">
      <label for="thumbnail_prod">Ganti Thumbnail (jika perlu)</label>
      <input type="file" name="thumbnail_prod" id="thumbnail_prod">

      <!-- Gambar Tambahan -->
      <?php for ($i = 1; $i <= 3; $i++): ?>
        <label>Gambar Tambahan <?= $i ?> Saat Ini:</label>
        <img src="../assets/product_img/<?= $produk["img{$i}_prod"] ?>" alt="Gambar <?= $i ?>" width="100px">
        <label for="img<?= $i ?>_prod">Ganti Gambar Tambahan <?= $i ?></label>
        <input type="file" name="img<?= $i ?>_prod" id="img<?= $i ?>_prod">
      <?php endfor; ?>

        <h3>Ukuran & Stok</h3>
        <?php foreach ($ukuranList as $uk): ?>
        <label><?= htmlspecialchars($uk['ukuran_label']) ?></label>
        <input type="number" name="stok_ukuran[<?= $uk['id_ukuran'] ?>]" min="0" 
                value="<?= isset($stokUkuran[$uk['id_ukuran']]) ? $stokUkuran[$uk['id_ukuran']] : 0 ?>">
        <?php endforeach; ?>


      <button type="submit">Simpan Perubahan</button>
    </form>
  </div>
</div>

</body>
</html>
