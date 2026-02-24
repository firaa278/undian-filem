<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');

// Proses form jika data dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_hari = mysqli_real_escape_string($condb, $_POST['nama_hari']);
    
    // Generate ID Hari (contoh: K1, K2, ...)
    $result = mysqli_query($condb, "SELECT MAX(idhari) as max_id FROM hari");
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];
    $next_id = 'K1'; // Default jika tiada rekod
    
    if ($max_id) {
        $num = (int)substr($max_id, 1) + 1;
        $next_id = 'K' . $num;
    }
    
    // Masukkan data ke pangkalan data
    $sql = "INSERT INTO hari(idhari, nama_hari) 
            VALUES ('$next_id', '$nama_hari')";
    
    if (mysqli_query($condb, $sql)) {
        echo "<script>alert('Hari berjaya didaftarkan!');</script>";
    } else {
        echo "<script>alert('Ralat: " . mysqli_error($condb) . "');</script>";
    }
}

// Dapatkan senarai hari sedia ada
$hari = mysqli_query($condb, "SELECT * FROM hari ORDER BY idhari");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Hari</title>
</head>
<body>
    <h2>BORANG DAFTAR HARI</h2>
    
    <form method="POST" action="">
        <table border="1">
            <tr>
                <td>Nama hari:</td>
                <td><input type="text" name="nama_hari" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><button 
                    type="submit">Daftar Hari</button></td>
            </tr>
        </table>
    </form>
    
    <h3>Senarai Hari Sedia Ada</h3>
    <table border="1" width="100%">
        <tr>
            <th>ID Hari</th>
            <th>Nama Hari</th>
            <th>Tindakan</th>
        </tr>
        <?php
        if (mysqli_num_rows($hari) > 0) {
            while ($row = mysqli_fetch_assoc($hari)) {
                echo "<tr>";
                echo "<td>" . $row['idhari'] . "</td>";
                echo "<td>" . $row['nama_hari'] . "</td>";
                echo "<td>";
                echo "<a href='hari-kemaskini.php?id=" . $row['idhari'] . "'>
                                Kemaskini</a> | ";
                echo "<a href='hari-padam.php?id=" . $row['idhari'] . "' 
                                onclick='return confirm(\"Anda pasti?\")'>Padam</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Tiada hari didaftarkan</td></tr>";
        }
        ?>
    </table>
</body>
</html>