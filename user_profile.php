<?php
require_once '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/navbar.php'; ?>
<link rel="stylesheet" href="../assets/style.css">

<div class="profile-layout">
  <!-- Sidebar Profil -->
  <?php include '../partials/sidebar_profile.php'; ?>

  <!-- Konten Profil -->
  <div class="user-profile-container expanded">
    <title><?= htmlspecialchars($user['usrname']) ?></title>
    <h2>Profil Saya</h2>

    <div class="profile-flex">
      <!-- Kiri: Foto dan Upload -->
      <div class="profile-left">
        <form id="formUploadFoto" action="upload_foto.php" method="POST" enctype="multipart/form-data">
          <img src="<?= !empty($user['foto_profil']) ? '../uploads/' . htmlspecialchars($user['foto_profil']) : '../assets/defaultprof.jpeg' ?>" 
               alt="Foto Profil" class="profile-pic-large" id="fotoPreview">
          <input type="file" name="foto_profil" accept="image/*" class="file-input-hidden" id="fotoInput">
          <button type="button" class="btn" onclick="document.getElementById('fotoInput').click()">Ganti Foto</button>
        </form>
      </div>

      <!-- Kanan: Info & Aksi -->
      <div class="profile-right">
        <div class="profile-info">
          <p><strong>ID User:</strong> <?= htmlspecialchars($user['uid']) ?></p>
          <p><strong>Username:</strong> <?= htmlspecialchars($user['usrname']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
          <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama_depan'] ?? '-') ?> <?= htmlspecialchars($user['nama_belakang'] ?? '-') ?></p>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include '../partials/footer.php'; ?>

<script>
  document.getElementById('fotoInput').addEventListener('change', function () {
    if (this.files.length > 0) {
      document.getElementById('formUploadFoto').submit();
    }
  });
</script>
