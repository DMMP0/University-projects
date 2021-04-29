<?php
//controllare da mod.php
//storico anno corrente

include_once "$_SERVER[DOCUMENT_ROOT]/project/config/core.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/objects/transaction.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/database.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
$database = new Database();
$db = $database->getConnection();


$t = new TPrecisa($db);
$fid=$_SESSION['current_flow_id'];
$y=date("Y"); // anno corrente

$page_title="Storico anno corrente";


$require_login=true;
include_once "$_SERVER[DOCUMENT_ROOT]/project/login_checker.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/layout_head.php";


//fonti
$q= "select t.id,t.tipo,t.amount,tp.t_date from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 100 AND 199 ORDER BY t_date ";
$stmt1 = $t->conn->prepare($q);
$stmt1->bindParam(':fid', $fid);
$stmt1->bindParam(':y', $y);
$stmt1->execute();
$q= "select t.id,t.tipo,t.amount,tp.t_mese from transazioni as t inner join tboh as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 100 AND 199 ORDER BY t_mese ";
$stmt15 = $t->conn->prepare($q);
$stmt15->bindParam(':fid', $fid);
$stmt15->bindParam(':y', $y);
$stmt15->execute();
//$num1 = $stmt1->rowCount();

$q= "select t.id,t.tipo,t.amount,tp.t_date from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 200 AND 299 ORDER BY t_date ";
$stmt2 = $t->conn->prepare($q);
$stmt2->bindParam(':fid', $fid);
$stmt2->bindParam(':y', $y);
$stmt2->execute();
$q= "select t.id,t.tipo,t.amount,tp.t_mese from transazioni as t inner join tboh as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 200 AND 299 ORDER BY t_mese ";
$stmt25 = $t->conn->prepare($q);
$stmt25->bindParam(':fid', $fid);
$stmt25->bindParam(':y', $y);
$stmt25->execute();
//$num2 = $stmt2->rowCount();

//impieghi
$q= "select t.id,t.tipo,t.amount,tp.t_date from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 300 AND 399 ORDER BY t_date ";
$stmt3 = $t->conn->prepare($q);
$stmt3->bindParam(':fid', $fid);
$stmt3->bindParam(':y', $y);
$stmt3->execute();
$q= "select t.id,t.tipo,t.amount,tp.t_mese from transazioni as t inner join tboh as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 300 AND 399 ORDER BY t_mese ";
$stmt35 = $t->conn->prepare($q);
$stmt35->bindParam(':fid', $fid);
$stmt35->bindParam(':y', $y);
$stmt35->execute();
//$num3 = $stmt3->rowCount();

$q= "select t.id,t.tipo,t.amount,tp.t_date from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 400 AND 499 ORDER BY t_date ";
$stmt4 = $t->conn->prepare($q);
$stmt4->bindParam(':fid', $fid);
$stmt4->bindParam(':y', $y);
$stmt4->execute();
$q= "select t.id,t.tipo,t.amount,tp.t_mese from transazioni as t inner join tboh as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND tipo BETWEEN 400 AND 499 ORDER BY t_mese ";
$stmt45 = $t->conn->prepare($q);
$stmt45->bindParam(':fid', $fid);
$stmt45->bindParam(':y', $y);
$stmt45->execute();
//$num4 = $stmt4->rowCount();


include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
echo "<div class='titolo-tabella'>
		Anno corrente
		</div>";
echo "<div class='col-md-12'>";
echo "<div class='col-md-6'>";
echo	"<div class=\"table-responsive\">
				<table class=\"table table-bordered table-striped table-highlight\">
				<thead>
					<th>Impieghi</th>
					<th>Data</th>
					<th>Cifra</th>
				</thead>
				<tbody>";

echo "<tr>";
					
echo   " <td>Attivo corrente</td>";

echo "</tr>";
while (	($row = $stmt4->fetch(PDO::FETCH_ASSOC))	)
	{
				
	
		if($row!=null)
		{
			echo "<tr>";
			extract($row);
			
                echo   " <td>". fromCodeToString($tipo)."</td>
                    <td>{$t_date}</td>
                    <td>{$amount}</td>";				
			
			echo "</tr>";
		}
	}
while (	($row = $stmt45->fetch(PDO::FETCH_ASSOC))	)
	{
				
	
		if($row!=null)
		{
			echo "<tr>";
			extract($row);
			
            echo" <td>". fromCodeToString($tipo)."</td>
                  <td>".month($t_mese)."</td>
                  <td>{$amount}</td>";				
			
			echo "</tr>";
		}
	}
echo "<tr>";
					
echo   " <td>Attivo Immobilizzato</td>";

echo "</tr>";
while (	$row = $stmt3->fetch(PDO::FETCH_ASSOC)	)
	{
		
	
			
		if($row!=null)
		{
			
		echo "<tr>";
		extract($row);
		
		echo    "<td>". fromCodeToString($tipo)."</td>
                 <td>{$t_date}</td>
                 <td>{$amount}</td>";
			
		echo "</tr>";
		}
		
						
	}
while (	$row = $stmt35->fetch(PDO::FETCH_ASSOC)	)
	{
		
	
			
		if($row!=null)
		{
			
		echo "<tr>";
		extract($row);
		
		echo        "<td>". fromCodeToString($tipo)."</td>
                    <td>".month($t_mese)."</td>
                    <td>{$amount}</td>";
			
		echo "</tr>";
		}
		
						
	}
  
echo    "           
            </tbody>
        </table>
    </div>
	 </div>";
	
echo "<div class='col-md-6'>";
echo	"<div class=\"table-responsive\">
				<table class=\"table table-bordered table-striped table-highlight\">
				<thead>
					<th>Fonti</th>
					<th>Data</th>
					<th>Cifra</th>
				</thead>
				<tbody>";
  	

echo "<tr>";
					
echo   " <td>Capitale di debito</td>";

echo "</tr>";
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC) )
	{
		
		//var_dump($row1);
		//var_dump($row2);
		
		
		if($row!=null)
		{
			
			echo "<tr>";
		extract($row);		
	
                 echo  " <td>". fromCodeToString($tipo)."</td>
                    <td>{$t_date}</td>
                    <td>{$amount}</td>";
			
			echo "</tr>";
		}
	}
while ($row = $stmt25->fetch(PDO::FETCH_ASSOC) )
	{
		
		//var_dump($row1);
		//var_dump($row2);
		
		
		if($row!=null)
		{
			
			echo "<tr>";
		extract($row);		
	
                 echo  " <td>". fromCodeToString($tipo)."</td>
                    <td>".month($t_mese)."</td>
                    <td>{$amount}</td>";
			
			echo "</tr>";
		}
	}
	
echo "<tr>";
					
echo   " <td>Capitale proprio</td>";

echo "</tr>";
while ( $row = $stmt1->fetch(PDO::FETCH_ASSOC))
	{	
		if($row!=null)
		{
			
			echo "<tr>";
		extract($row);		
		
				echo   "<td>". fromCodeToString($tipo)."</td>
                    <td>{$t_date}</td>
                    <td>{$amount}</td>";
			
		echo "</tr>";
		}
		
					
	}
while ( $row = $stmt15->fetch(PDO::FETCH_ASSOC))
	{	
		if($row!=null)
		{
			
			echo "<tr>";
		extract($row);		
		
				echo   "<td>". fromCodeToString($tipo)."</td>
                    <td>".month($t_mese)."</td>
                    <td>{$amount}</td>";
			
		echo "</tr>";
		}
		
					
	}
  
echo    "           
            </tbody>
        </table>
    </div>
</div>
 </div>";
 
 echo "<button class=\"normal-button\" onclick=\"window.location.href='{$home_url}/storico/mod.php?y={$y}&b=0'\"> Modifica</button></div>";
 unset($_POST);

 
// footer HTML and JavaScript codes
include "$_SERVER[DOCUMENT_ROOT]/project/layout_foot.php";
