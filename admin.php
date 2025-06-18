<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login_admin.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="../assets/admin-style.css">
</head>
<body>

<?php include 'dashboard_admin.php'; ?>

<div class="main-content">
  <h1>Selamat Datang, <?= $_SESSION['nama_admin']; ?>!</h1>
  <p>Gunakan menu di sebelah kiri untuk mengelola data.</p>
</div>

<!-- JavaScript diletakkan di sini, setelah semua elemen dimuat -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll("dropdown-toggle");

    toggles.forEach(function (toggle) {
      toggle.addEventListener("click", function (e) {
        e.preventDefault();
        const parent = toggle.closest("sidebar-dropdown");

        // Tutup dropdown lain jika ada yang terbuka
        document.querySelectorAll("sidebar-dropdown").forEach(function (dropdown) {
          if (dropdown !== parent) {
            dropdown.classList.remove("open");
          }
        });

        // Toggle dropdown ini
        parent.classList.toggle("open");
      });
    });
  });
</script>

</body>
</html>
