<?php
session_start();
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

$role = $_SESSION['role'];
$id = $_SESSION['id'];

$sql_siswa = "SELECT * FROM siswa";
$result_siswa = $conn->query($sql_siswa);

if ($role == "Absensi" && isset($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
    $hari = date('N', strtotime($tanggal));

    if ($hari == 6 || $hari == 7 || $tanggal >= '2024-01-01' && $tanggal <= '2024-01-07') {
        $keterangan = "Hari Libur";
    } else {
        $sql_kehadiran = "SELECT k.*, s.nama 
                         FROM kehadiran k 
                         JOIN siswa s ON k.id_siswa = s.id 
                         WHERE k.tanggal = '$tanggal'";
        $result_kehadiran = $conn->query($sql_kehadiran);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managemen Data Kehadiran</title>
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
        <a href="dashboard.php" class="nav-item nav-link" aria-current="page">Dashboard</a>
        <?php
        if ($role == "Ketua Kelas" || $role == "Wakil Ketua Kelas") {
          echo '<a href="all_data.php" class="nav-item nav-link">All Data</a>';
        }
        if ($role == "Sekretaris") {
          echo '<a href="manage_siswa.php" class="nav-item nav-link">Manage Siswa</a>';
        }
        if ($role == "Absensi") {
            echo '<a href="manage_kehadiran.php?tanggal=' . (isset($tanggal) ? $tanggal : date('Y-m-d')) . '" class="nav-item nav-link active">Manage Kehadiran</a>';
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
    <?php if ($role == "Absensi") { ?>
        <p class="mt-5">Pilih Hari dan Tanggal:</p>
        <form action="manage_kehadiran.php" method="GET">
            <input type="date" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d'); ?>" required>
            <input type="submit" value="Tampilkan">
        </form>
        <br>
        <?php if (isset($keterangan)) { ?>
            <p><?php echo $keterangan; ?></p>
        <?php } else { ?>
            <?php if ($result_siswa->num_rows > 0) { ?>
                <table class="table table-bordered table-hover table-light">
                    <tr class="table-warning">
                        <th>ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th>Kehadiran</th>
                        <th>Aksi</th>
                    </tr>
                    <?php while ($row_siswa = $result_siswa->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row_siswa['id']; ?></td>
                            <td><?php echo $row_siswa['nama']; ?></td>
                            <td>
                                <?php
                                if (isset($result_kehadiran)) {
                                    $hadir = false;
                                    while ($row_kehadiran = $result_kehadiran->fetch_assoc()) {
                                        if ($row_kehadiran['id_siswa'] == $row_siswa['id']) {
                                            echo $row_kehadiran['status'] ?: '<span class="red">Belum diisi</span>';
                                            $hadir = true;
                                            break;
                                        }
                                    }
                                    if (!$hadir) {
                                        echo '<span class="red">Belum diisi</span>';
                                    }
                                    $result_kehadiran->data_seek(0);
                                } else {
                                    echo '<span class="red">Belum diisi</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!$hadir) {
                                    echo '<a href="tambah_kehadiran.php?id_siswa=' . $row_siswa['id'] . '&tanggal=' . $tanggal . '"><button class="btn btn-outline-secondary">Tambah Keterangan</button></a>';
                                } else {
                                    echo '<a href="edit_kehadiran.php?id_siswa=' . $row_siswa['id'] . '&tanggal=' . $tanggal . '"><button class="btn btn-outline-secondary">Edit Keterangan</button></a>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else {
                echo "Tidak ada data siswa.";
            } ?>
        <?php } ?>
    <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
