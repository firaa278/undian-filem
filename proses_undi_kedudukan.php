<?php
session_start();
include('connection.php');
include('kawalan-biasa.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['undi'])) {
    $nokp = mysqli_real_escape_string($condb, $_POST['nokp']);
    $undi = $_POST['undi']; // undi[id_filem] = nama_hari

    // Mula transaksi untuk memastikan semua undian disimpan atau tiada langsung
    mysqli_begin_transaction($condb);

    try {
        // Pertama, semak jika pengguna sudah mengundi sebelum ini
        $semak_undi = mysqli_query($condb, "SELECT * FROM undian WHERE nokp='$nokp'");
        if (mysqli_num_rows($semak_undi) > 0) {
            throw new Exception("Anda sudah mengundi sebelum ini.");
        }

        // Proses setiap undian
        foreach ($undi as $id_filem => $nama_hari) {
            $id_filem = mysqli_real_escape_string($condb, $id_filem);
            $nama_hari = mysqli_real_escape_string($condb, $nama_hari);

            // Dapatkan idhari berdasarkan nama hari
            $hari_query = mysqli_query($condb, "SELECT idhari FROM hari
                            WHERE nama_hari='$nama_hari'");

            if (!$hari_query || mysqli_num_rows($hari_query) == 0) {
                throw new Exception("Hari tidak ditemukan.");
            }
            
            $hari_data = mysqli_fetch_assoc($hari_query);
            $idhari = $hari_data['idhari'];

            // Simpan undian ke pangkalan data
            $insert_query = "INSERT INTO undian (nokp, id_filem, idhari) 
                            VALUES ('$nokp', '$id_filem', '$idhari')";
            
            if (!mysqli_query($condb, $insert_query)) {
                throw new Exception("Gagal menyimpan undian: " . mysqli_error($condb));
            }
        }

        // Jika semua berjaya, commit transaksi
        mysqli_commit($condb);
        echo "<script>alert('Undian anda telah direkodkan. Terima kasih!'); 
                window.location='index.php';</script>";
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($condb);
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); 
                window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Tiada data undian dihantar.'); 
            window.location='undi_kedudukan.php';</script>";
}
?>