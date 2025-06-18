<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$uid = $user['uid'];

if (!isset($_POST['checkout_ids'])) {
    echo "<script>alert('Tidak ada produk yang dipilih.'); window.location='keranjang.php';</script>";
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$ids = $_POST['checkout_ids'];
$id_list = implode(',', array_fill(0, count($ids), '?'));
$param_types = str_repeat('i', count($ids));

$pengiriman_result = $conn->query("SELECT id_pengiriman, metode FROM pengiriman");

$stmt = $conn->prepare("
    SELECT cart.id_cart, cart.qty,
           produk.nama_prod, produk.thumbnail_prod, produk.harga_prod,
           ukuran.ukuran_label
    FROM cart
    JOIN produk ON cart.id_prod = produk.id_prod
    JOIN ukuran ON cart.id_ukuran = ukuran.id_ukuran
    WHERE cart.id_cart IN ($id_list)
");
$stmt->bind_param($param_types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$total_harga = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_harga += $row['harga_prod'] * $row['qty'];
}
?>

<?php include '../partials/header.php'; ?>
<link rel="stylesheet" href="../assets/style.css">

<div class="container">
    <h2>Checkout</h2>
    <form action="proses_checkout.php" method="POST">
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-card">
                <img src="../assets/product_img/<?= htmlspecialchars($item['thumbnail_prod']) ?>" class="cart-img">
                <div class="cart-info">
                    <h3><?= htmlspecialchars($item['nama_prod']) ?></h3>
                    <p>Ukuran: <?= htmlspecialchars($item['ukuran_label']) ?></p>
                    <p>Jumlah: <?= $item['qty'] ?></p>
                    <p class="cart-price">Rp <?= number_format($item['harga_prod'] * $item['qty'], 0, ',', '.') ?></p>
                </div>
            </div>
            <input type="hidden" name="cart_ids[]" value="<?= $item['id_cart'] ?>">
        <?php endforeach; ?>

        <hr>
        <h3>Total Harga: Rp <?= number_format($total_harga, 0, ',', ',') ?></h3>

        <label for="pengiriman">Metode Pengiriman:</label>
        <select name="pengiriman" id="pengiriman" onchange="toggleAlamat()" required>
            <option value="">-- Pilih Pengiriman --</option>
            <?php while($row = $pengiriman_result->fetch_assoc()): ?>
                <option value="<?= $row['id_pengiriman'] ?>|<?= htmlspecialchars($row['metode']) ?>">
                    <?= htmlspecialchars($row['metode']) ?>
                </option>
            <?php endwhile; ?>
        </select>


        <div id="alamatField" style="display: none; margin-top: 15px;">
            <label for="alamat">Alamat Pengiriman:</label>
            <textarea name="alamat" id="alamat" rows="4" placeholder="Masukkan alamat lengkap..."></textarea>
        </div>

        <input type="hidden" name="metode_pembayaran" value="COD">

        <div class="cart-checkout">
            <button type="submit" class="btn">Konfirmasi Pesanan</button>
        </div>
    </form>
</div>

<script>
function toggleAlamat() {
    const select = document.getElementById('pengiriman');
    const selectedText = select.options[select.selectedIndex].text;
    document.getElementById('alamatField').style.display =
        selectedText.toLowerCase().includes('ambil di toko') ? 'none' : 'block';
}
</script>

<?php include '../partials/footer.php'; ?>
