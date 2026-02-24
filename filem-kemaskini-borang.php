<?php
session_start();
include('kawalan-admin.php');
include('connection.php');

// Dapatkan data filem
$id_filem = $_GET['id_filem'];
$result = mysqli_query($condb, "SELECT * FROM filem WHERE id_filem='$id_filem'");
$filem = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kemaskini Filem</title>
</head>
<body>
    <h2>Kemaskini Filem</h2>
    
    <form action="filem-kemaskini-proses.php" method="POST">
        <input type="hidden" name="id_filem" value="<?= $filem['id_filem'] ?>">
        
        Nama Filem: 
        <input type="text" name="nama_filem" value="<?= $filem['nama_filem'] ?>" 
               required><br><br>
        
        Gambar Semasa: 
        <?php if (!empty($filem['gambar'])): ?>
            <img src="<?= $filem['gambar'] ?>" alt="Gambar Filem" width="100"><br>
        <?php else: ?>
            <p>Tiada gambar</p>
        <?php endif; ?>
        <br>
        
        <input type="submit" value="Kemaskini">
        <a href="filem-senarai.php">Batal</a>
    </form>
</body>
</html>