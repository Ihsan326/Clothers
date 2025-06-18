<?php
require_once '../config/koneksi.php';
session_start();

// Ambil data produk dari database berdasarkan ID
if (!isset($_GET['id'])) {
    echo "Produk tidak ditemukan.";
    exit();
}

$id_produk = $_GET['id'];
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("
    SELECT produk.*, kategori.nama_kategori, brand.nama_brand 
    FROM produk 
    LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori 
    LEFT JOIN brand ON produk.id_brand = brand.id_brand 
    WHERE produk.id_prod = ?
");
$stmt->bind_param("s", $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit();
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/navbar.php'; ?>
<link rel="stylesheet" href="../assets/style.css">

<div class="product-detail-card">
  <title><?= htmlspecialchars($produk['nama_prod']) ?></title>
  <div class="product-content" style="display: flex; flex-wrap: wrap; gap: 40px; justify-content: center; align-items: flex-start;">

    <!-- KIRI: Gambar dan Form Beli -->
    <div class="product-left" style="max-width: 400px; flex: 1;">
      <img id="mainImage" src="../assets/product_img/<?= htmlspecialchars($produk['thumbnail_prod']) ?>" alt="Thumbnail" class="main-img" style="width: 500px; height: 400px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
      
      <div class="thumbs" style="margin-top: 10px; display: flex; gap: 10px;">
        <?php foreach (['thumbnail_prod', 'img1_prod', 'img2_prod', 'img3_prod'] as $img): ?>
          <?php if (!empty($produk[$img])): ?>
            <img src="../assets/product_img/<?= htmlspecialchars($produk[$img]) ?>" class="thumb" onclick="changeImage(this)" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; cursor: pointer;">
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <!-- Form belanja -->
      <?php
      // Ambil ukuran dan stok per ukuran dari produk_stok
      $ukuran_stmt = $conn->prepare("
          SELECT u.id_ukuran, u.ukuran_label, ps.stok 
          FROM produk_stok ps 
          JOIN ukuran u ON ps.id_ukuran = u.id_ukuran 
          WHERE ps.id_prod = ? AND ps.stok > 0
      ");
      $ukuran_stmt->bind_param("s", $id_produk);
      $ukuran_stmt->execute();
      $ukuran_result = $ukuran_stmt->get_result();
      ?>

      <!-- Form belanja -->
      <?php if ($ukuran_result->num_rows > 0): ?>
        <form action="proses_belanja.php" method="POST" style="margin-top: 20px;">
          <input type="hidden" name="id_produk" value="<?= $produk['id_prod'] ?>">

          <label for="ukuran"><strong>Pilih Ukuran:</strong></label>
          <select name="id_ukuran" required style="width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px;">
            <option value="" disabled selected>Choose Size</option>
            <?php
              $stmt_stok = $conn->prepare("SELECT ps.*, u.ukuran_label FROM produk_stok ps 
                                          JOIN ukuran u ON ps.id_ukuran = u.id_ukuran 
                                          WHERE ps.id_prod = ? AND ps.stok > 0");
              $stmt_stok->bind_param("s", $id_produk);
              $stmt_stok->execute();
              $result_stok = $stmt_stok->get_result();
              while ($uk = $result_stok->fetch_assoc()) :
            ?>
              <option value="<?= $uk['id_ukuran'] ?>">
                <?= htmlspecialchars($uk['ukuran_label']) ?> (<?= $uk['stok'] ?> stok)
              </option>
            <?php endwhile; ?>
          </select>

          <div style="display: flex; flex-direction: column; gap: 10px;">
            <button type="submit" name="aksi" value="beli" style="padding: 12px; background-color: #6c63ff; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">BUY NOW</button>
            <button type="submit" name="aksi" value="keranjang" style="padding: 12px; background-color: #8a7bff; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">ADD TO CART</button>
          </div>
        </form>
      <?php else: ?>
        <p style="color: red; margin-top: 20px;">Semua ukuran produk ini sedang habis.</p>
      <?php endif; ?>
    </div>

    <!-- KANAN: Info Produk -->
    <div class="product-right" style="flex: 2; max-width: 750px; margin-left: 30px">
      <h1 class="product-name"><?= htmlspecialchars($produk['nama_prod']) ?></h1>
      <p class="product-price" style="font-size: 25px; color: #2b9348; font-weight: bold;">
        IDR <?= number_format($produk['harga_prod'], 0, ',', '.') ?>
      </p>

      <div class="info-list p" style="margin: 15px 0;">
        <p><?= htmlspecialchars($produk['kondisi_prod']) ?></p>
        <p><?= htmlspecialchars($produk['nama_kategori'] ?? '-') ?></p>
        <p><?= htmlspecialchars($produk['nama_brand'] ?? '-') ?></p>
      </div>

      <div class="product-description" style="margin-top: 20px;">
        <p><?= nl2br(htmlspecialchars($produk['deskripsi_prod'])) ?></p>
      </div>
    </div>
  </div>
</div>

<script>
function changeImage(el) {
    document.getElementById("mainImage").src = el.src;
}
</script>

<?php include '../partials/footer.php'; ?>
