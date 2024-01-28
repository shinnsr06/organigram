<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_siswa = mysqli_real_escape_string($conn, $_POST['id_siswa']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $check_sql = "SELECT COUNT(*) AS count FROM kehadiran WHERE id_siswa = ? AND tanggal = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $id_siswa, $tanggal);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $existing_entries = $row['count'];

    if ($existing_entries > 0) {
        echo "Kehadiran untuk siswa ini pada tanggal tersebut sudah ada.";
        header("Refresh:2");
    } else {
        $insert_sql = "INSERT INTO kehadiran (id_siswa, tanggal, status) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iss", $id_siswa, $tanggal, $status);
        if ($insert_stmt->execute()) {
            echo "Kehadiran berhasil ditambahkan.";
            header("Refresh:1; url=manage_kehadiran.php?tanggal=$tanggal");
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
        $insert_stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-9">
    <a href="manage_kehadiran.php?tanggal=<?php echo $_GET['tanggal']; ?>"><button class="btn btn-secondary">Kembali</button></a><br><br>
    <form action="" method="POST">
        <h2 class="form-signin-heading">Tambah Data</h2>
        <input type="hidden" name="id_siswa" value="<?php echo $_GET['id_siswa']; ?>">
        <input type="hidden" name="tanggal" value="<?php echo $_GET['tanggal']; ?>">
        <select name="status" class="form-select" id="status">
            <option selected>Pilih keterangan kehadiran</option>
            <option value="Hadir">Hadir</option>
            <option value="Sakit">Sakit</option>
            <option value="Izin">Izin</option>
            <option value="Tanpa Keterangan">Tanpa Keterangan</option>
        </select>
        <br>
        <button type="submit" class="btn btn-warning">Submit</button>
    </form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
