<script>
function setFocusToTextBox(){
    document.getElementById("znacznik").focus();
}
</script>

<?php

// paramatry poczatkowe


// typ pierwszy angielsko - poslki i polsko - angielski
$tabela = 'tabele';

// kolumny tabeli bazy danych
$angielski = 'angielskie';
$polski = 'polskie';
$przyklad = 'zdanie';

if(!isset($_GET['act'])){
       	$_GET['act'] = 'nauka';     
}

switch($_GET['act']){
	  case 'sprawdz':
			try{
                  $pdo = new PDO('mysql:host=localhost;dbname=zestaw', 'root', '');
                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $id = $_GET['id'];
	          $stmtpk = $pdo -> query("SELECT * FROM $tabela WHERE id = '$id'"); // wyciagniecie calego losowego wiersza

		  $stmtpk->execute();
		  $tablica = $stmtpk->fetch();

		  $angielskie = $tablica['angielskie'];
		  $polskie = $tablica['polskie'];
		  $zdanie = $tablica['zdanie'];

		  if(isset($_POST['wprowadzone'])){

			if($_POST['wprowadzone'] == $angielskie){
				sprawdzenie($polskie, $angielskie, $zdanie);
				wyslij(); edytuj($id); usun($id); echo "<table>Dobrze! </table>";
			}

			if($_POST['wprowadzone'] !== $angielskie) {
				sprawdzenie($polskie, $angielskie, $zdanie);
				$wprowadzone = $_POST['wprowadzone'];
				wyslij();  edytuj($id); echo "<table>Zle! wprowadziłeś: $wprowadzone</table>";
			}
		  } 

		  $stmtpk->closeCursor();
		}
            	  catch(PDOException $e){
            	      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
            	  }  
/********************************************************************/
	  default:
    		try{
                  $pdo = new PDO('mysql:host=localhost;dbname=zestaw', 'root', '');
                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	          $stmtp = $pdo -> query("SELECT * FROM $tabela ORDER BY rand() LIMIT 1"); // wyciagniecie calego losowego wiersza
			      // nauka.php?act=sprawdz  

		  $stmtp->execute();
		  $tablica = $stmtp->fetch();

		  $id = $tablica['id'];
		  $polskie = $tablica['polskie'];
		  $angielskie = $tablica['angielskie'];

		  if(!isset($_POST['wprowadzone'])){
			formularz($polskie, $id);
		  }

echo '</br><a href="../index.php">Start</a> ';
echo '<a href="tryb_edycji.php">Tabele</a> ';
echo '<a href="nauka.php">Nauka</a> ';
echo '<a href="logi.php">Logi</a> ';

		  $stmtp->closeCursor();
		}
            	  catch(PDOException $e){
            	      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
            	  }  
	  break;        
}

function formularz($polskie, $id){

      echo '<table border="0"><form method="post" action="nauka.php?act=sprawdz&id='.$id.'">
        <tr>
            <td>Wyrażenie polskie:</td>
            <td><input type="text" style="width: 250px" value="'.$polskie.'"></td>
        </tr>
        <tr>
            <td>Wrażenie hiszpańskie:</td>
            <td><input type="text" onLoad="setFocusToTextBox();" id="znacznik" name="wprowadzone" style="width: 250px"></td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" value="Wyślij">
        <input type="reset" value="Wyczyść formularz"></td></form>
        </tr>
    	</table>';
  }

function sprawdzenie($polskie, $angielskie, $zdanie){
      echo '<table border="0">
        <tr>
            <td>Wyrażenie polskie:</td>
            <td><input type="text" style="width: 250px" value="'.$polskie.'"></td>
        </tr>
        <tr>
            <td>Wrażenie hiszpańskie:</td>
            <td><input type="text" style="width: 250px"  value="'.$angielskie.'"></td>
        </tr>
	<tr>
            <td>Zdanie</td>
            <td><input type="text" style="width: 250px"  value="'.$zdanie.'"></td>
        </tr>
    	</table>';
  }

function wyslij(){
echo '<form method="post" action="nauka.php">
      <input type="submit" name="submit" value="Dalej">
        </form>';
  }

function usun($id){
echo '<form method="post" action="tryb_edycji.php?act=usun&id='.$id.'">
      <input type="submit" name="submit" value="Usuń">
        </form>';
  }
function edytuj($id){
echo '<form method="post" action="nauka.php?act=edytuj&id='.$id.'">
      <input type="submit" name="submit" value="Edytuj">
        </form>';
  }

?>

