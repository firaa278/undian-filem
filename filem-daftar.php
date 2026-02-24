<?php
session_start();
include('kawalan-admin.php');
include('connection.php');
include("header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_filem = $_POST['id_filem'];
    $nama = $_POST['nama_filem'];

    // Urus gambar
    $nama_gambar = $_FILES['gambar']['name'];
    $sementara = $_FILES['gambar']['tmp_name'];
    $lokasi = 'gambar/' . basename($nama_gambar);

    if (move_uploaded_file($sementara, $lokasi)) {
        $query = "INSERT INTO filem (id_filem, nama_filem, gambar) 
                  VALUES ('$id_filem','$nama', '$lokasi')";
        if (mysqli_query($condb, $query)) {
            echo "<script>alert('Pendaftaran berjaya!'); 
                window.location.href='filem-senarai.php';</script>";
        } else {
            // Semak jika ralat kerana Duplicate entry
            if (mysqli_errno($condb) == 1062) {
                echo "<script>
                alert('ID filem sudah didaftarkan oleh pengguna lain. Sila guna ID lain.');
                </script>";
            } else {
                echo "<script>alert('Ralat: " . mysqli_error($condb) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Gagal memuat naik gambar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Filem</title>
</head>
<body>
    <h2>DAFTAR FILEM PERTANDINGAN</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        
        ID Filem:<br>
        <input type="text" name="id_filem" required><br><br>

        Nama Filem:<br>
        <input type="text" name="nama_filem" required><br><br>

        Muat Naik Gambar:<br>
        <input type="file" name="gambar" accept="image/*" required><br><br>

        <input type="submit" value="DAFTAR">
    </form>
</body>
</html>
