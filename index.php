<?php
session_start();

include("header.php");
include("connection.php");

// Query undian mengikut hari dengan ORDER BY idhari
$query_hari = "
SELECT 
    j.idhari, 
    j.nama_hari, 
    c.id_filem, 
    c.nama_filem, 
    c.gambar, 
    COUNT(u.id_undi) AS jumlah_undian
FROM undian u
JOIN hari j ON u.idhari = j.idhari
JOIN filem c ON u.id_filem = c.id_filem
GROUP BY j.idhari, j.nama_hari, c.id_filem, c.nama_filem, c.gambar
ORDER BY j.idhari, jumlah_undian DESC";  // Perubahan di sini - ORDER BY idhari dahulu

$result_hari = mysqli_query($condb, $query_hari);

if (!$result_hari) {
    die("SQL Error: " . mysqli_error($condb));
}

// Susun data undian mengikut idhari
$undian_hari = [];
while ($row = mysqli_fetch_assoc($result_hari)) {
    $idhari = $row['idhari'];
    if (!isset($undian_hari[$idhari])) {
        $undian_hari[$idhari] = [
            'nama_hari' => $row['nama_hari'],
            'filem' => []
        ];
    }
    $undian_hari[$idhari]['filem'][] = $row;
}

// Urutkan array berdasarkan idhari (jika perlu)
ksort($undian_hari);
?>

<table width="100%">
    <tr>
        <td width="70%" bgcolor="#925c3a" align="center">
            <img src="banner.jpg" style="width:50%; max-width:400px;">
        </td>
        <td align="center" bgcolor="#925c3a">
            <h3>Daftar Sebagai Pengundi</h3>
            <h3>Klik Pautan Dibawah</h3>
            <a href="login-borang.php">Log Masuk</a><br>
            <a href="signup.php">Daftar Pengguna Baharu</a>
        </td>
    </tr>
</table>

<!-- Paparan Undian Mengikut Hari -->
<div style="padding: 20px; background-color: #77401c; margin-top: 20px;">
    <h2 style="text-align: center; color: #000000;">UNDIAN SEMASA MENGIKUT HARI</h2>
    
    <?php foreach ($undian_hari as $idhari => $data_hari): ?>
        <div style="margin-bottom: 30px; background-color: white; padding: 15px; 
                    border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="color: #946528; border-bottom: 2px solid 
                    #000000; padding-bottom: 5px;">
                <?= $data_hari['nama_hari'] ?>
            </h3>
            
            <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px;">
                <?php foreach ($data_hari['filem'] as $undian): ?>
                    <div style="flex: 1; min-width: 200px; background-color: #66412a;
                                 padding: 10px; border-radius: 5px; border-left: 4px solid #66412a;">
                        <div style="display: flex; align-items: center;">
                            <img src="<?= $undian['gambar'] ?>" 
                                 alt="<?= $undian['nama_filem'] ?>" 
                                 style="width: 60px; height: 75px; object-fit: cover; 
                                        border-radius: 5px; margin-right: 10px;">
                            <div>
                                <h4 style="margin: 0;"><?= $undian['nama_filem'] ?></h4>
                                <p style="margin: 5px 0; color: #5a3f3f; font-weight: bold;">
                                    Undian: <?= $undian['jumlah_undian'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include("footer.php"); ?>