<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Velaris Hotel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Registrasi Customer</h2>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <p style="color:green"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>

<form action="../api/auth/register.php" method="POST">
    <label>Nama</label><br>
    <input type="text" name="nama_lengkap" required><br><br>
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>No HP</label><br>
    <input type="text" name="no_hp" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Konfirmasi Password</label><br>
    <input type="password" name="password_confirm" required><br><br>

    <button type="submit">Daftar</button>
</form>

<p>Sudah punya akun?
    <a href="login.php">Login</a>
</p>

</body>
</html>
