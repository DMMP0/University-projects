<?php
 //transazioni dell'anno, settato prima con $year
 //$year= date("Y");
$query1 =   "SELECT
                id,
                tipo,
                amount, 
                macroarea,
				anno,
				ripetizioni_annuali
				
				FROM TRANSAZIONI 
			WHERE ID_FLOW=?";
$query2 =   "SELECT
                id,
                tipo,
                amount, 
                macroarea,
				anno,
				ripetizioni_annuali
				
				FROM TRANSAZIONI
			WHERE ID_FLOW=?";
			
//serve a contare gli anni
$query3 =   "SELECT DISTINCT anno AS a
			FROM TRANSAZIONI
			WHERE ID_FLOW=?
			ORDER BY anno ASC";
			
			
$TP=new TPrecisa($db);
$stmt2 = $TP->conn->prepare( $query2 );
$stmt2->bindParam(1, $_SESSION['current_flow_id'], PDO::PARAM_INT);
//var_dump($stmt);
$stmt2->execute();
    
$TB=new TBoh($db);
$stmt1 = $TB->conn->prepare( $query1 );
$stmt1->bindParam(1, $_SESSION['current_flow_id'], PDO::PARAM_INT);
//var_dump($stmt);
$stmt1->execute();

//anni diversi
$stmt3 = $TB->conn->prepare( $query3 );
$stmt3->bindParam(1, $_SESSION['current_flow_id'], PDO::PARAM_INT);
//var_dump($stmt);
$stmt3->execute();
$yearAmount=[];
$dataPoints1=[];
while ($row = $stmt3->fetch(PDO::FETCH_ASSOC))
		{
			//var_dump($row);
			extract($row);
			$yearAmount=array_push_assoc($yearAmount, $a, 0);
		}

//var_dump($yearAmount);

//calcolo in ciascun mese dai dati del flow
while ($row = $stmt1->fetch(PDO::FETCH_ASSOC))
		{
			//var_dump($row);
			extract($row);
			if($macroarea=="1")//impieghi
			{
				$yearAmount[$anno] -= $amount;
				
			}
			else //fonti
			{
				$yearAmount[$anno] += $amount;
			}
			
			/*var_dump($anno);
			var_dump($yearAmount[$anno]);*/
		}
		
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC))
		{
			extract($row);
			//$mese=(int)(str_replace("-", "",substr($t_date,5,2)));
			/*$d = date_parse_from_format("Y-m-d", $t_date);
			$mese=(int)($d["month"]);*/
			if($macroarea=="1")//impieghi
			{
				$yearAmount[$anno] -= $amount;
				
			}
			else //fonti
			{
				$yearAmount[$anno] += $amount;
			}
			//var_dump($yearAmount[$anno]);
		}
		

foreach ($yearAmount as $key => &$val) 
{
    array_push($dataPoints1,array("y" => $val,"label" => $key ) );
}

unset($val); // break the reference with the last element
$_SESSION['data1']=$dataPoints1;

//var_dump($dataPoints1);
 
?>


