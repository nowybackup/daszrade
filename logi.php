<?php
echo '<a href="../index.php">Start</a> ';
echo '<a href="tryb_edycji.php">Tabele</a> ';
echo '<a href="nauka.php">Nauka</a> ';
echo '<a href="logi.php">Logi</a>';

wyswietl_logi();

function wyswietl_logi(){
      echo '<table border="0">
        <tr>
            <td> Przycisk edytuj w pliku <b>nauka.php</b> nie działa</td>
        </tr>
    	</table>';
  }
?>