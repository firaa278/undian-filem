<?php
# Memulakan sesi
session_start();
error_reporting(0);

# Memanggil fail header.php, connection.php, dan kawalan-admin.php 
include('header.php');
include('connection.php');
include('kawalan-admin.php');

?>
<h3 align='center'>Senarai Filem</h3>

<!-- Header bagi jadual untuk memaparkan senarai filem-->
<table align='center' width='70%' border='1' id='saiz'>
    <tr bgcolor="#793e27">
        <td colspan='2' align='right'>
            <form action='' method='POST' style="margin:0; padding:0;">
                <input type='text' name='nama_filem' placeholder='Carian filem'>
                <input type='submit' value='Cari'>
            </form>
        </td>
        <td colspan='5' align='right'>
            | <a href=filem-daftar.php>Daftar Filem Baru</a> |
            <!-- Memanggil fail butang-saiz bagi membolehkan pengguna mengubah saiz tulisan -->
            <?php include('butang-saiz.php'); ?>
        </td>
    </tr>
    <tr bgcolor="#794527" align='center'>
        <td>ID Filem</td>
        <td>Nama Filem</td>
        <td>Gambar</td>
        <td>Tindakan</td>
    </tr>

<?php

# Syarat tambahan yang akan dimasukkan dalam arahan(query) senarai filem
$tambahan = "";
if(!empty($_POST['nama_filem'])) {
    $tambahan = "WHERE nama_filem LIKE '%".$_POST['nama_filem']."%'";
}

# Arahan query utuk mencari filem
$arahan_papar = "SELECT * FROM filem $tambahan";

# Laksanakan arahan mencari data filem
$laksana = mysqli_query($condb, $arahan_papar);

# Mengambil data yang ditemui 
while($row= mysqli_fetch_array($laksana)) {
    # Memaparkan senarai filem dalam jadual 
    echo "<tr>
        <td>{$row['id_filem']}</td>
        <td>{$row['nama_filem']}</td>
        <td class='gambar-cell'><img src='{$row['gambar']}' alt='{$row['nama_filem']}'
            style='max-width: 100px; height: auto; '></td> 
        <td align='right'>

            | <a href='filem-padam.php?id_filem=".$row['id_filem']."'
                onClick=\"return confirm('Anda pasti anda ingin memadam data ini?')\">Hapus</a>
            | <a href='filem-kemaskini-borang.php?id_filem=".$row['id_filem']."'>Kemaskini</a> |

        </td>
    </tr>";
}

?>
</table>
<?php include('footer.php'); ?>