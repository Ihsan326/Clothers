<?php
require_once '../config/koneksi.php';
session_start();

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_kategori'])) {
    $nama = trim($_POST['nama_kategori']);
    if (!empty($nama)) {
        $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
    }
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $conn->query("DELETE FROM kategori WHERE id_kategori = $id");
}
?>

<link rel="stylesheet" href="../assets/admin-style.css">

<?php include 'dashboard_admin.php'; ?>
<div class="main-content">
    <h2>Kelola Kategori</h2>
    <form method="POST">
        <input type="text" name="nama_kategori" placeholder="Nama kategori" required>
        <button type="submit" class="btn">Tambah</button>
    </form>

    <h3>Daftar Kategori</h3>
    <ul>
        <?php
        $res = $conn->query("SELECT * FROM kategori");
        while ($row = $res->fetch_assoc()):
        ?>
            <li><?= htmlspecialchars($row['nama_kategori']) ?> - <a href="?hapus=<?= $row['id_kategori'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a></li>
        <?php endwhile; ?>
    </ul>
</div>
