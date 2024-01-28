<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM siswa WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        header("Location: dashboard.php");
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="form.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="form-wrapper">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-control">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="form-control">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>