<?php
// Memulakan fungsi session
session_start();

// Memanggil fail header.php dan sambungan database
include("header.php");
include("connection.php");
include("kawalan-admin.php");

// Fungsi untuk padam semua undian
if (isset($_POST['padam_semua'])) {
    $sql_padam = "DELETE FROM undian";
    if (mysqli_query($condb, $sql_padam)) {
        echo "<script>alert('Semua undian telah dipadam!'); 
        window.location.href='keputusan.php';</script>";
    } else {
        echo "<script>alert('Ralat: " . mysqli_error($condb) . "');</script>";
    }
}

// Query untuk pemenang keseluruhan
$query_pemenang = "
    SELECT c.id_filem, c.nama_filem, c.gambar, 
    COUNT(u.id_undi) as jumlah_undian
    FROM undian u
    JOIN filem c ON u.id_filem = c.id_filem
    GROUP BY c.id_filem, c.nama_filem, c.gambar
    ORDER BY jumlah_undian DESC
    LIMIT 1
";
$result_pemenang = mysqli_query($condb, $query_pemenang);
$pemenang_keseluruhan = mysqli_fetch_assoc($result_pemenang);

// Query untuk pemenang setiap hari
$query_hari = "
    SELECT j.idhari, j.nama_hari, 
           c.id_filem, c.nama_filem, c.gambar, 
           COUNT(u.id_undi) as jumlah_undian
    FROM undian u
    JOIN hari j ON u.idhari = j.idhari
    JOIN filem c ON u.id_filem = c.id_filem
    GROUP BY j.idhari, j.nama_hari, c.id_filem, c.nama_filem, c.gambar
    ORDER BY j.nama_hari, jumlah_undian DESC
";
$result_hari = mysqli_query($condb, $query_hari);

// Susun data pemenang mengikut hari
$pemenang_hari = [];
while ($row = mysqli_fetch_assoc($result_hari)) {
    $hari = $row['nama_hari'];
    if (!isset($pemenang_hari[$hari]) || $row['jumlah_undian'] > 
        $pemenang_hari[$hari]['jumlah_undian']) 
        {$pemenang_hari[$hari] = $row;
    }
}
?>

<table width="100%" border="1">
    <tr>
        <td colspan="2" align="center">
            <h2>KEPUTUSAN UNDIAN</h2>
        </td>
    </tr>
     
    <!-- Pemenang Keseluruhan -->
    <tr>
        <td colspan="2" bgcolor="#eeeeee">
            <h3>PEMENANG KESELURUHAN</h3>
            <?php if ($pemenang_keseluruhan): ?>
                <table>
                    <tr>
                        <td>
                            <img src="<?= $pemenang_keseluruhan['gambar'] ?>" 
                                 alt="<?= $pemenang_keseluruhan['nama_filem'] ?>" 
                                 width="120" height="150">
                        </td>
                        <td>
                            <h3><?= $pemenang_keseluruhan['nama_filem'] ?></h3>
                            <p><b>Jumlah Undian:</b> <?= $pemenang_keseluruhan['jumlah_undian'] ?></p>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <p>Tiada data pemenang keseluruhan.</p>
            <?php endif; ?>
        </td>
    </tr>
    
    <!-- Pemenang Mengikut Hari -->
    <tr>
        <td colspan="2">
            <h3>PEMENANG MENGIKUT HARI</h3>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" border="1" cellpadding="5">
                <?php foreach ($pemenang_hari as $hari => $pemenang): ?>
                <tr>
                    <td width="30%" bgcolor="#eeeeee">
                        <h4>Hari: <?= $hari ?></h4>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <img src="<?= $pemenang['gambar'] ?>" 
                                         alt="<?= $pemenang['nama_filem'] ?>" 
                                         width="80" height="100">
                                </td>
                                <td>
                                    <h4><?= $pemenang['nama_filem'] ?></h4>
                                    <p><b>Undian:</b> <?= $pemenang['jumlah_undian'] ?></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php endforeach; ?>
                
            </table>
        </td>
    </tr>
</table>
 
<!-- Butang Padam Semua Undian (Hanya untuk Admin) -->
    <?php if ($_SESSION['tahap'] == 'ADMIN'): ?>
    <tr>
        <td colspan="2" align="right">
            <form method="POST" onsubmit="return confirm
            ('Adakah anda pasti ingin memadam SEMUA undian? Tindakan ini tidak boleh dipulihkan.');">
                <button type="submit" name="padam_semua" style="background-color: #834a14; 
                color: white; padding: 8px 15px; border: none; cursor: pointer;">Padam Semua Undian
                </button>
                </form>
               
        </td>
    </tr>
 <!-- Butang Cetak Semua Undian-->
    <button onclick="window.print()" class="print-btn" style="background-color: rgb(0, 0, 0); 
            color: white; padding: 8px 40px; border: none; cursor: pointer;">Cetak Laporan</button>
    <?php endif; ?>


<?php include("footer.php"); ?>