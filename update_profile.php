<?php
session_start();

if (!isset($_SESSION['id']))  {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$id_user = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    
    $sql_update = "UPDATE siswa SET username = ?, password = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $new_username, $new_password, $id_user);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = "Profil berhasil diperbarui.";
        header("Location: profil.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui profil. Silakan coba lagi.";
        header("Location: profil.php");
        exit();
    }

    $stmt_update->close();
}

$conn->close();
?>
