<?php
session_start();

if (!isset($_SESSION['id']))  {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$tanggal = date("Y-m-d");

$role = $_SESSION['role'];
$id_user = $_SESSION['id'];

$sql_user = "SELECT * FROM siswa WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $id_user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();

$sql_kehadiran = "SELECT * FROM kehadiran WHERE id_siswa = ?";
$stmt_kehadiran = $conn->prepare($sql_kehadiran);
$stmt_kehadiran->bind_param("i", $id_user);
$stmt_kehadiran->execute();
$result_kehadiran = $stmt_kehadiran->get_result();

$sql_kas = "SELECT * FROM kas WHERE id_siswa = ?";
$stmt_kas = $conn->prepare($sql_kas);
$stmt_kas->bind_param("i", $id_user);
$stmt_kas->execute();
$result_kas = $stmt_kas->get_result();

$stmt_user->close();
$stmt_kehadiran->close();
$stmt_kas->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-0 py-3">
  <div class="container-xl">
    <a class="navbar-brand" href="all_data.php">
        ORGANIGRAM
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <div class="navbar-nav mx-lg-auto">
        <a class="nav-item nav-link" href="dashboard.php" aria-current="page">Dashboard</a>
        <?php
        if ($role == "Ketua Kelas" || $role == "Wakil Ketua Kelas") {
          echo '<a href="all_data.php" class="nav-item nav-link">All Data</a>';
        }
        if ($role == "Sekretaris") {
          echo '<a href="manage_siswa.php" class="nav-item nav-link">Manage Siswa</a>';
        }
        if ($role == "Absensi") {
          echo '<a href="manage_kehadiran.php?tanggal=' . $tanggal . '" class="nav-item nav-link">Manage Kehadiran</a>';
        }
        if ($role == "Bendahara") {
          echo '<a href="manage_kas.php?tanggal=' . $tanggal . '" class="nav-item nav-link">Manage Kas</a>';
        }
        ?>
        <a class="nav-item nav-link active" href="riwayat.php" aria-current="page">Riwayat</a>
      </div>
      <div class="navbar-nav ms-lg-4">
        <a class="nav-item nav-link" href="profil.php">Profil</a>
      </div>
      <div class="d-flex align-items-lg-center mt-3 mt-lg-0">
      <a href="logout.php" class="btn btn-sm btn-danger w-full w-lg-auto" onclick="return confirm('Keluar dari sesi?')">
          Log Out
        </a>
      </div>
    </div>
  </div>
</nav>

    <div class="container">
    <h3 class="mt-5 text-center">Riwayat Kehadiran</h3>
    <table class="table table-bordered table-hover table-light">
        <tr class="table-warning">
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
        <?php
        while ($row_kehadiran = $result_kehadiran->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row_kehadiran['tanggal'] . "</td>";
            echo "<td>" . $row_kehadiran['status'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h3 class="mt-5 text-center">Riwayat Kas</h3>
    <table class="table table-bordered table-hover table-light">
        <tr class="table-warning">
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
        <?php
        while ($row_kas = $result_kas->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row_kas['tanggal'] . "</td>";
            echo "<td>" . $row_kas['status'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    </div>
</body>
</html>