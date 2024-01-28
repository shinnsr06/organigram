<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Sekretaris')  {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID siswa tidak valid";
    header("Refresh:1; url=manage_siswa.php");
    exit();
}

$id_siswa = $_GET['id'];

$query_siswa = "SELECT * FROM siswa WHERE id = $id_siswa";
$result_siswa = $conn->query($query_siswa);

if ($result_siswa->num_rows == 0) {
    echo "Data siswa tidak ditemukan";
    header("Refresh:1; url=manage_siswa.php");
    exit();
}

$row = $result_siswa->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $role = $_POST['role'];

    $update_query = "UPDATE siswa SET nama = '$nama', role = '$role' WHERE id = $id_siswa";

    if ($conn->query($update_query) === TRUE) {
        echo "Data siswa berhasil diperbarui";
        header("Refresh:1; url=manage_siswa.php");
    } else {
        echo "Error: " . $update_query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-9">
    <a href="manage_siswa.php"><button class="btn btn-secondary">Kembali</button></a><br><br>
    <form method="post" action="">
    <h2 class="form-signin-heading">Edit Student</h2>
    <div  class="mb-3">
        <label for="nama" class="form-label">Nama Siswa </label>
        <input type="text" id="nama" class="form-control" name="nama" value="<?php echo $row['nama']; ?>">
    </div>
        <select name="role" class="form-select" id="role">
  <option selected>Pilih Role Siswa</option>
            <option value="Siswa">Siswa</option>
            <option value="Ketua Kelas">Ketua Kelas</option>
            <option value="Wakil Ketua Kelas">Wakil Ketua Kelas</option>
            <option value="Sekretaris">Sekretaris</option>
            <option value="Absensi">Absensi</option>
            <option value="Bendahara">Bendahara</option>
        </select><br>
        <button type="submit" class="btn btn-warning">Submit</button>
    </form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
