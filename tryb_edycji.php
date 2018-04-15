
<?php
echo '<a href="../index.php">Start</a> ';
echo '<a href="tryb_edycji.php">Tabele</a> ';
echo '<a href="nauka.php">Nauka</a> ';
echo '<a href="logi.php">Logi</a> ';
echo '<h1>Tryb Edycji</h1>';
include_once "connect.php";

// paramatry poczatkowe

// typ pierwszy angielsko - poslki i polsko - angielski
$tabela = 'tabele';

// kolumny tabeli bazy danych
$angielski = 'angielskie';
$polski = 'polskie';
$przyklad = 'zdanie';

function filtruj($zmienna) {
    if (get_magic_quotes_gpc())
        $zmienna = stripslashes($zmienna); // usuwamy slashe

// usuwamy spacje, tagi html oraz niebezpieczne znaki
    return htmlspecialchars(trim($zmienna));
}

// poruszanie sie po stronie
 if(!isset($_GET['act']))   {
     
     $_GET['act'] = 'tryb_edycji';
     
     }

// zarzadzanie baza danych
 switch($_GET['act']){

      case 'wyswietl':
        echo 'wświetla dane';
      break;
      
      case 'dodaj':      
  
        $flaga = 1;

        if ($flaga == 1 && empty($_POST['dodaj_element1']) || empty($_POST['dodaj_element2'])){
	    $flaga = 0;
            exit(header("Location: tryb_edycji.php"));
        }

        if ($flaga == 1){
            if ($mysqli->connect_error != 0) {
		    $flaga = 0;
                    echo "Error:" . $mysqli->connect_errno;
            }
        } 

       if ($flaga == 1){

                $dodaj_element1 = filtruj($_POST['dodaj_element1']);
                $dodaj_element2 = filtruj($_POST['dodaj_element2']);
                $dodaj_element3 = filtruj($_POST['dodaj_element3']);

                    if($flaga == 1 && $_SERVER['REQUEST_METHOD'] == 'POST'){
                        
                             if ($flaga == 1 && $_POST['dodaj_element1'] == $dodaj_element1  && $_POST['dodaj_element2'] == $dodaj_element2 && $_POST['dodaj_element3'] == $dodaj_element3) {
                                
                                       $sql = "INSERT INTO $tabela ($polski, $angielski, $przyklad ) VALUES ('$dodaj_element1', '$dodaj_element2', '$dodaj_element3')";
                                       $mysqli->query($sql);

                                       $flaga = 0;
				       $mysqli->close();
                                       exit(header("Location: tryb_edycji.php"));

                     	   } 
		     }

                     if($flaga == 1){
			$flaga = 0;
                        echo "<div align=\"center\">Wystąpił błąd</div>";
			echo $dodaj_element1, $dodaj_element2, $dodaj_element3;
                        echo '<p></br><a href="tryb_edycji.php">Spróbuj jeszcze raz!</a></p>';
                     }
                        

                    if($flaga == 1){
		        $flaga = 0;
                        exit(header("Location: tryb_edycji.php"));
                    }
 	}     
        
      break;
      
      case 'edytuj':
         $id = $_GET['id'];
         $id = filtruj($id);

	 $flaga = 1;

         if ($flaga == 1 && empty($id)){
	     $flaga = 0;
             echo"Wystąpił następujący błąd: Nieprawidłowy numer 'id'";
         }
              
         if ($flaga == 1 && empty($_POST['a'.$id.'']) || empty($_POST['b'.$id.''])){
	     $flaga = 0;
             header("Location: tryb_edycji.php");
         }

         if ($flaga == 1) {
              $edytuj_element1 = filtruj($_POST['a'.$id.'']);
              $edytuj_element2 = filtruj($_POST['b'.$id.'']);
              $edytuj_element3 = filtruj($_POST['c'.$id.'']);
              
              if ($_POST['a'.$id.''] == $edytuj_element1  && $_POST['b'.$id.''] == $edytuj_element2 && $_POST['c'.$id.''] == $edytuj_element3) {
                   try
                   {
                      $pdo = new PDO('mysql:host=localhost;dbname=zestaw', 'root', '');
                      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                          $liczba = $pdo ->exec("UPDATE $tabela SET polskie = '$edytuj_element1' , angielskie = '$edytuj_element2', zdanie = '$edytuj_element3' WHERE id = '$id' ");
                          header("Location: tryb_edycji.php");
                      
                   }
                   catch(PDOException $e)
                   {
                      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
                      echo '</br><a href="tryb_edycji.php">wróć</a>';
                   }
              }
              else {
                  echo "</br>Wystąpił błąd";
              }
             
          }
   
      break;
   
      case 'usun':
          
         $id = $_GET['id'];
         $id = filtruj($id);

	 $flaga = 1;

         if ($flaga == 1 && empty($id)){
	     $flaga = 0;
             echo "Nie udało się usunąć wiersza tabeli";
         }
         
	 if ($flaga == 1) {
              	   try
                   {
                      $pdo = new PDO('mysql:host=localhost;dbname=zestaw', 'root', '');
                      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                          $liczba = $pdo ->exec("DELETE FROM $tabela WHERE id = '$id' ") or die('Błąd zapytania');
                          header("Location: tryb_edycji.php");
                      
                   }
                   catch(PDOException $e)
                   {
                      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
                      echo '</br><a href="tryb_edycji.php">wróć</a>';
                   }

  	}
      break;
     
      default:  
       
             formularz();
             try
               {
                  $pdo = new PDO('mysql:host=localhost;dbname=zestaw', 'root', '');
                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                  $stmt = $pdo -> query("SELECT * FROM $tabela");

                  echo '<table border="1" width="750px" >';
                  echo '<ul>';

                  while($row = $stmt->fetch())
                  {
                      $id = $row['id'];
                   
                      echo '<form method="post" action="tryb_edycji.php?act=edytuj&id='.$row['id'].'"><tr>
                            <td width="150px"><input name="a'.$id.'" placeholder="Wyrażenie polskie:" value="'.$row['polskie'].'"</td>'.
			   '<td width="150px"><input name="b'.$id.'" placeholder="Wrażenie angielskie:"  value="'.$row['angielskie'].'"</td>'.
		           '<td width="150px"><input name="c'.$id.'" placeholder="Przykładowe zdanie" value="'.$row['zdanie'].'" </td>'.
                           '<td width="50px"><input type="submit" name="submit" value="Edytuj!"></td></form>'.
			   '<form method="post" action="tryb_edycji.php?act=usun&id='.$row['id'].'">
		            <td width="50px"><input type="submit" name="submit" value="Usuń!"></td></tr></form>';

                  }

                  $stmt->closeCursor();
                  echo '</ul>';
                  echo '</ table>';

               }
               catch(PDOException $e)
               {
                  echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
               }
      }
      
  function formularz(){
      echo '<table border="0"><form method="post" action="tryb_edycji.php?act=dodaj">
        <tr>
            <td>Wyrażenie polskie:</td>
            <td><input type="text" name="dodaj_element1" style="width: 250px"></td>
        </tr>
        <tr>
            <td>Wrażenie hiszpańskie:</td>
            <td><input type="text" name="dodaj_element2" style="width: 250px"></td>
        </tr>
        <tr>
            <td>Przykładowe zdanie:</td>
            <td><input type="text" name="dodaj_element3" style="width: 250px"></td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" value="Wyślij">&nbsp;
        <input type="reset" value="Wyczyść formularz"></td></form>
        </tr>
    </table>';
  }
  
?>
<style type="text/css">
    body {
        background: black ;
    }
</style>