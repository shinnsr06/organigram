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

if ($role == "Bendahara" && isset($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
    $hari = date('N', strtotime($tanggal));

    if ($hari == 1 || $hari == 2 || $hari == 3 || $hari == 4 || $hari == 6 || $hari == 7 ) {
        $keterangan = "Pembayaran kas dilakukan setiap hari jum'at";
    } else {
        
        $sql_payment_dates = "SELECT DISTINCT tanggal FROM kas";
        $result_payment_dates = $conn->query($sql_payment_dates);

        $total_uang_kas = 0;
        while ($row_payment_date = $result_payment_dates->fetch_assoc()) {
            $payment_date = $row_payment_date['tanggal'];
            $sql_kas = "SELECT COUNT(*) as jumlah_pembayaran FROM kas WHERE tanggal = '$payment_date'";
            $result_kas = $conn->query($sql_kas);
            $row_kas = $result_kas->fetch_assoc();
            $total_uang_kas += $row_kas['jumlah_pembayaran'] * 2000;
        }
    }
}

$tanggal = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managemen Data Kas</title>
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

    <?php if ($role == "Bendahara") { ?>
            
            <p class="mt-5">Pilih Hari dan Tanggal:</p>
            <form action="manage_kas.php" method="GET">
                <input type="date" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d'); ?>" required>
                <input type="submit" value="Tampilkan">
            </form>

        <?php if (isset($keterangan)) { ?>
            <p><?php echo $keterangan; ?></p>
        <?php } else { ?>
            <?php if ($result_siswa->num_rows > 0) { ?>
                <p class="mt-5">Total Jumlah Uang Kas dari Keseluruhan Tanggal Pembayaran adalah <?php echo $total_uang_kas; ?></p>
                <table class="table table-bordered table-hover table-light">
                    <tr class="table-warning">
                        <th>ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th>Kas</th>
                        <th>Aksi</th>
                    </tr>
                    <?php while ($row_siswa = $result_siswa->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row_siswa['id']; ?></td>
                            <td><?php echo $row_siswa['nama']; ?></td>
                            <td>
                                <?php
                                $sql_kas = "SELECT COUNT(*) as jumlah_pembayaran FROM kas WHERE id_siswa = '{$row_siswa['id']}' AND tanggal = '$tanggal'";
                                $result_kas = $conn->query($sql_kas);
                                $row_kas = $result_kas->fetch_assoc();
                                $jumlah_pembayaran = $row_kas['jumlah_pembayaran'];
                                if ($jumlah_pembayaran > 0) {
                                    echo $jumlah_pembayaran * 2000;
                                } else {
                                    echo '<span class="red">Belum bayar</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($jumlah_pembayaran == 0) {
                                    echo '<a href="tambah_kas.php?id_siswa=' . $row_siswa['id'] . '&tanggal=' . $_GET['tanggal'] . '"><button class="btn btn-outline-secondary">Tambah Keterangan</button></a>';
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
