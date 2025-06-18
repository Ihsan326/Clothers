<div class="sidebar">
  <h2>Admin Panel</h2>
  <a href="admin.php" class="sidebar-link">🏠 Dashboard</a>

  <div class="sidebar-dropdown">
    <a href="#" class="dropdown-toggle">📦 Produk <span class="arrow">▼</span></a>
    <div class="sidebar-submenu">
      <a href="tambah_produk.php">➕ Tambah Produk</a>
      <a href="produk_admin.php">📋 Daftar Produk</a>
      <a href="brand.php">🏷️ Brand</a>
      <a href="kategori.php">🗂️ Kategori</a>
      <a href="kondisi.php">📌 Kondisi</a>
    </div>
  </div>

  <a href="pesanan_admin.php" class="sidebar-link">📦 Daftar Pesanan</a>
  <a href="ambilditoko_admin.php" class="sidebar-link">📍 Ambil di Toko</a>
  <a href="customer_admin.php" class="sidebar-link">👤 Data Customer</a>
  <a href="admin_registrasi.php" class="sidebar-link">👥 Tambah Admin</a>
  <a href="logout_admin.php" class="sidebar-link logout" style="color: #ff6666;">🚪 Logout</a>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

    dropdownToggles.forEach(toggle => {
      toggle.addEventListener("click", function (e) {
        e.preventDefault();
        const parent = this.closest(".sidebar-dropdown");
        parent.classList.toggle("open");
      });
    });
  });
</script>
