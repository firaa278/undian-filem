<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');

$id = $_GET['id'] ?? '';

// Dapatkan data hari
$result = mysqli_query($condb, "SELECT * FROM hari WHERE idhari = '$id'");
$hari = mysqli_fetch_assoc($result);

// Proses form jika data dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_hari = mysqli_real_escape_string($condb, $_POST['id_hari']);
    $nama_hari = mysqli_real_escape_string($condb, $_POST['nama_hari']);
    
    // Semak jika ID baru sudah wujud (kecuali jika sama dengan ID asal)
    if ($id_hari != $id) {
        $check_sql = "SELECT idhari FROM hari WHERE idhari = '$id_hari'";
        $check_result = mysqli_query($condb, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            echo 
            "<script>alert('Ralat: ID Hari \"$id_hari\" sudah wujud dalam sistem!');</script>";

        } else {
            // Kemaskini kedua-dua ID dan nama jika ID baru unik
            $sql = "UPDATE hari SET idhari = '$id_hari', nama_hari = '$nama_hari' 
                    WHERE idhari = '$id'";
            
            if (mysqli_query($condb, $sql)) {
                echo "<script>alert('Hari berjaya dikemaskini!'); 
                window.location.href='hari-daftar.php';</script>";
            } else {
                echo "<script>alert('Ralat: " . mysqli_error($condb) . "');</script>";
            }
        }
    } else {
        // Jika ID tidak berubah, hanya kemaskini nama hari
        $sql = "UPDATE hari SET nama_hari = '$nama_hari' WHERE idhari = '$id'";
        
        if (mysqli_query($condb, $sql)) {
            echo "<script>alert('Hari berjaya dikemaskini!'); 
            window.location.href='hari-daftar.php';</script>";
        } else {
            echo "<script>alert('Ralat: " . mysqli_error($condb) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kemaskini Hari</title>
</head>
<body>
    <h2>KEMASKINI HARI</h2>
    
    <form method="POST" action="">
        <table border="1">
            <tr>
                <td>ID Hari:</td>
                <td><input type="text" name="id_hari" 
                        value="<?php echo $hari['idhari']; ?>" required></td>
            </tr>
            <tr>
                <td>Nama Hari:</td>
                <td><input type="text" name="nama_hari" 
                        value="<?php echo $hari['nama_hari']; ?>" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button type="submit">Kemaskini</button>
                    <a href="hari-daftar.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>