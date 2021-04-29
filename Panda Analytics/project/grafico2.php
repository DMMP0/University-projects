<?php
 //transazioni dell'anno, settato prima con $year
 $year= date("Y");
 $mese=0;
$query1 =   "SELECT
                t.id,
                t.tipo,
                t.amount, 
                t.macroarea,
				t.anno,
				t.ripetizioni_annuali,
				tb.t_mese
				FROM (TRANSAZIONI AS T INNER JOIN FLOW AS f ON f.id = t.ID_FLOW) INNER JOIN TBOH AS tb ON tb.ID_TRANSAZIONE = t.id
			WHERE t.ID_FLOW=? AND t.anno=?";
$query2 =   "SELECT
                t.id,
                t.tipo,
                t.amount, 
                t.macroarea,
				t.anno,
				t.ripetizioni_annuali,
				tb.t_date
				FROM (TRANSAZIONI AS T INNER JOIN FLOW AS f ON f.id = t.ID_FLOW) INNER JOIN TPRECISA AS tb ON tb.ID_TRANSAZIONE = t.id
			WHERE t.ID_FLOW=? AND t.anno=?";
			
$TP=new TPrecisa($db);
$stmt2 = $TP->conn->prepare( $query2 );
$stmt2->bindParam(1, $_SESSION['current_flow_id'], PDO::PARAM_INT);
$stmt2->bindParam(2, $year, PDO::PARAM_INT);
//var_dump($stmt);
$stmt2->execute();
    

$stmt1 = $TP->conn->prepare( $query1 );
$stmt1->bindParam(1, $_SESSION['current_flow_id'], PDO::PARAM_INT);
$stmt1->bindParam(2, $year, PDO::PARAM_INT);
//var_dump($stmt);
$stmt1->execute();

$yearAmount=array(0,0,0,0,0,0,0,0,0,0,0,0);


//calcolo in ciascun mese dai dati del flow
while ($row = $stmt1->fetch(PDO::FETCH_ASSOC))
{
	//var_dump($row);
	extract($row);
	$mese=(int)($t_mese);
	//var_dump($mese);
	if($macroarea==="0")//fonti
	{
		$yearAmount[$mese-1] += $amount;		
	}
	else
	{
		$yearAmount[$mese-1] -= $amount;
	}
}
		
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC))
{
	extract($row);
	#var_dump($t_date);
	$d = date_parse_from_format("Y-m-d", $t_date);
	$mese=(int)($d["month"]);
	#var_dump($mese);
	if($macroarea==="0")//fonti
	{
		$yearAmount[$mese-1] += $amount;
	}
	else
	{
				
		$yearAmount[$mese-1] -= $amount;
	}
}
			
for($i=1;$i<13;$i++)
{
	if($i-2 >= 0)
		$yearAmount[$i-1] += $yearAmount[$i-2];
}


//	var_dump($yearAmount);
$dataPoints2 = array( 
	array("y" => $yearAmount[0],"label" => "Gennaio" ),
	array("y" => $yearAmount[1],"label" => "Febbraio" ),
	array("y" => $yearAmount[2],"label" => "Marzo" ),
	array("y" => $yearAmount[3],"label" => "Aprile" ),
	array("y" => $yearAmount[4],"label" => "Maggio" ),
	array("y" => $yearAmount[5],"label" => "Giugno" ),
	array("y" => $yearAmount[6],"label" => "Luglio" ),
	array("y" => $yearAmount[7],"label" => "Agosto" ),
	array("y" => $yearAmount[8],"label" => "Settembre" ),
	array("y" => $yearAmount[9],"label" => "Ottobre" ),
	array("y" => $yearAmount[10],"label" => "Novembre" ),
	array("y" => $yearAmount[11],"label" => "Dicembre" )
);

$_SESSION['data2']=$dataPoints2;
 
 





