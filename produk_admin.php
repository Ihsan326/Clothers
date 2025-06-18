<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login_admin.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT * FROM produk";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Produk - Admin</title>
  <link rel="stylesheet" href="../assets/admin-style.css">
  <style>
    .main-content {
      padding: 30px;
      margin-left: 230px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    thead {
      background-color: #2c3e50;
      color: white;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
      vertical-align: top;
    }

    td img {
      object-fit: cover;
      border-radius: 5px;
    }

    .deskripsi-scroll {
      max-height: 100px;
      overflow-y: auto;
    }

    .aksi-btn {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .aksi-btn a {
      background-color: #3498db;
      color: white;
      padding: 5px 8px;
      border-radius: 4px;
      text-align: center;
      text-decoration: none;
      font-size: 13px;
    }

    .aksi-btn a.delete {
      background-color: #e74c3c;
    }

    .aksi-btn a:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>

<?php include 'dashboard_admin.php'; ?>

<div class="main-content">
  <h1>Daftar Produk</h1>
  <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; background-color: white; border-collapse: collapse;">
    <thead style="background-color: #2c3e50; color: white;">
      <tr>
        <th>ID</th>
        <th>Thumbnail</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Gender</th>
        <th>Kondisi</th>
        <th>Kategori</th>
        <th>Brand</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id_prod']) ?></td>
            <td>
              <img src="../assets/product_img/<?= htmlspecialchars($row['thumbnail_prod']) ?>" width="60" height="60">
            </td>
            <td><?= htmlspecialchars($row['nama_prod']) ?></td>
            <td>IDR <?= number_format($row['harga_prod'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($row['target_kategori']) ?></td>
            <td><?= htmlspecialchars($row['kondisi_prod']) ?></td>
            <td><?= htmlspecialchars($row['id_kategori']) ?></td>
            <td><?= htmlspecialchars($row['id_brand']) ?></td>
            <td>
              <div class="deskripsi-scroll">
                <?= nl2br(htmlspecialchars($row['deskripsi_prod'])) ?>
              </div>
            </td>
            <td>
                <div class="aksi-btn">
                <a href="edit_produk.php?id=<?= $row['id_prod'] ?>">Edit</a>
                <a href="hapus_produk.php?id=<?= $row['id_prod'] ?>" class="delete" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="10" style="text-align: center;">Tidak ada produk</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
