
<script>document.title = 'Flow Display';</script>
<?php
$page_title='Flow Display';
echo "<div class='col-md-6'>	<script src=\"https://canvasjs.com/assets/script/canvasjs.min.js\"></script>";
echo 	"<div id=\"chartContainer1\" style=\"height: 370px; width: 100%;\"></div>";
echo "</div>";
echo "<div class='col-md-6'>";
echo 	"<div id=\"chartContainer2\" style=\"height: 370px; width: 100%;\"></div>";
echo "</div>";			
?>

 <?php
 if(isset($_SESSION['current_flow_id'])&&$_SESSION['current_flow_id']!=0)
		{
			if($action=='refresh2')
				echo "<script> location.replace(\"index.php\"); </script>";

			include_once "$_SERVER[DOCUMENT_ROOT]/project/config/core.php";
			include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
			include_once "$_SERVER[DOCUMENT_ROOT]/project/config/database.php";
			include_once "$_SERVER[DOCUMENT_ROOT]/project/objects/transaction.php";



			$database = new Database();
			$db = $database->getConnection();
			$t = new TPrecisa($db);
			/*$q2="delete from transazioni where amount=0";
                $stmt1 = $t->conn->prepare($q2);
                $stmt1->execute();*/

			include_once "objects/transaction.php";
			include_once "grafico1.php";
			include_once "grafico2.php";
			
			include_once 'readTransactions.php';
				
				//header("Refresh:0; url=index.php");*/
		}
		?>
	


