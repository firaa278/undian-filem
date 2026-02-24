<?php
session_start();
include('kawalan-admin.php');
include('connection.php');

// Dapatkan data dari borang
$id_filem = $_POST['id_filem'];
$nama_filem = mysqli_real_escape_string($condb, $_POST['nama_filem']);

// Kemaskini nama filem sahaja (tanpa ubah gambar)
$sql = "UPDATE filem SET nama_filem='$nama_filem' WHERE id_filem='$id_filem'";

if (mysqli_query($condb, $sql)) {
    echo "<script>alert('Kemaskini berjaya'); 
    window.location.href='filem-senarai.php';</script>";
} else {
    echo "<script>alert('Ralat: " . addslashes(mysqli_error($condb)) . "');
          window.history.back();</script>";
}