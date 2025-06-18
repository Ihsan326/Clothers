<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
?>

<style>
  .main-header {
    background-color: #fff;
    border-bottom: 1px solid #eee;
    padding: 10px 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  .header-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
  }

  .logo-container {
    flex-shrink: 0;
  }

  .logo-img {
    height: 40px;
    width: 40px;
    object-fit: cover;
    border-radius: 50%;
  }

  .search-wrapper {
    flex: 1;
    position: relative;
  }

  .search-form {
    position: relative;
    width: 100%;
  }

  .search-input {
    width: 100%;
    padding: 10px 40px 10px 16px;
    border-radius: 30px;
    border: 1px solid #ccc;
    background-color: #fffaf3;
    font-size: 14px;
  }

  .search-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    color: #555;
  }

  .profile-section {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
  }

  .profile-link {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #333;
  }

  .profile-pic {
    width: 36px;
    height: 36px;
    object-fit: cover;
    border-radius: 50%;
  }

  .profile-name {
    font-size: 14px;
    font-weight: 500;
  }

  .auth-buttons .auth-link {
    margin-left: 10px;
    font-size: 14px;
    color: #007bff;
    text-decoration: none;
  }

  .auth-buttons .auth-link:hover {
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .header-container {
      flex-direction: column;
      align-items: stretch;
      gap: 10px;
    }

    .search-wrapper {
      width: 100%;
    }

    .profile-section {
      justify-content: center;
    }
  }
</style>

<header class="main-header">
  <div class="header-container">
    <!-- Logo -->
    <div class="logo-container">
      <a href="../public/index.php">
        <img src="../assets/baseimages/clothers.jpg" alt="Clothers Logo" class="logo-img">
      </a>
    </div>

    <!-- Search -->
<!-- Search Bar -->
    <div class="search-wrapper" style="position: relative; width: 100%;">
        <form action="filter_page.php" method="GET" style="position: relative; display: flex;">
            <input type="text" name="q" placeholder="Cari produk..." required
                style="width: 100%; padding: 10px 40px 10px 15px; border-radius: 20px; border: 1px solid #ccc; background-color: #fffaf3;">
        </form>
    </div>

    <!-- Profil/Login -->
    <div class="profile-section">
      <?php if (isset($_SESSION['user'])): ?>
        <a href="../public/user_profile.php" class="profile-link">
          <img src="<?= !empty($user['foto_profil']) ? '../uploads/' . htmlspecialchars($user['foto_profil']) : '../assets/defaultprof.jpeg' ?>" class="profile-pic" alt="Foto Profil">
          <span class="profile-name"><?= htmlspecialchars($_SESSION['user']['usrname']) ?></span>
        </a>
      <?php else: ?>
        <div class="auth-buttons">
          <a href="login.php" class="auth-link">Login</a>
          <a href="register.php" class="auth-link">Registrasi</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>
