<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-biasa.php');

// Ambil senarai filem
$filem = mysqli_query($condb, "SELECT * FROM FILEM");
$senarai = [];
while ($row = mysqli_fetch_assoc($filem)) {
  $senarai[] = $row;
}

// Ambil senarai hari dari pangkalan data
$hari_query = mysqli_query($condb, "SELECT * FROM hari");
$hari = [];
while ($row = mysqli_fetch_assoc($hari_query)) {
  $hari[] = $row['nama_hari'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Borang Undian Kelab Moral SMK TUNKU ABDUL RAHMAN</title>
  <script>
    function enforceSingleHariPerFilem() {
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      const selectedHari = {};
      
      checkboxes.forEach(checkbox => {
        const filemId = checkbox.name.match(/\[(.*?)\]/)[1];
        
        if (!selectedHari[filemId]) {
          selectedHari[filemId] = {
            selected: null,
            checkboxes: []
          };
        }
        
        selectedHari[filemId].checkboxes.push(checkbox);
        
        if (checkbox.checked) {
          selectedHari[filemId].selected = checkbox.value;
        }
      });
      
      Object.values(selectedHari).forEach(filem => {
        filem.checkboxes.forEach(checkbox => {
          if (filem.selected && checkbox.value !== filem.selected) {
            checkbox.disabled = true;
          } else {
            checkbox.disabled = false;
          }
        });
      });
      
      disableDuplicateSelection();
    }
    
    function disableDuplicateSelection() {
      const selectedValues = {};
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      
      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          if (selectedValues[checkbox.value]) {
            checkbox.checked = false;
          } else {
            selectedValues[checkbox.value] = true;
          }
        }
      });
      
      checkboxes.forEach(checkbox => {
        if (selectedValues[checkbox.value] && !checkbox.checked) {
          checkbox.disabled = true;
        }
      });
    }
    
    function handleCheckboxClick(checkbox) {
      const filemId = checkbox.name.match(/\[(.*?)\]/)[1];
      const filemCheckboxes = document.querySelectorAll(`input[name="undi[${filemId}]"]`);
      
      filemCheckboxes.forEach(cb => {
        if (cb !== checkbox) {
          cb.checked = false;
        }
      });
      
      enforceSingleHariPerFilem();
    }
  </script>
</head>
<body>
  <h2>BORANG UNDIAN  KELAB MORAL SMK TUNKU ABDUL RAHMAN</h2>

  <form action="proses_undi_kedudukan.php" method="POST" 
        onchange="enforceSingleHariPerFilem()">
        
    <input type="hidden" name="nokp" value="<?=$_SESSION['nokp']?>">

    <?php
    foreach ($senarai as $cl) {
      echo '<div>';
      echo '<img src="'.$cl['gambar'].'" alt="'.$cl['nama_filem'].'" width="150">';
      echo '<p>'.$cl['nama_filem'].'</p>';

      foreach ($hari as $j) {
        echo '<label>';
        echo '<input type="checkbox" name="undi['.$cl['id_filem'].']" value="'.$j.'" 
                onclick="handleCheckboxClick(this)"> '.$j;
        echo '</label><br>';
      }

      echo '<hr></div>';
    }
    ?>
    
    <input type="submit" value="SUBMIT">
  </form>
</body>
</html>
