<?php
require_once '../config/koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kode = $_POST['kode'];

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM kode_registrasi WHERE kode = ? AND status = 'aktif'");
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $result = $stmt->get_result();
    $cek = $result->fetch_assoc();

    if ($cek) {
        $_SESSION['kode_valid'] = $kode;
        header("Location: form_registrasi_admin.php");
        exit();
    } else {
        $error = "Kode tidak valid atau sudah digunakan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Validasi Kode Registrasi</title></head>
<body>
    <h2>Masukkan Kode Registrasi Admin</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="kode" placeholder="Kode Registrasi" required>
        <button type="submit">Lanjut</button>
    </form>
</body>
</html>
