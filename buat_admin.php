<?php
require_once '../config/koneksi.php';

// Data admin baru
$nama = "Admin Utama";
$username = "admin123";
$email = "admin@example.com";
$password = "admin123"; // nanti di-hash
$notlp = "081234567890";

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Buat UID unik dengan prefix AID
$uid = "AID" . uniqid();

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("INSERT INTO admin (uid_admin, nama_admin, usrname_admin, password_admin, email_admin, notlp_admin) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $uid, $nama, $username, $hashed_password, $email, $notlp);

if ($stmt->execute()) {
    echo "Admin berhasil dibuat!";
} else {
    echo "Gagal: " . $stmt->error;
}
?>
