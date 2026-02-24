<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');

$id = $_GET['id'] ?? '';

if (!empty($id)) {
    $sql = "DELETE FROM hari WHERE idhari = '$id'";
    
    if (mysqli_query($condb, $sql)) {
        echo "<script>alert('Hari berjaya dipadam!'); 
            window.location.href='hari-daftar.php';</script>";
    } else {
        echo "<script>alert('Ralat: " . mysqli_error($condb) . "');
             window.location.href='hari-daftar.php';</script>";
    }
} else {
    header("Location: hari-daftar.php");
}
?>
