<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$uid = $user['uid'];

$db = new Database();
$conn = $db->getConnection();

$query = $conn->prepare("
    SELECT 
        cart.id_cart, cart.qty, cart.tanggal_ditambahkan,
        produk.nama_prod, produk.thumbnail_prod, produk.harga_prod,
        brand.nama_brand,
        ukuran.ukuran_label
    FROM cart
    JOIN produk ON cart.id_prod = produk.id_prod
    LEFT JOIN brand ON produk.id_brand = brand.id_brand
    JOIN ukuran ON cart.id_ukuran = ukuran.id_ukuran
    WHERE cart.uid = ?
");
$query->bind_param("s", $uid);
$query->execute();
$result = $query->get_result();
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/navbar.php'; ?>
<link rel="stylesheet" href="../assets/style.css">

<div class="profile-layout">
    <?php include '../partials/sidebar_profile.php'; ?>

    <div class="user-profile-container expanded">
        <h2>CART</h2>

        <form action="checkout.php" method="POST">
            <div class="cart-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($item = $result->fetch_assoc()): ?>
                        <div class="cart-item">
                            <input type="checkbox" name="checkout_ids[]" value="<?= $item['id_cart'] ?>" class="cart-check">
                            <img src="../assets/product_img/<?= htmlspecialchars($item['thumbnail_prod']) ?>" class="cart-thumb" alt="<?= htmlspecialchars($item['nama_prod']) ?>">
                            
                            <div class="cart-details">
                                <h4><?= htmlspecialchars($item['nama_prod']) ?></h4>
                                <p><strong>Brand:</strong> <?= htmlspecialchars($item['nama_brand'] ?? '-') ?></p>
                                <p><strong>Ukuran:</strong> <?= htmlspecialchars($item['ukuran_label']) ?></p>
                                <p><strong>Jumlah:</strong> <?= htmlspecialchars($item['qty']) ?></p>
                                <p class="cart-price">Rp <?= number_format($item['harga_prod'], 0, ',', '.') ?></p>
                            </div>

                            <div class="cart-actions">
                                <a href="hapus_cart.php?id=<?= $item['id_cart'] ?>" class="btn btn-logout" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="cart-empty">Keranjang kamu masih kosong.</p>
                <?php endif; ?>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <div class="cart-checkout">
                    <button type="submit" class="btn">Checkout</button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
