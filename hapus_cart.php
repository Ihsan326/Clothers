<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_GET['id']) || !isset($_SESSION['user'])) {
    header("Location: keranjang.php");
    exit();
}

$id_cart = $_GET['id'];
$uid = $_SESSION['user']['uid'];

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("DELETE FROM cart WHERE id_cart = ? AND uid = ?");
$stmt->bind_param("is", $id_cart, $uid);
$stmt->execute();

header("Location: keranjang.php");
exit();
?>
