<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['kode_valid'])) {
    header("Location: cek_kode.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_SESSION['kode_valid'];
    $nama = $_POST['nama_admin'];
    $username = $_POST['usrname_admin'];
    $email = $_POST['email_admin'];
    $password = password_hash($_POST['password_admin'], PASSWORD_DEFAULT);
    $notlp = $_POST['notlp_admin'];

    $uid = "AID" . substr(uniqid(), -6);

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO admin (uid_admin, nama_admin, usrname_admin, password_admin, email_admin, notlp_admin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $uid, $nama, $username, $password, $email, $notlp);
    $success = $stmt->execute();

    if ($success) {
        // Update status kode jadi terpakai
        $stmt2 = $conn->prepare("UPDATE kode_registrasi SET status = 'terpakai', digunakan_oleh = ?, digunakan_pada = NOW() WHERE kode = ?");
        $stmt2->bind_param("ss", $email, $kode);
        $stmt2->execute();

        unset($_SESSION['kode_valid']);
        echo "Registrasi berhasil. Silakan <a href='admin_login.php'>login</a>.";
    } else {
        echo "Registrasi gagal. Coba lagi.";
    }
}
