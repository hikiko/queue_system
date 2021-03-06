<?php
session_start();
include "defines.php";
include "serial_functions.php";

queue_init();
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="style.css">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script type="text/javascript" src="jquery.js"></script>
  
<?php
include "jsrefresh.php";
?>
</head>

<body>
    

<?php
if(isset($_POST['submit'])) {
  $sel_radio = $_POST['selections'];
  if(isset($sel_radio)) {
    if($sel_radio == 'clearall') {
      queue_reset();

      $con = mysqli_connect(HOST, USER, PASS, DB, 9999) or die(mysqli_connect_error());
      $query = "delete from queue";
      $res = mysqli_query($con, $query) or die(mysqli_error());
    }
  }
}
?>

  <div id="infotitle">
    <h3>Γενικές Πληροφορίες:</h3>
  </div>

  <div id="info" name="info">
  </div>

  <br/>

  <div id="actionstitle" name="actionstitle">
    <h3>Επιλογές:</h3>
  </div>

  <div id="actions" name="actions">
    <form action="" name="selections" id="selections" method="POST">
      <input type="radio" class="radio" name="selections" value="showall" /> Εμφάνιση όλων των
        των ηλεκτρονικών εισιτηρίων <br/>
      <input type="radio" name="selections" class="radio" value="clearall" /> Επανεκκίνηση μηχανήματος / Καθαρισμός εγγραφών <br/>

      <input type="hidden" name="page" id="page" value=0 />
      <input type="submit" name="btnsubmit" id="btnsubmit" value="Καταχώρηση"/>
      <br></br>
      <br></br>
      <input type="button" id="btnPrevious" class="buttons" onclick="previous();" value="<">
      <input type="button" id="btnNext" class="buttons" onclick="next();" value=">">
      
    </form>
  </div>
  <script type="text/javascript">
      
   $('#btnPrevious').hide();
   $('#btnNext').hide();
   function next()//when you press the next button (submits form)
   {
       document.getElementById('selections').reset();
       document.getElementById('page').value = 1;
       $('input[name="selections"][value="showall"]').prop('checked', true);
       document.getElementById('selections').submit();     
   };
   function previous()//when you press the previous button (submits form)
   {
       document.getElementById('selections').reset();
       document.getElementById('page').value = -1;
       $('input[name="selections"][value="showall"]').prop('checked', true);
       document.getElementById('selections').submit();
   };
  </script>
  

  <div id="dbentries" name="dbentries">
<?php
function getFromDB($page){
      	$con = mysqli_connect(HOST, USER, PASS, DB, 9999) or die(mysqli_connect_error());
      	$numPage=$page;
      	$offset=$numPage * 5;
        $countQuery= "select * from queue";
        $resultC= mysqli_query($con,$countQuery) or die(mysqli_error());
        $count=$resultC->num_rows;
        if ($offset>= $count)
        {
            $numPage=0;
            $offset=$numPage* 5;
        }
        if ($offset< 0)
        {
            $numPage= $count/ 5- 1 ;
            $offset=$numPage* 5;
            
        }
      	$query = "select * from queue LIMIT 5 OFFSET $offset";
        
      	$res = mysqli_query($con, $query) or die(mysqli_error());
      	if($res->num_rows > 0) {
        		echo "<table><tbody><tr><th>ID</th><th>AMKA</th><th>Σειρά</th><th>Ημερομηνία</th></tr>";
        		while($row = $res->fetch_array(MYSQLI_ASSOC)) {
          		$id = $row['id'];
          		$amka = $row['amka'];
          		$num = $row['num'];
          		$date = $row['date'];
          		echo "<tr>";
          		echo "<td>$id</td>";
          		echo "<td>$amka</td>";
          		echo "<td>$num</td>";
          		echo "<td>$date</td>";
          		echo "</tr>";
        			}
      		}
    		}
    		
  $sel_radio = $_POST['selections'];
  if(isset($sel_radio)) {
    if($sel_radio == 'showall') {
    	if($_POST['page'] == 0) {
            $page= 0;
            $_SESSION["previous"]= $page;
            $_SESSION["showBtn"]=1;
            getFromDB($page);
    }
    	elseif($_POST['page'] == 1) {
            $page= $_SESSION["previous"] +1;
            $_SESSION["previous"]=$page;
            getFromDB($page);
    }
        elseif ($_POST['page'] == -1) {
            $page= $_SESSION["previous"]-1;
            $_SESSION["previous"]=$page;
            getFromDB($page);
    
}
    	
    }
  }
  if ($_SESSION["showBtn"]==1)
  {
  echo "<script type=\"text/javascript\"> 
      $('#btnNext').show();
      $('#btnPrevious').show();
       </script>";
  }
  


?>
     </tbody>
     </table>
  </div>
  <div id="about" name="about" value="about">
    <a href="about.html">Πληροφορίες</a>
  </div>
</body>
