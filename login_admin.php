<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Login Admin</title></head>
<body>
    <h2>Login Admin</h2>
    <?php if (isset($_GET['error'])) echo "<p style='color:red;'>".$_GET['error']."</p>"; ?>
    <form method="POST" action="proses_login_admin.php">
        <input type="text" name="usrname_admin" placeholder="Username" required><br>
        <input type="email" name="email_admin" placeholder="Email" required><br>
        <input type="password" name="password_admin" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
