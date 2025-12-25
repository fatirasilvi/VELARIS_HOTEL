<?php
session_start();

// Jika sudah login, redirect
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer') {
    header("Location: ../booking.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Velaris Hotel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Login Customer</h2>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form action="../api/auth/login.php" method="POST">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p>Belum punya akun?
    <a href="register.php">Daftar di sini</a>
</p>

</body>
</html>
