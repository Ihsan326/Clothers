<?php
include '../partials/header.php';
include '../partials/navbar.php';
?>

<link rel="stylesheet" href="../assets/style.css">

<div class="container-panel">
    <title>Register Page</title>
    <h2>Daftar Akun</h2>
    <form method="post" action="proses_register.php">
        <label>Nama Depan</label>
        <input type="text" name="nama_depan" required>

        <label>Nama Belakang</label>
        <input type="text" name="nama_belakang" required>

        <label>Username</label>
        <input type="text" name="usrname" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Daftar</button>
    </form>
</div>

<?php
include '../partials/footer.php';
?>