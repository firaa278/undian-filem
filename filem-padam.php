<?php
session_start();
include('kawalan-admin.php');

if(!empty($_GET['id_filem'])) {
    include('connection.php');
    
    $id_filem = mysqli_real_escape_string($condb, $_GET['id_filem']);
    
    // Mulakan transaksi
    mysqli_begin_transaction($condb);
    
    try {
        // 1. Padam semua undian untuk filem ini
        $delete_undian = "DELETE FROM undian WHERE id_filem = '$id_filem'";
        if(!mysqli_query($condb, $delete_undian)) {
            throw new Exception("Gagal memadam undian: " . mysqli_error($condb));
        }
        
        // 2. Padam filem
        $delete_filem = "DELETE FROM filem WHERE id_filem = '$id_filem'";
        if(!mysqli_query($condb, $delete_filem)) {
            throw new Exception("Gagal memadam filem: " . mysqli_error($condb));
        }
        
        // Jika semua berjaya, commit transaksi
        mysqli_commit($condb);
        echo "<script>alert('Padam filem berjaya');
        window.location.href = 'filem-senarai.php';</script>";
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($condb);
        echo "<script>alert('Padam filem gagal: " . addslashes($e->getMessage()) . "');
        window.location.href = 'filem-senarai.php';</script>";
    }
} else {
    die("<script>alert('Ralat! Akses secara langsung');
    window.location.href = 'filem-senarai.php';</script>");
}
?>