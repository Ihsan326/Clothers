<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login_admin.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Search dan filter
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$query = "SELECT * FROM users WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $query .= " AND (nama_depan LIKE ? OR nama_belakang LIKE ? OR usrname LIKE ? OR email LIKE ?)";
    $searchParam = "%" . $search . "%";
    $params = array_fill(0, 4, $searchParam);
    $types .= 'ssss';
}

if (!empty($status_filter)) {
    $query .= " AND status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'dashboard_admin.php'; ?>
<link rel="stylesheet" href="../assets/admin-style.css">

<div class="main-content">
    <h1>Data Customer</h1>

    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Cari nama/email/username..." value="<?= htmlspecialchars($search) ?>" style="padding: 7px; width: 250px;">
        <select name="status" style="padding: 7px;">
            <option value="">Semua Status</option>
            <option value="aktif" <?= $status_filter == 'aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="nonaktif" <?= $status_filter == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            <option value="diblokir" <?= $status_filter == 'diblokir' ? 'selected' : '' ?>>Diblokir</option>
        </select>
        <button type="submit" style="padding: 7px 15px;">Filter</button>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>UID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Foto Profil</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user['uid']) ?></td>
                    <td><?= htmlspecialchars($user['nama_depan'] . ' ' . $user['nama_belakang']) ?></td>
                    <td><?= htmlspecialchars($user['usrname']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php if ($user['foto_profil']): ?>
                            <img src="../assets/foto_profil/<?= htmlspecialchars($user['foto_profil']) ?>" width="50" style="border-radius: 50%;">
                        <?php else: ?>
                            Tidak Ada
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['status'] ?? 'nonaktif') ?></td>
                    <td>
                        <a href="hapus_customer.php?uid=<?= $user['uid'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a> |
                        <a href="detail_customer.php?uid=<?= $user['uid'] ?>">Detail</a> |
                        <?php if ($user['status'] == 'nonaktif'): ?>
                            <a href="ubah_status.php?uid=<?= $user['uid'] ?>&status=aktif" onclick="return confirm('Aktifkan akun ini?')">Aktifkan</a>
                        <?php elseif ($user['status'] == 'aktif'): ?>
                            <a href="ubah_status.php?uid=<?= $user['uid'] ?>&status=nonaktif" onclick="return confirm('Nonaktifkan akun ini?')">Nonaktifkan</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
