<script src='https://kit.fontawesome.com/a076d05399.js'></script>

<?php 
include_once "objects/flow.php";
include_once "objects/user.php";
include_once "config/database.php";
include_once "libs/css/user.css";
 

$database = new Database();
$db = $database->getConnection();
$flow = new Flow($db);
$user = new User($db);
$stmt = $flow->getFromUsername($_SESSION['username']);
$uf = $user->countAllFlows($_SESSION['username']);
if($uf%6==0)
	$div=2;
else if($uf%4==0)
	$div=3;
else if($uf%3==0)
	$div=4;
else if($uf%2==0)
	$div=6;
else if($uf==1)
	$div=12;
else
	$div=1;

echo "<div class='col-md-12'>";
$i=0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$i++;
		//var_dump($stmt);
		//var_dump($row);
		extract($row);
			
		echo "<div class='col-md-$div'>";

			echo "<div class=\"container-flow-display\">";
				echo "<div class=\"container-flow-display-first-line\">";
				//echo "<a href='{$home_url}index.php?action=refresh&flow=$id'>Azienda $1</a>";
				echo /* "<div>*/" <button onclick=\"window.location.href='{$home_url}index.php?flow={$id}&action=refresh1'\">Azienda $i</button>";
				echo "<button onclick=\"window.location.href='{$home_url}index.php?flow={$id}&action=delete'\"> <i class='fas fa-trash-alt'></i></button></div>";
			//	echo "</div>";
				echo "<div class=\"container-flow-display-details\">";
				echo "<p>$nome</p>";
				echo "<p>$fondazione</p>";
			//	echo "<p></p>";
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
		//echo "<a href='{$home_url}flow.php?flow={$id}&action=refresh' >{$nome} </a>";	
		//var_dump($_SESSION['username']);
				
	}



echo "</div>";


echo "<div class=\"container-new-flow\">";
echo "<button onclick=\"window.location.href='{$home_url}index.php?action=create_flow'\">Crea azienda </button>";	
echo "</div>";
echo "</div>";
//var_dump($_SESSION);
			
 


?>

