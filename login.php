<?php
include '../partials/header.php';
include '../partials/navbar.php';
?>

<link rel="stylesheet" href="../assets/style.css">

<div class="container-panel">
    <title>Login Page</title>
    <h2>LOGIN</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-message" style="color: red; font-size: 14px; margin-bottom: 10px;">
            <?php
                if ($_GET['error'] === 'empty') {
                    echo "Username dan password tidak boleh kosong!";
                } elseif ($_GET['error'] === 'invalid') {
                    echo "Username atau password salah!";
                }
            ?>
        </div>
    <?php endif; ?>

    <form method="post" action="proses_login.php">
        <label>Username</label>
        <input type="text" name="usrname" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Login</button>

        <p style="margin-top: 15px; font-size: 13px;">
            <a href="register.php">I don't have an account</a>
        </p>
    </form>
</div>

<?php
include '../partials/footer.php';
?>