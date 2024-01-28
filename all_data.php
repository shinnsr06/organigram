<?php
session_start();

if (!isset($_SESSION['id']))  {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$role = $_SESSION['role'];


$sql_siswa = "SELECT * FROM siswa";
$result_siswa = $conn->query($sql_siswa);

$sql_kehadiran = "SELECT * FROM kehadiran";
$result_kehadiran = $conn->query($sql_kehadiran);

$sql_kas = "SELECT * FROM kas";
$result_kas = $conn->query($sql_kas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Data</title>
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
        <a href="dashboard.php" class="nav-item nav-link"aria-current="page">Dashboard</a>
        <?php
        if ($role == "Ketua Kelas" || $role == "Wakil Ketua Kelas") {
          echo '<a href="all_data.php" class="nav-item nav-link active">All Data</a>';
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
        <a class="nav-item nav-link" href="riwayat.php" aria-current="page">Riwayat</a>
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
<h3 class="mt-5 text-center">Data Siswa</h3>
    <table class="table table-bordered table-hover table-light">
        <tr class="table-warning">
            <th>Nama</th>
            <th>Role</th>
        </tr>
        <?php
        if ($result_siswa->num_rows > 0) {
            while ($row_siswa = $result_siswa->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_siswa['nama'] . "</td>";
                echo "<td>" . $row_siswa['role'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Tidak ada data siswa.</td></tr>";
        }
        ?>
    </table>

    <h3 class="mt-5 text-center">Data Kehadiran</h3>
    <table class="table table-bordered table-hover table-light">
        <tr class="table-warning">
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result_kehadiran->num_rows > 0) {
            while ($row_kehadiran = $result_kehadiran->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_kehadiran['tanggal'] . "</td>";
                echo "<td>" . $row_kehadiran['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Tidak ada data kehadiran.</td></tr>";
        }
        ?>
    </table>

    <h3 class="mt-5 text-center">Data Kas</h3>
    <table class="table table-bordered table-hover table-light">
        <tr class="table-warning">
            <th>Tanggal</th>
            <th>status</th>
        </tr>
        <?php
        if ($result_kas->num_rows > 0) {
            while ($row_kas = $result_kas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_kas['tanggal'] . "</td>";
                echo "<td>" . $row_kas['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Tidak ada data kas.</td></tr>";
        }
        ?>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
