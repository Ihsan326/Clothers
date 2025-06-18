<?php
session_start();
if (!isset($_SESSION['kode_valid'])) {
    header("Location: cek_kode.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Registrasi Admin</title></head>
<body>
    <h2>Form Registrasi Admin</h2>
    <form method="POST" action="proses_registrasi_admin.php">
        <input type="text" name="nama_admin" placeholder="Nama Lengkap" required>
        <input type="text" name="usrname_admin" placeholder="Username" required>
        <input type="email" name="email_admin" placeholder="Email" required>
        <input type="password" name="password_admin" placeholder="Password" required>
        <input type="text" name="notlp_admin" placeholder="No. Telepon" required>
        <button type="submit">Daftar</button>
    </form>
</body>
</html>
