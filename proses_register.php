<?php
require_once '../config/koneksi.php';
require_once '../service/UserService.php';

$db = new Database();
$conn = $db->getConnection();
$userService = new UserService($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_depan     = trim($_POST['nama_depan']);
    $nama_belakang  = trim($_POST['nama_belakang']);
    $usrname        = trim($_POST['usrname']);
    $email          = trim($_POST['email']);
    $password_plain = $_POST['password'];

    // Validasi dasar
    if (empty($nama_depan) || empty($usrname) || empty($email) || empty($password_plain)) {
        echo "Semua field wajib diisi!";
        exit;
    }

    // Enkripsi password
    $password = password_hash($password_plain, PASSWORD_DEFAULT);

    // Generate UID acak (dengan prefix UID)
    $uid = 'UID' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Eksekusi pendaftaran
    $result = $userService->register($uid, $nama_depan, $nama_belakang, $usrname, $email, $password);

    if ($result) {
        header("Location: login.php?success=1");
        exit();
    } else {
        echo "❌ Registrasi gagal. Mungkin username/email sudah digunakan.";
    }
}
?>
