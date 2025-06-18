<!-- filter_page.php -->
 <?php
require_once '../config/koneksi.php';
session_start();
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
    <div class="filter-layout">
        <?php include '../partials/filter_sidebar.php'; ?>

        <div class="filter-content">
            <!-- Di sini produk yang sudah difilter ditampilkan -->
            <?php
            $db = new Database();
            $conn = $db->getConnection();

            // Ambil nilai filter dari URL
            $kategori = $_GET['kategori'] ?? '';
            $brand = $_GET['brand'] ?? '';
            $keyword = $_GET['q'] ?? ''; // dari search bar
            $kondisi = $_GET['kondisi'] ?? '';
            $harga_min = $_GET['harga_min'] ?? '';
            $harga_max = $_GET['harga_max'] ?? '';


            // Query dasar
            $query = "
                SELECT produk.*, brand.nama_brand, kategori.nama_kategori 
                FROM produk
                LEFT JOIN brand ON produk.id_brand = brand.id_brand
                LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori
                WHERE 1
            ";

            // Parameter binding
            $params = [];
            $types = "";

            // Tambahkan kondisi filter jika dipilih
            if (!empty($kategori)) {
                $query .= " AND produk.id_kategori = ?";
                $types .= "i";
                $params[] = $kategori;
            }

            if (!empty($brand)) {
                $query .= " AND produk.id_brand = ?";
                $types .= "i";
                $params[] = $brand;
            }

            if (!empty($keyword)) {
                $query .= " AND produk.nama_prod LIKE ?";
                $types .= "s";
                $params[] = "%" . $keyword . "%";
            }

            if (!empty($kondisi)) {
                $query .= " AND produk.kondisi_prod = ?";
                $types .= "s";
                $params[] = $kondisi;
            }

            if (is_numeric($harga_min)) {
                $query .= " AND produk.harga_prod >= ?";
                $types .= "i";
                $params[] = $harga_min;
            }

            if (is_numeric($harga_max)) {
                $query .= " AND produk.harga_prod <= ?";
                $types .= "i";
                $params[] = $harga_max;
            }
            
            if (!empty($_GET['target_kategori'])) {
                $query .= " AND produk.target_kategori = ?";
                $types .= "s";
                $params[] = $_GET['target_kategori'];
            }

            // Eksekusi query
            $stmt = $conn->prepare($query);
            if ($types && $params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            // Tampilkan hasil
            if ($result->num_rows > 0): ?>
                <div class="product-grid">
                <?php while($prod = $result->fetch_assoc()): ?>
                    <a href="detail_produk.php?id=<?= urlencode($prod['id_prod']) ?>" class="product-card-link">
                        <div class="product-card">
                            <img src="../assets/product_img/<?= htmlspecialchars($prod['thumbnail_prod']) ?>" alt="<?= htmlspecialchars($prod['nama_prod']) ?>">
                            <h3><?= htmlspecialchars($prod['nama_prod']) ?></h3>
                            <div class="harga">IDR <?= number_format($prod['harga_prod'], 0, ',', ',') ?></div>
                        </div>
                    </a>
                <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Tidak ada produk yang ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php include '../partials/footer.php'; ?>
