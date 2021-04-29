<?php




include_once "$_SERVER[DOCUMENT_ROOT]/project/config/core.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/objects/transaction.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/database.php";
$database = new Database();
$db = $database->getConnection();
//update
$t = new TPrecisa($db);
$fid=$_SESSION['current_flow_id'];
$y=$_GET['y']; // anno
$page_title="Modifica storico $y";


if(isset($_POST) && isset($_GET['action'])&& $_GET['action']=='refresh')
{

	//var_dump($_POST);
	$tot=$_GET['t'];
	//$_GET['t'] è il totale, $_GET['h'] da dove iniziano i mesi
	for( $i=1; $i<$tot+1;$i++)
	{
		$id=$_POST["id$i"];
		$amount=$_POST["amount$i"];
		$q1="UPDATE TRANSAZIONI SET amount=$amount WHERE id=$id";
		if($i<($_GET['t']-$_GET['h']+1))
		{
			if(isset($_POST["data$i"]))
			{
				$data=$_POST["data$i"];
				$q2="UPDATE TPRECISA SET t_date='$data' WHERE id_transazione=$id";
			}
			
			
			
		}
		else
		{
			if(isset($_POST["mese$i"]))
			{
				$data=$_POST["mese$i"];
				$q2="UPDATE TBOH SET t_mese=$data WHERE id_transazione=$id";
			}
		}
		$stmt = $t->conn->prepare($q1);
		$stmt->execute();
		if(isset($q2))
		{
			$stmt0 = $t->conn->prepare($q2);
			$stmt0->execute();
		}

	}


	if(isset($_GET['b']))
		echo "<script> location.replace(\"viewY.php\"); </script>";
	else
		echo "<script> location.replace(\"viewAll.php\"); </script>";

}


if($y==date("Y"))
	$page_title="Storico anno corrente";
else
	$page_title="Storico $y";

$require_login=true;
include_once "$_SERVER[DOCUMENT_ROOT]/project/login_checker.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/layout_head.php";

$i=0;

$row1=null;
$row2=null;


//fonti
$q= "select t.id,t.tipo,t.amount,tp.t_mese from transazioni as t inner join tboh as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND macroarea=0 ORDER BY t_mese ";
$stmt1 = $t->conn->prepare($q);
$stmt1->bindParam(':fid', $fid);
$stmt1->bindParam(':y', $y);
$stmt1->execute();
$num1 = $stmt1->rowCount();

$q= "select t.id,t.tipo,t.amount,tp.t_date from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND macroarea=0 ORDER BY t_date ";
$stmt2 = $t->conn->prepare($q);
$stmt2->bindParam(':fid', $fid);
$stmt2->bindParam(':y', $y);
$stmt2->execute();
$num2 = $stmt2->rowCount();

//impieghi
$q= "select t.id,t.tipo,t.amount,tp.t_mese from transazioni as t inner join tboh as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND macroarea=1 ORDER BY t_mese ";
$stmt3 = $t->conn->prepare($q);
$stmt3->bindParam(':fid', $fid);
$stmt3->bindParam(':y', $y);
$stmt3->execute();
$num3 = $stmt3->rowCount();

$q= "select t.id,t.tipo,t.amount,tp.t_date from transazioni as t inner join tprecisa as tp on tp.id_transazione=t.id where t.id_flow=:fid AND t.anno=:y AND macroarea=1 ORDER BY t_date ";
$stmt4 = $t->conn->prepare($q);
$stmt4->bindParam(':fid', $fid);
$stmt4->bindParam(':y', $y);
$stmt4->execute();
$num4 = $stmt4->rowCount();
$tot= $num1+
	  $num2+
	  $num3+
	  $num4;
$half= $num1+$num3;

if($tot==0)
{
	 echo "<div class='alert alert-info'>";
            echo "<strong>Nessuna transazione con periodo temporale .</strong>";
        echo "</div>";
}
else{
			include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";


				

				if(isset($_GET['b']))
					echo	"<form method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."?t=$tot&h=$half&y=$y&action=refresh&b=0\" class=\"form-horizontal\">";
				else
					echo	"<form method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."?t=$tot&h=$half&y=$y&action=refresh\" class=\"form-horizontal\">";
				echo  "		<div class='col-md-6'>
							<div class=\"table-responsive\">
							<table class=\"table table-bordered table-striped table-highlight\">
							<thead>
								<th>Impieghi</th>
								<th>Data</th>
								<th>Cifra</th>
							</thead>
							<tbody>";
			if($num3>0 || $num4 > 0)
			  {

				while (	($row1 = $stmt4->fetch(PDO::FETCH_ASSOC)) || ($row2 = $stmt3->fetch(PDO::FETCH_ASSOC))	)
				{


					if($row1!=null)
					{
						$i++;
						echo "<tr>";
					extract($row1);
					if($num4>0)
						{
						 echo "<td>". fromCodeToString($tipo)."</td>
						 <td><input id=\"data{$i}\" name=\"data{$i}\" type=\"date\" class=\"form-control\" value=\"{$t_date}\"/></td>
								<td><input id=\"amount{$i}\" name=\"amount{$i}\" type=\"number\" min=\"0\" class=\"form-control\" value=\"{$amount}\"/></td>
								 <input type=\"hidden\" id=\"id{$i}\" name=\"id{$i}\" value=\"{$id}\">";
						}
						echo "</tr>";
					}



					if($row2!=null)
					{
						$i++;
						echo "<tr>";
					extract($row2);
					if($num3>0)
						{
							echo      "<td>". fromCodeToString($tipo)."</td>
								<td><input id=\"mese{$i}\" name=\"mese{$i}\" type=\"number\" class=\"form-control\" value=\"{$t_mese}\"/></td>
								<td><input id=\"amount{$i}\" name=\"amount{$i}\" type=\"number\" min=\"0\" class=\"form-control\" value=\"{$amount}\"/></td>
								<input type=\"hidden\" id=\"id{$i}\" name=\"id{$i}\" value=\"{$id}\">";
						}
						echo "</tr>";
					}
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

			if($num1>0 || $num2 > 0)
			  {
				while (($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) || ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)))
				{

					if($row1!=null)
					{
						echo "<tr>";
						$i++;
					extract($row1);
					if($num1>0)
						{
							 echo  " <td>". fromCodeToString($tipo)."</td>
								<td><input id=\"mese{$i}\" name=\"mese{$i}\" type=\"number\" class=\"form-control\" min=\"1\" max=\"12\" value=\"{$t_mese}\"/></td>
								<td><input id=\"amount{$i}\" name=\"amount{$i}\" type=\"number\" min=\"0\" class=\"form-control\" value=\"{$amount}\"/></td>
								<input type=\"hidden\" id=\"id{$i}\" name=\"id{$i}\" value=\"{$id}\">";
						}
						echo "</tr>";
					}


					if($row2!=null)
					{
						//var_dump($row2);
						echo "<tr>";
					extract($row2);
					$i++;
					if($num2>0)
						{
							echo   "<td>". fromCodeToString($tipo)."</td>
								<td><input id=\"data{$i}\" name=\"data{$i}\" type=\"number\" class=\"form-control\" min=\"1\" max=\"12\" value=\"{$t_date}\"/></td>
								<td><input id=\"amount{$i}\" name=\"amount{$i}\" type=\"number\" min=\"0\" class=\"form-control\" value=\"{$amount}\"/></td>
								<input type=\"hidden\" id=\"id{$i}\" name=\"id{$i}\" value=\"{$id}\">";
						}
					echo "</tr>";
					}


				}
			  }

			echo    "
						</tbody>
					</table>
				</div>
				<div class='footer'>
				 <input class='normal-button' value='salva' type=\"submit\">
				 </div>
				</div>
				</form>
				
				";
}
	
	
	
	
//fonti
$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND macroarea=0";
$stmt1 = $t->conn->prepare($q);
$stmt1->bindParam(':fid', $fid);
$stmt1->bindParam(':y', $y);
$stmt1->execute();
$num1 = $stmt1->rowCount();


//impieghi
$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND macroarea=1";
$stmt3 = $t->conn->prepare($q);
$stmt3->bindParam(':fid', $fid);
$stmt3->bindParam(':y', $y);
$stmt3->execute();
$num3 = $stmt3->rowCount();


$num4 = $stmt4->rowCount();
$tot2= $num1+
	  
	  $num3;
$half= $num1+$num3;

if($tot2==0)
{
	 echo "<div class='alert alert-info'>";
            echo "<strong>Nessuna transazione senza periodo temporale .</strong>";
        echo "</div>";
}
elseif($tot2!=0&&$tot==0){
			include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";


				

				if(isset($_GET['b']))
					echo	"<form method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."?t=$tot&h=$half&y=$y&action=refresh&b=0\" class=\"form-horizontal\">";
				else
					echo	"<form method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."?t=$tot&h=$half&y=$y&action=refresh\" class=\"form-horizontal\">";
				echo  "		<div class='col-md-6'>
							<div class=\"table-responsive\">
							<table class=\"table table-bordered table-striped table-highlight\">
							<thead>
								<th>Impieghi</th>
								<th>Cifra</th>
							</thead>
							<tbody>";
			if($num3>0)
			  {

				while (	$row2 = $stmt3->fetch(PDO::FETCH_ASSOC)	)
				{


					


					if($row2!=null)
					{
						$i++;
						echo "<tr>";
					extract($row2);
					if($num3>0)
						{
							echo      "<td>". fromCodeToString($tipo)."</td>
								
								<td><input id=\"amount{$i}\" name=\"amount{$i}\" type=\"number\" min=\"0\" class=\"form-control\" value=\"{$amount}\"/></td>
								<input type=\"hidden\" id=\"id{$i}\" name=\"id{$i}\" value=\"{$id}\">";
						}
						echo "</tr>";
					}
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
								
								<th>Cifra</th>
							</thead>
							<tbody>";

			if($num1>0)
			  {
				while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC))
				{

					if($row1!=null)
					{
						echo "<tr>";
						$i++;
					extract($row1);
					if($num1>0)
						{
							 echo  " <td>". fromCodeToString($tipo)."</td>
								
								<td><input id=\"amount{$i}\" name=\"amount{$i}\" type=\"number\" min=\"0\" class=\"form-control\" value=\"{$amount}\"/></td>
								<input type=\"hidden\" id=\"id{$i}\" name=\"id{$i}\" value=\"{$id}\">";
						}
						echo "</tr>";
					}




				}
			  }

			echo    "
						</tbody>
					</table>
				</div>
				<div class='footer'>
				 <input class='normal-button' value='salva' type=\"submit\">
				 </div>
				</div>
				</form>
				
				";
}
	



// footer HTML and JavaScript codes

include_once "$_SERVER[DOCUMENT_ROOT]/project/layout_foot.php";

