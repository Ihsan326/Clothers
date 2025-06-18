<?php
require_once '../config/koneksi.php';
session_start();

$db = new Database();
$conn = $db->getConnection();

// Tambah brand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_brand'])) {
    $nama = trim($_POST['nama_brand']);
    if (!empty($nama)) {
        $stmt = $conn->prepare("INSERT INTO brand (nama_brand) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
    }
}

// Hapus brand
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $conn->query("DELETE FROM brand WHERE id_brand = $id");
}
?>

<link rel="stylesheet" href="../assets/admin-style.css">

<?php include 'dashboard_admin.php'; ?>
<div class="main-content">
    <h2>Kelola Brand</h2>
    <form method="POST">
        <input type="text" name="nama_brand" placeholder="Nama brand" required>
        <button type="submit" class="btn">Tambah</button>
    </form>

    <h3>Daftar Brand</h3>
    <ul>
        <?php
        $res = $conn->query("SELECT * FROM brand");
        while ($row = $res->fetch_assoc()):
        ?>
            <li><?= htmlspecialchars($row['nama_brand']) ?> - <a href="?hapus=<?= $row['id_brand'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a></li>
        <?php endwhile; ?>
    </ul>
</div>
