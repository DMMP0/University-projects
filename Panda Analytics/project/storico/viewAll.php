<?php


include_once "$_SERVER[DOCUMENT_ROOT]/project/config/core.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/objects/transaction.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/database.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
$database = new Database();
$db = $database->getConnection();


$t = new TPrecisa($db);
$fid=$_SESSION['current_flow_id'];
$y=array();

$page_title="Storico";


$q="select distinct anno from transazioni as t where id_flow=:fid";
$stmt = $t->conn->prepare($q);
$stmt->bindParam(':fid', $fid);
$stmt->execute();
$num = $stmt->rowCount();
while(	$row = $stmt->fetch(PDO::FETCH_ASSOC)	)
{
	extract($row);
	$y[]=$anno; //sembra che sia più veloce di array_push()
}



$require_login=true;
include_once "$_SERVER[DOCUMENT_ROOT]/project/login_checker.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/layout_head.php";

$row1=null;
$row2=null;

foreach($y as $yy)
{

	//impieghi
	$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 300 AND 399";
	$stmt1 = $t->conn->prepare($q);
	$stmt1->bindParam(':fid', $fid);
	$stmt1->bindParam(':y', $yy);
	$stmt1->execute();
	$num1 = $stmt1->rowCount();
				
	$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND  tipo BETWEEN 400 AND 499";
	$stmt2 = $t->conn->prepare($q);
	$stmt2->bindParam(':fid', $fid);
	$stmt2->bindParam(':y', $yy);
	$stmt2->execute();
	$num2 = $stmt2->rowCount();
				
	//fonti
	$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y  AND tipo BETWEEN 100 AND 199";
	$stmt3 = $t->conn->prepare($q);
	$stmt3->bindParam(':fid', $fid);
	$stmt3->bindParam(':y', $yy);
	$stmt3->execute();
	$num3 = $stmt3->rowCount();
				
	$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 200 AND 299";
	$stmt4 = $t->conn->prepare($q);
	$stmt4->bindParam(':fid', $fid);
	$stmt4->bindParam(':y', $yy);
	$stmt4->execute();
	$num4 = $stmt4->rowCount();



	$q="select SUM(amount) as somma from transazioni where id_flow=:fid AND anno=:y AND macroarea=0";
	$stmt5 = $t->conn->prepare($q);
	$stmt5->bindParam(':fid', $fid);
	$stmt5->bindParam(':y', $yy);
	$stmt5->execute();
	while($row = $stmt5->fetch(PDO::FETCH_ASSOC))
	{
		extract($row);
		$sF=$somma ;
	}

	$q="select SUM(amount) as somma from transazioni where id_flow=:fid AND anno=:y AND macroarea=1";
	$stmt6 = $t->conn->prepare($q);
	$stmt6->bindParam(':fid', $fid);
	$stmt6->bindParam(':y', $yy);
	$stmt6->execute();
	while($row = $stmt6->fetch(PDO::FETCH_ASSOC))
	{
		extract($row);
		$sI=$somma;
	}

	include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";

	echo "<div class='col-md-12'>";

	echo "<div class='titolo-tabella'>
			<h2>$yy</h2>
		</div>";
			

	if($num1==0&&$num4==0&&$num3==0&&$num2==0)
	{
		echo "Nessun dato presente";
	}
	else
	{
		echo "<div class='col-md-6'>";
		echo	"<div class=\"table-responsive\">
					<table class=\"table table-bordered table-striped table-highlight\">
						<thead>
							<th>Impieghi</th>
							<th>Cifra</th>
						</thead>
						<tbody>";
		if($num3>0)
		{
			echo "<tr>";
					
			echo   " <td>Attivo corrente</td>";

			echo "</tr>";
			while (	$row1 = $stmt3->fetch(PDO::FETCH_ASSOC))
				{
					
						echo "<tr>";
						extract($row1);
					
						echo   " <td>". fromCodeToString($tipo)."</td>
							<td>{$amount}</td>";				
						echo "</tr>";
					
				}
		}

		if($num4>0)
		{
			echo "<tr>";
					
			echo   " <td>Attivo Immobilizzato</td>";

			echo "</tr>";
			while (	$row1 = $stmt4->fetch(PDO::FETCH_ASSOC))
			{
							
					
				echo "<tr>";
				extract($row1);
					
				echo   " <td>". fromCodeToString($tipo)."</td>
							<td>{$amount}</td>";				
				echo "</tr>";
					
			}
		}

		echo "<tr>";
					
		echo   " <td>Totale Impieghi</td>";
		echo   " <td>$sI</td>";

		echo "</tr>
		</tbody>
		</table>
		</div>
		</div>";



		echo "<div class='col-md-6'>";
		echo	"<div class=\"table-responsive\">
					<table class=\"table table-bordered table-striped table-highlight\">
						<thead>
							<th>Fonti</th>
							<th>Cifra</th>
						</thead>
						<tbody>";
		if($num1>0)
		{
			echo "<tr>";
					
			echo   " <td>Capitale di debito</td>";

			echo "</tr>";
			while (	$row1 = $stmt1->fetch(PDO::FETCH_ASSOC))
			{
					
				echo "<tr>";
				extract($row1);
					
				echo   " <td>". fromCodeToString($tipo)."</td>
						<td>{$amount}</td>";				
				echo "</tr>";
					
			}
		}

		if($num2>0)
		{
			echo "<tr>";
					
			echo   " <td>Capitale proprio</td>";

			echo "</tr>";
			while (	$row1 = $stmt2->fetch(PDO::FETCH_ASSOC))
			{
							
					
				echo "<tr>";
				extract($row1);
					
				echo   " <td>". fromCodeToString($tipo)."</td>
						<td>{$amount}</td>";				
				echo "</tr>";
					
			}
		}

		echo "<tr>";
					
		echo   " <td>Totale Fonti</td>";
		echo   " <td>$sF</td>";

		echo "</tr>
		</tbody>
		</table>
		</div>
		</div>";

		echo "<button class=\"normal-button\" onclick=\"window.location.href='{$home_url}/storico/mod.php?y={$yy}'\"> Modifica</button></div>";	

	}
	
}

unset($y);
 
// footer HTML and JavaScript codes
include "$_SERVER[DOCUMENT_ROOT]/project/layout_foot.php";
?>