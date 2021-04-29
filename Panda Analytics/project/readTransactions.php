<?php
$t = new TPrecisa($db);


$fid=$_SESSION['current_flow_id'];

$y=date('Y'); // anno corrente
$m=(int)(date('m')); // mese corrente


echo "<div class='transactions'>";
	$q="select * from transazioni as t inner join tboh as tp on tp.id_transazione=t.id where tp.t_mese >= :m AND tp.t_mese <= :md AND t.id_flow=:fid";
	$stmt1 = $t->conn->prepare($q);
	$stmt1->bindParam(':m', $m);
	$md=$m+3;
	$stmt1->bindParam(':md', $md);
	$stmt1->bindParam(':fid', $fid);
	$stmt1->execute();
	$num1 = $stmt1->rowCount();
	
	$q="select * from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id WHERE tp.t_date>=':cd' AND tp.t_date<=':nd' AND t.id_flow=$fid";
	$stmt2 = $t->conn->prepare($q);
	//$stmt2->bindParam(':fid', $fid);
	$cd=date('Y-m-d');
	$stmt2->bindParam(':cd',$cd );
	$nd = new DateTime('now');
	$nd->modify('+3 month');
	$nd = $nd->format('Y-m-d');
	$stmt2->bindParam(':nd', $nd);
	$stmt2->execute();
	$num2 = $stmt2->rowCount();
	//var_dump($num1);
//	var_dump($num2);
	include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
	
	
	

/*while ($row = $stmt2->fetch(PDO::FETCH_ASSOC))
			{
				
				var_dump($row);
			}
while ($row = $stmt1->fetch(PDO::FETCH_ASSOC))
			{
				
				var_dump($row);
			}*/

//control query
	$q="select SUM(amount) as sicum from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id WHERE t.id_flow=$fid";
	$stmt2 = $t->conn->prepare($q);
	$stmt2->execute();
	$row = $stmt2->fetch(PDO::FETCH_ASSOC);
	#var_dump($row);	
	extract($row);
	$q="select SUM(amount) as masarai from transazioni as t inner join tboh as tp on tp.id_transazione=t.id WHERE t.id_flow=$fid";
	$stmt2 = $t->conn->prepare($q);
	$stmt2->execute();
	$row = $stmt2->fetch(PDO::FETCH_ASSOC);
	#var_dump($row);	
	extract($row);
	
	#var_dump($num2);
	#var_dump($num1);	
if(($num1>0 || $num2 > 0)&&(( $sicum!=null && $sicum>0 )|| ($masarai>0 && $masarai!=null)))
	{
		echo "<table class='table table-hover table-responsive table-bordered'>";
 
		// table headers
		echo "<tr>";
        echo "<th>Tipo</th>";
        echo "<th>Macroarea</th>";
		echo "<th>Ammontare</th>";
		echo "<th>Anno</th>";
		echo "<th>Mese/Data precisa</th>";		
		echo "</tr>";
		while ($row = $stmt2->fetch(PDO::FETCH_ASSOC))
			{
				
				//var_dump($row);
				extract($row);
				if($AMOUNT!=0)
				{
					$d = date_parse_from_format("Y-m-d", $T_DATE);
					echo "<tr>";			
					echo "<td>".fromCodeToString($TIPO)."</td>";
					if($MACROAREA)
						echo "<td>Impieghi</td>";
					else
						echo "<td>Fonti</td>";
					echo "<td>{$AMOUNT}</td>";
					echo "<td>{$ANNO}</td>";			
					echo "<td>{$d}</td>";
					echo "</tr>";
				}
				
				
			}
		while ($row = $stmt1->fetch(PDO::FETCH_ASSOC))
			{
				//var_dump($row);
				extract($row);
				if($AMOUNT!=0)
				{
					$mese=(int)$T_MESE;
					$mese = month($mese);
					echo "<tr>";		
					echo "<td>".fromCodeToString($TIPO)."</td>";
					if($MACROAREA)
						echo "<td>Impieghi</td>";
					else
						echo "<td>Fonti</td>";
					echo "<td>{$AMOUNT}</td>";
					echo "<td>{$ANNO}</td>";
					echo "<td>{$mese}</td>";
					echo "</tr>";
				}
			
			}
		echo "</table>";
		
	}
else
	{
		echo "<div class='alert alert-danger'>
        <strong>Nessuna transazione imminente</strong>
		</div>";
	}


echo "</div>";

