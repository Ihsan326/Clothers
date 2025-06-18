<?php
require_once '../config/koneksi.php';
session_start();

$db = new Database();
$conn = $db->getConnection();

// Ambil semua produk
$query = "SELECT p.*, k.nama_kategori, b.nama_brand 
          FROM produk p
          LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
          LEFT JOIN brand b ON p.id_brand = b.id_brand";
$result = $conn->query($query);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Clothers.com</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="landing-container">
        <div class="product-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <a href="detail_produk.php?id=<?= urlencode($row['id_prod']) ?>" class="product-card-link">
                    <div class="product-card">
                        <img src="../assets/product_img/<?= htmlspecialchars($row['thumbnail_prod']) ?>" alt="<?= htmlspecialchars($row['nama_prod']) ?>">
                        <h3><?= htmlspecialchars($row['nama_prod']) ?></h3>
                        <p class="harga"><?= "IDR " . number_format($row['harga_prod'], 0, ',', ',') ?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>

        <div class="banner-section">
            <img src="../assets/baseimages/banner1.jpg" alt="Promo Banner" class="banner-img">
        </div>
    </main>
</body>
</html>

<?php include '../partials/footer.php'; ?>