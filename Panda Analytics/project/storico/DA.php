<?php
function q($t,$n)
{
	if(isset($_SESSION["query_flag"])&& $_SESSION["query_flag"]==true)
	{
        return "update transazioni set tipo=:tipo,amount=:cifra,macroarea=$n,anno=:anno,id_flow=:flow where id=:id";
	}
	
	
	
	$q="select distinct anno from transazioni";
	$stmt = $t->conn->prepare($q);
	$stmt->execute();
	
	while (	($row = $stmt->fetch(PDO::FETCH_ASSOC))	)
	{
		if($row!=null)
		{
			
			extract($row);
			if($anno == date('Y'))
			{
				$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=$n,anno=:anno,id_flow=:flow where id=:id";
					
				$_SESSION["query_flag"] = true;
				break;
			}
			else
			{
				$q="insert into transazioni values(:id,:tipo,:cifra,$n,:anno,0,:flow)";
				$_SESSION["query_flag"] = false;
			}
              
		}
	}
		
	return $q;
	
	
}


$action = '';
$page_title="DAFonti";
$require_login=true;

$y=date('Y');
$Date=date('Y-m-d');
$b=false; // true data non precisa, false data precisa

include_once "$_SERVER[DOCUMENT_ROOT]/project/config/core.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/InputCheckFoo.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/database.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/objects/transaction.php";


if(isset($_GET['t'])&& $_GET['t']==0)
{
	$_SESSION['current_flow_id'] = 0;
	//header("Location: {$home_url}index.php");
	header("Location: http://localhost/project/index.php?action=refresh1");
}

$database = new Database();
$db = $database->getConnection();
$t = new TPrecisa($db);
$action = $_GET['action'];


switch($action)
{
   case '1':{$page_title="DAFonti1";unset($_SESSION["query_flag"]);break;}
   case '2':
   {
     
		//controllare il refresh della pagina è necessario per evitare errori brutti
	   /*$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	   //var_dump($pageWasRefreshed);
	   if($pageWasRefreshed ) 
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
		} else 
		{
			$q=q($t,0);
		}*/
		
		//here you get the url behind the domain.
		$currentPage = $_SERVER['REQUEST_URI'];
		//if the session current page is not set.
		if(!isset($_SESSION['currentPage']))
		{

			//set the session to the current page.
			$_SESSION['currentPage'] = $currentPage;
		}
		//check if the session is not equal to the current page
		if($_SESSION['currentPage'] != $currentPage)
		{

			// if it's not equal you set the session again to the current page.
			$_SESSION['currentPage'] = $currentPage;

			$q=q($t,0);
		}
		else
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
		}
		
		//var_dump($_SESSION["query_flag"]);
		$page_title="DAFonti2";
		$anno=$y;
		$tr1=$_POST;
		$tr1= array_push_assoc($tr1,'tipo1',311);
		$tr1= array_push_assoc($tr1,'tipo2',312);
		$tr1= array_push_assoc($tr1,'tipo3',313);
		$tr1= array_push_assoc($tr1,'tipo4',314);
		$tr1= array_push_assoc($tr1,'tipo5',315);
		$tr1= array_push_assoc($tr1,'tipo6',316);
		$tr1= array_push_assoc($tr1,'tipo7',317);
		$tr1= array_push_assoc($tr1,'tipo8',318);
		$tr1= array_push_assoc($tr1,'tipo9',321);
		$tr1= array_push_assoc($tr1,'tipo10',322);
		$tr1= array_push_assoc($tr1,'tipo11',323);

		for($i=1;$i<12;$i++)
		{
			$idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno". ($i+12);
			$idd = str_replace(' ', '', $idd);
			$tr1= array_push_assoc($tr1,"id$i",$idd);  //id transazione
		}
		
			$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 300 AND 399";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
		$stmt1->bindParam(':y', $anno);
		$stmt1->execute();
		$num = $stmt1->rowCount();
		if($num==0)
			$q="insert into transazioni values(:id,:tipo,:cifra,0,:anno,0,:flow)";
		else
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";

		  
		//$macroarea=0;
		//settype($macroarea, "integer");
	 
		//inserimento nel db
				  
		//  var_dump($tr1);
		for( $i=1;$i<12;$i++)
		{
			$stmt1 = $t->conn->prepare($q);
			$stmt1->bindParam(':id', $tr1["id$i"]); 
			$stmt1->bindParam(':tipo', $tr1["tipo$i"]); 
			$stmt1->bindParam(':cifra', $tr1["cifra$i"."a"]);
		 
			//$stmt1->bindParam(':macroarea', $macroarea);
					 
			$stmt1->bindParam(':anno', $anno);
			$stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
			//var_dump($macroarea);
			$stmt1->execute();
		}


	   for($i=1;$i<12;$i++)
	   {
			//print_r($tr1);
			if($tr1["datetime$i"]!=null)
			{
				$t1= $tr1["datetime$i"];

				$q2="select count(id_transazione) as c from tprecisa where id_transazione=".$tr1["id$i"];
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
				while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
				{
					extract($row);
					if($c == 0)
						$q2="Insert into tprecisa values($t1,".$tr1["id$i"].");";
					else
						$q2="update tprecisa set t_date='$t1'  where id_transazione=".$tr1["id$i"];
				}
			   //   var_dump($q2);
			   
				$stmt1 = $t->conn->prepare($q2);
				#var_dump($i);
				#var_dump($tr1);
				
				$stmt1->execute();
				$q2="delete from tboh where ID_TRANSAZIONE=".$tr1["id$i"]."";
				
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
			   
			}

			else
			{
				$t1=$Date;
				switch($tr1["cibi$i"])
				{
					case "Giorni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." days"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
						
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
						//var_dump($row);
						extract($row);
						if($c == 0)
							$q2="Insert into tboh values($m,".$tr1["id$i"].")";
						else
							$q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}

			   
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

						break;
					}
					case "Mesi" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." months"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
							  $q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
							  $q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						break;
					}
					case "Anni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." years"));
						$dateValue = strtotime($t1);                     
						$m = date("Y", $dateValue)."";
						
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
							  $q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
							  $q2="update tboh set t_date=$m where id_transazione=".$tr1["id$i"];
						}

						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						
						break;
					}
					case "NoScadenza" :
					{
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

						break;
					}

				}
				 
			}
				

		}
	 
				 //  $_SESSION['tr1']= $tr1;
				  /// var_dump($_SESSION); vi entra, ma viene svuotato più avanti
		break;
	}
	case '3':
	{
     
		//debug
		//controllare il refresh della pagina è necessario per evitare errori brutti
	   /*$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	   if($pageWasRefreshed ) 
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
		} else 
		{
			$q=q($t,0);
		}*/
		
		//here you get the url behind the domain.
		$currentPage = $_SERVER['REQUEST_URI'];
		//if the session current page is not set.
		if(!isset($_SESSION['currentPage']))
		{

			//set the session to the current page.
			$_SESSION['currentPage'] = $currentPage;
		}
		//check if the session is not equal to the current page
		if($_SESSION['currentPage'] != $currentPage)
		{

			// if it's not equal you set the session again to the current page.
			$_SESSION['currentPage'] = $currentPage;

			$q=q($t,0);
		}
		else
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";
		}
		   //var_dump($_POST);
		//le cose diventano null a caso
		 
		$anno=$y;
		$page_title="DAImpieghi1";
		$tr1=$_POST;
		$tr1 =array_push_assoc($tr1,'tipo1',410);
		$tr1 = array_push_assoc($tr1,'tipo2',421);
		$tr1  =array_push_assoc($tr1,'tipo3',422);
		$tr1 = array_push_assoc($tr1,'tipo4',423);



		for($i=1;$i<5;$i++)
		{
			$idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno". (23+$i);
			$idd = str_replace(' ', '', $idd);
			// var_dump($idd);
			$tr1= array_push_assoc($tr1,"id$i",$idd);  //id transazione
		}

			$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 400 AND 499";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
		$stmt1->bindParam(':y', $anno);
		$stmt1->execute();
		$num = $stmt1->rowCount();
		if($num==0)
			$q="insert into transazioni values(:id,:tipo,:cifra,0,:anno,0,:flow)";
		else
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=0,anno=:anno,id_flow=:flow where id=:id";

	   // $macroarea=0;
	 
				   
		//var_dump($q);
		for( $i=1;$i<5;$i++)
		{
		  $stmt1 = $t->conn->prepare($q);
		  $stmt1->bindParam(':id', $tr1["id$i"]);
		  $stmt1->bindParam(':tipo', $tr1["tipo$i"]);
		  $stmt1->bindParam(':cifra', $tr1["cifra$i"."a"]);
	   
		  //    $stmt1->bindParam(':macroarea',$macroarea);
		  $stmt1->bindParam(':anno', $anno);
		  $stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
		  $stmt1->execute();
		}






		for( $i=1;$i<5;$i++)
		{
			//print_r($tr1);
			if($tr1["datetime$i"]!=null)
			{
				$t1= $tr1["datetime$i"];
				//var_dump($t1);
				$q2="select count(id_transazione) as c from tprecisa where id_transazione=".$tr1["id$i"];
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
				while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
				{
					#var_dump($t1);
					extract($row);
					if($c == 0)
					  $q2="Insert into tprecisa values($t1,".$tr1["id$i"].");";
					else
					  $q2="update tprecisa set t_date='$t1' where id_transazione=".$tr1["id$i"];
				}
				//   var_dump($q2);
				# var_dump($t1);
				$stmt1 = $t->conn->prepare($q2);
				#var_dump($t1);
				#var_dump($q2);
				$stmt1->execute();
				$q2="delete from tboh where id_transazione=".$tr1["id$i"];
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
		   
			}

			else
			{
				$t1=$Date;
				switch($tr1["cibi$i"])
				{
					case "Giorni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." days"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
						
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}

			   
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

						break;
					}
					case "Mesi" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." months"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						break;
					}
					case "Anni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." years"));
						$dateValue = strtotime($t1);                     
						$m = date("Y", $dateValue)."";
						
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_date=$m where id_transazione=".$tr1["id$i"];
						}

						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						
						break;
					}
					case "NoScadenza" :
					{
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

						break;
					}

				}
				 
			}
			 
		}
	 /*
				   $_SESSION['tr2']=$tr1;
				   
				   
				   var_dump($_SESSION);
				   */
		break;
	}
	 
	case '4':
	{
		 //debug
		 //var_dump($_SESSION);
		 //controllare il refresh della pagina è necessario per evitare errori brutti
	   /*$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	   if($pageWasRefreshed ) 
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
		} else 
		{
			$q=q($t,1);
		}*/
		
		//here you get the url behind the domain.
		$currentPage = $_SERVER['REQUEST_URI'];
		//if the session current page is not set.
		if(!isset($_SESSION['currentPage']))
		{

			//set the session to the current page.
			$_SESSION['currentPage'] = $currentPage;
		}
		//check if the session is not equal to the current page
		if($_SESSION['currentPage'] != $currentPage)
		{

			// if it's not equal you set the session again to the current page.
			$_SESSION['currentPage'] = $currentPage;

			$q=q($t,1);
		}
		else
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
		}
		 
		 
		$page_title="DAImpieghi2";
		$anno=$y;
		$tr1=$_POST;
		$tr1=  array_push_assoc($tr1,'tipo1',111);
		$tr1 = array_push_assoc($tr1,'tipo2',112);
		$tr1 = array_push_assoc($tr1,'tipo3',121);
		$tr1 = array_push_assoc($tr1,'tipo4',122);
		$tr1 = array_push_assoc($tr1,'tipo5',131);
		$tr1 = array_push_assoc($tr1,'tipo6',132);
		$tr1 = array_push_assoc($tr1,'tipo7',133);

		for($i=1;$i<8;$i++)
		{
			$idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno$i";
			$idd = str_replace(' ', '', $idd);
			// var_dump($idd);
			$tr1= array_push_assoc($tr1,"id$i",$idd);  //id transazione
		}
		
			$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 100 AND 199";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
		$stmt1->bindParam(':y', $anno);
		$stmt1->execute();
		$num = $stmt1->rowCount();
		if($num==0)
			$q="insert into transazioni values(:id,:tipo,:cifra,1,:anno,0,:flow)";
		else
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";


		//$macroarea=1;
		//   var_dump($tr1);
	 
		for( $i=1;$i<8;$i++)
		{
			//var_dump ($i);
	 
			$stmt1 = $t->conn->prepare($q);
			$stmt1->bindParam(':id', $tr1["id$i"]);
			$stmt1->bindParam(':tipo', $tr1["tipo$i"]);
			$stmt1->bindParam(':cifra', $tr1["cifra$i"."a"]);
				   
				   
			//  $stmt1->bindParam(':macroarea',$macroarea);
			$stmt1->bindParam(':anno', $anno);
			$stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
			$stmt1->execute();
		}

		for($i=1;$i<8;$i++)
		{
			//print_r($tr1);
			if($tr1["datetime$i"]!=null)
			{
				$t1= $tr1["datetime$i"];

				$q2="select count(id_transazione) as c from tprecisa where id_transazione=".$tr1["id$i"];
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
				while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
				{
					extract($row);
					if($c == 0)
						$q2="Insert into tprecisa values($t1,".$tr1["id$i"].");";
					else
						$q2="update tprecisa set t_date='$t1'  where id_transazione=".$tr1["id$i"];
				}
			   //   var_dump($q2);
			   
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
				$q2="delete from tboh where id_transazione=".$tr1["id$i"];
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
		   
			}

			else
			{
				$t1=$Date;
				switch($tr1["cibi$i"])
				{
					case "Giorni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." days"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
						
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}

			   
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

						break;
					}
					case "Mesi" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." months"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						break;
					}
					case "Anni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." years"));
						$dateValue = strtotime($t1);                     
						$m = date("Y", $dateValue)."";
						
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_date=$m where id_transazione=".$tr1["id$i"];
						}

						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						
						break;
					}
					case "NoScadenza" :
					{
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

						break;
					}
				}
			 
			}
						 
			 
		}
	 /*
				   $_SESSION['tr3']=$tr1;*/
		break;
	}
	case '5':
	{
		/* $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	   if($pageWasRefreshed ) 
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
		} else 
		{
			$q=q($t,1);
		}*/
		
		//here you get the url behind the domain.
		$currentPage = $_SERVER['REQUEST_URI'];
		//if the session current page is not set.
		if(!isset($_SESSION['currentPage']))
		{

			//set the session to the current page.
			$_SESSION['currentPage'] = $currentPage;
		}
		//check if the session is not equal to the current page
		if($_SESSION['currentPage'] != $currentPage)
		{

			// if it's not equal you set the session again to the current page.
			$_SESSION['currentPage'] = $currentPage;

			$q=q($t,1);
		}
		else
		{
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";
		}
		//debug
		//var_dump($_SESSION);
		$anno=$y;
		$tr1=$_POST;
		$tr1=  array_push_assoc($tr1,'tipo1',211);
		$tr1= array_push_assoc($tr1,'tipo2',221);
		$tr1= array_push_assoc($tr1,'tipo3',222);
		$tr1= array_push_assoc($tr1,'tipo4',223);
		$tr1= array_push_assoc($tr1,'tipo5',231);

		for($i=1;$i<6;$i++)
		{
			$idd="".$_SESSION["user_id"].$_SESSION["current_flow_id"]."$anno". ($i+7);
			$idd = str_replace(' ', '', $idd);
			//var_dump($idd);
			$tr1= array_push_assoc($tr1,"id$i",$idd);  //id transazione
		}
			
			$q="select id,tipo,amount from transazioni where id_flow=:fid AND anno=:y AND tipo BETWEEN 200 AND 299";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->bindParam(':fid', $_SESSION['current_flow_id']);
		$stmt1->bindParam(':y', $anno);
		$stmt1->execute();
		$num = $stmt1->rowCount();
		if($num==0)
			$q="insert into transazioni values(:id,:tipo,:cifra,1,:anno,0,:flow)";
		else
			$q="update transazioni set tipo=:tipo,amount=:cifra,macroarea=1,anno=:anno,id_flow=:flow where id=:id";


		// $macroarea=1;
	 
		// var_dump($tr1);
					 
		for( $i=1;$i<6;$i++)
		{
			$stmt1 = $t->conn->prepare($q);
			$stmt1->bindParam(':id', $tr1["id$i"]);
			$stmt1->bindParam(':tipo', $tr1["tipo$i"]);
			$stmt1->bindParam(':cifra', $tr1["cifra$i"."a"]);
			//AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
			   //   $stmt1->bindParam(':macroarea',$macroarea);
			$stmt1->bindParam(':anno', $anno);
			$stmt1->bindParam(':flow', $_SESSION['current_flow_id']);
			$stmt1->execute();
		}

		for($i=1;$i<6;$i++)
		{
			//print_r($tr1);
			if($tr1["datetime$i"]!=null)
			{
				$t1= $tr1["datetime$i"];

				$q2="select count(id_transazione) as c from tprecisa where id_transazione=".$tr1["id$i"];
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
				while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
				{
					extract($row);
					if($c == 0)
						$q2="Insert into tprecisa values($t1,".$tr1["id$i"].");";
					else
						$q2="update tprecisa set t_date='$t1'  where id_transazione=".$tr1["id$i"];
				}
				//   var_dump($q2);
			   
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
				$q2="delete from tboh where id_transazione=".$tr1["id$i"];
				$stmt1 = $t->conn->prepare($q2);
				$stmt1->execute();
		   
			}

			else
			{
				$t1=$Date;
				switch($tr1["cibi$i"])
				{
					case "Giorni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." days"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
					
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}	

					  //var_dump($q2);
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

						break;
					}
					case "Mesi" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." months"));
						$dateValue = strtotime($t1);                     
						$m = date("m", $dateValue)."";
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
							  $q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
							  $q2="update tboh set t_mese=$m where id_transazione=".$tr1["id$i"];
						}
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						break;
					}
					case "Anni" :
					{
						$t1=date('Y-m-d', strtotime($Date. " + ".$tr1["cifra$i"."b"]." years"));
						$dateValue = strtotime($t1);                     
						$m = date("Y", $dateValue)."";
						
						$q2="select count(id_transazione) as c from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						while (	$row = $stmt1->fetch(PDO::FETCH_ASSOC)	)
						{
							extract($row);
							if($c == 0)
								$q2="Insert into tboh values($m,".$tr1["id$i"].")";
							else
								$q2="update tboh set t_date=$m where id_transazione=".$tr1["id$i"];
						}

						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
					
						break;
					}
					case "NoScadenza" :
					{
						$q2="delete from tprecisa where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();
						$q2="delete from tboh where id_transazione=".$tr1["id$i"];
						$stmt1 = $t->conn->prepare($q2);
						$stmt1->execute();

					break;
					}

				}
			 
			}
							  
				  
		}
						
		unset($_SESSION["query_flag"]);
		
		//allora, dato che mysql è molto simpatico e setta a 0 quando dovrebbe essere 1 e viceversa, 
		//qui metto query dal significato incontrovertibile che sistemano questo contrattempo
		$q="update transazioni set macroarea=0 where tipo>300";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->execute();
		$q="update transazioni set macroarea=1 where tipo<300";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->execute();

		echo "<script> location.replace(\"DA.php?t=0\"); </script>";
		
		
		exit;
    }
					 
	default : {$page_title="SPFonti1";break;}
}
 


 include_once "$_SERVER[DOCUMENT_ROOT]/project/login_checker.php";
 include_once "$_SERVER[DOCUMENT_ROOT]/project/layout_head.php";
 ?>

 <script type="text/javascript">


 function clearfield(entry)
     {    
       entry.value = "";
     }
 
 </script>

<?php


 switch($action)
 {
   case 1:
     {
       echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"DA.php?action=2\">";
     
       echo "<div class=\"container\">
       <h3>Capitale di debito</h3><br>
     </div>";
     
     echo "
     
    
       <div class=\"container\">
       <table class=\"table\">
       <thead>
         <tr>
           <th>Debiti a breve scadenza</th>
         </tr>
       </thead>
       <tbody>
      
         <tr>
           <td>Fondi rischi e oneri</td>
           <td>
             <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <title>Inserisci cifra</title>
     
             <label>Inserisci cifra
               <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\"/>
             </label>
           </td>
           <td>
             <label>Inserisci periodo temporale<br>
               <input id=\"cifra1b\" onClick=\"clearfield(document.getElementById('datetime1'))\" type=\"number\" name=\"cifra1b\" maxlength=\"30\" value=\"0\">
             </label>
             <select id=\"cibi1\" name=\"cibi1\" size=\"1\">
               <option value=\"Giorni\">giorni</option>
               <option value=\"Mesi\">mesi</option>
               <option value=\"Anni\">anni</option>
               <option value=\"NoScadenza\">Nessuna scadenza</option>
             </select>
           </td>
           <td>
             <label>Oppure inserisci la data di scadenza
               <input id=\"datetime1\" onClick=\"clearfield(document.getElementById('cifra1b'))\" type=\"date\" name=\"datetime1\">
             </label>
           </td>
         </tr>
         <tr>
           <td>Debiti per TFR</td>
           <td>
             <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <title>Inserisci cifra</title>
             <label>Inserisci cifra
             <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\"/></label>
           </td>
           <td>
             <label>Inserisci periodo temporale<br>
               <input id=\"cifra2b\" onClick=\"clearfield(document.getElementById('datetime2'))\" type=\"number\" name=\"cifra2b\" maxlength=\"30\" value=\"0\">
             </label>
             <select id=\"cibi2\" name=\"cibi2\" size=\"1\">
               <option value=\"Giorni\">giorni</option>
               <option value=\"Mesi\">mesi</option>
               <option value=\"Anni\">anni</option>
               <option value=\"NoScadenza\">Nessuna scadenza</option>
             </select>
           </td>
           <td>
             <label>Oppure inserisci la data di scadenza
               <input id=\"datetime2\" onClick=\"clearfield(document.getElementById('cifra2b'))\" type=\"date\" name=\"datetime2\">
             </label>
           </td>
         </tr>
         <tr>
           <td>Obbligazioni</td>
           <td>
             <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <title>Inserisci cifra</title>
             <label>Inserisci cifra
               <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\"/>
               </label>
           </td>
           <td>
             <label>Inserisci periodo temporale<br>
               <input id=\"cifra3b\" onClick=\"clearfield(document.getElementById('datetime3'))\" type=\"number\" name=\"cifra3b\" maxlength=\"30\" value=\"0\">
             </label>
             <select id=\"cibi3\" name=\"cibi3\" size=\"1\">
               <option value=\"Giorni\">giorni</option>
               <option value=\"Mesi\">mesi</option>
               <option value=\"Anni\">anni</option>
               <option value=\"NoScadenza\">Nessuna scadenza</option>
             </select>
           </td>
           <td>
             <label>Oppure inserisci la data di scadenza
               <input id=\"datetime3\" onClick=\"clearfield(document.getElementById('cifra3b'))\" type=\"date\" name=\"datetime3\">
             </label>
           </td>
         </tr>
         <tr>
           <td>Debiti verso banche</td>
           <td>
             <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <title>Inserisci cifra</title>
             <label>Inserisci cifra
               <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\"/>
             </label>
           </td>
           <td>
             <label>Inserisci periodo temporale<br>
               <input id=\"cifra4b\" onClick=\"clearfield(document.getElementById('datetime4'))\" type=\"number\" name=\"cifra4b\" maxlength=\"30\" value=\"0\">
             </label>
             <select id=\"cibi4\" name=\"cibi4\" size=\"1\">
               <option value=\"Giorni\">giorni</option>
               <option value=\"Mesi\">mesi</option>
               <option value=\"Anni\">anni</option>
               <option value=\"NoScadenza\">Nessuna scadenza</option>
             </select>
           </td>
           <td>
             <label>Oppure inserisci la data di scadenza
               <input type=\"date\" id=\"datetime4\" name=\"datetime4\">
             </label>
           </td>
         </tr>
         <tr><td>Debiti verso fornitori</td>
           <td>
             <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <title>Inserisci cifra</title>
             <div>
               <label>Inserisci cifra
               <input id=\"cifra5a\" name=\"cifra5a\" class=\"eltab\" type=\"number\" value=\"0\"/></label>
             </div>
           </td>
           <td>
             <label>Inserisci periodo temporale<br>
               <input id=\"cifra5b\" onClick=\"clearfield(document.getElementById('datetime5'))\" type=\"number\" name=\"cifra5b\" maxlength=\"30\" value=\"0\">
             </label>
             <select id=\"cibi5\" name=\"cibi5\" size=\"1\">
               <option value=\"Giorni\">giorni</option>
               <option value=\"Mesi\">mesi</option>
               <option value=\"Anni\">anni</option>
               <option value=\"NoScadenza\">Nessuna scadenza</option>
             </select>
           </td>
           <td>
             <label>Oppure inserisci la data di scadenza
               <input id=\"datetime5\" onClick=\"clearfield(document.getElementById('cifra5b'))\" type=\"date\" name=\"datetime5\">
             </label>
           </td>
         </tr>
         <tr>
           <td>Debiti tributari</td>
           <td>
             <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <title>Inserisci cifra</title>
             <label>Inserisci cifra
               <input id=\"cifra6a\" name=\"cifra6a\" class=\"eltab\" type=\"number\" value=\"0\"/>
             </label>
           </td>
           <td>
           <label>Inserisci periodo temporale<br>
             <input id=\"cifra6b\" onClick=\"clearfield(document.getElementById('datetime6'))\" type=\"number\" name=\"cifra6b\" maxlength=\"30\" value=\"0\">
           </label>
           <select id=\"cibi6\" name=\"cibi6\" size=\"1\">
             <option value=\"Giorni\">giorni</option>
             <option value=\"Mesi\">mesi</option>
             <option value=\"Anni\">anni</option>
             <option value=\"NoScadenza\">Nessuna scadenza</option>
           </select>
         </td>
         <td>
         <label>Oppure inserisci la data di scadenza
           <input id=\"datetime6\" onClick=\"clearfield(document.getElementById('cifra6b'))\" type=\"date\" name=\"datetime6\">
         </label>
         </td>
       </tr>
       <tr>
         <td>Debiti verso Istituti di previdenza</td>
         <td>
           <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
           <title>Inserisci cifra</title>
           <label>Inserisci cifra
             <input id=\"cifra7a\" name=\"cifra7a\" class=\"eltab\" type=\"number\" value=\"0\"/>
           </label>
         </td>
         <td>
           <label>Inserisci periodo temporale<br>
             <input id=\"cifra7b\" onClick=\"clearfield(document.getElementById('datetime7'))\" type=\"number\" name=\"cifra7b\" maxlength=\"30\" value=\"0\">
           </label>
           <select id=\"cibi7\" name=\"cibi7\" size=\"1\">
             <option value=\"Giorni\">giorni</option>
             <option value=\"Mesi\">mesi</option>
             <option value=\"Anni\">anni</option>
             <option value=\"NoScadenza\">Nessuna scadenza</option>
           </select>
         </td>
         <td>
           <label>Oppure inserisci la data di scadenza
             <input id=\"datetime7\" onClick=\"clearfield(document.getElementById('cifra7b'))\" type=\"date\" name=\"datetime7\">
           </label>
         </td>
       </tr>
       <tr>
         <td>Ratei e risconti</td>
         <td>
           <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
           <title>Inserisci cifra</title>
           <label>Inserisci cifra
             <input id=\"cifra8a\" name=\"cifra8a\" class=\"eltab\" type=\"number\" value=\"0\"/>
           </label>
         </td>
         <td>
           <label>Inserisci periodo temporale<br>
             <input id=\"cifra8b\" onClick=\"clearfield(document.getElementById('datetime8'))\" type=\"number\" name=\"cifra8b\" maxlength=\"30\" value=\"0\">
             </label>
             <select id=\"cibi8\" name=\"cibi8\" size=\"1\">
               <option value=\"Giorni\">giorni</option>
               <option value=\"Mesi\">mesi</option>
               <option value=\"Anni\">anni</option>
               <option value=\"NoScadenza\">Nessuna scadenza</option>
               </select>
         </td>
         <td>
           <label>Oppure inserisci la data di scadenza
             <input id=\"datetime8\" onClick=\"clearfield(document.getElementById('cifra8b'))\" type=\"date\" name=\"datetime8\">
           </label>
         </td>
       </tr>
     </tbody>
     <thead>
       <tr><th>Debiti a media/lunga scadenza</th></tr>
     </thead>
     <tbody>
       <tr>
         <td>Debiti per TFR</td>
         <td>
           <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
           <title>Inserisci cifra</title>
           <label>Inserisci cifra
             <input id=\"cifra9a\" name=\"cifra9a\" class=\"eltab\" type=\"number\" value=\"0\"/>
           </label>
         </td>
         <td>
           <label>Inserisci periodo temporale<br>
             <input id=\"cifra9b\" onClick=\"clearfield(document.getElementById('datetime9'))\" type=\"number\" name=\"cifra9b\" maxlength=\"30\" value=\"0\">
           </label>
           <select id=\"cibi9\" name=\"cibi9\" size=\"1\">
             <option value=\"Giorni\">giorni</option>
             <option value=\"Mesi\">mesi</option>
             <option value=\"Anni\">anni</option>
             <option value=\"NoScadenza\">Nessuna scadenza</option>
           </select>
         </td>
         <td>
           <label>Oppure inserisci la data di scadenza
             <input id=\"datetime9\" onClick=\"clearfield(document.getElementById('cifra9b'))\" type=\"date\" name=\"datetime9\">
           </label>
         </td>
       </tr>
       <tr>
         <td>Obbligazioni</td>
         <td>
           <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
           <title>Inserisci cifra</title>
           <label>Inserisci cifra
             <input id=\"cifra10a\" name=\"cifra10a\" class=\"eltab\" type=\"number\" value=\"0\"/>
           </label>
         </td>
         <td>
           <label>Inserisci periodo temporale<br>
             <input id=\"cifra10b\" onClick=\"clearfield(document.getElementById('datetime10'))\" type=\"number\" name=\"cifra10b\" maxlength=\"30\" value=\"0\">
           </label>
           <select id=\"cibi10\" name=\"cibi10\" size=\"1\">
             <option value=\"Giorni\">giorni</option>
             <option value=\"Mesi\">mesi</option>
             <option value=\"Anni\">anni</option>
             <option value=\"NoScadenza\">Nessuna scadenza</option>
           </select>
         </td>
         <td>
           <label>Oppure inserisci la data di scadenza
             <input id=\"datetime10\" onClick=\"clearfield(document.getElementById('cifra10b'))\" type=\"date\" name=\"datetime10\">
           </label>
           </td>
         </tr>
         <tr>
           <td>Debiti verso banche</td>
           <td>
             <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
             <title>Inserisci cifra</title>
             <label>Inserisci cifra
               <input id=\"cifra11a\" name=\"cifra11a\" class=\"eltab\" type=\"number\" value=\"0\"/>
             </label>
           </td>
           <td>
             <label>Inserisci periodo temporale<br>
               <input id=\"cifra11b\" onClick=\"clearfield(document.getElementById('datetime11'))\" type=\"number\" name=\"cifra11b\" maxlength=\"30\" value=\"0\">
             </label>
             <select id=\"cibi11\" name=\"cibi11\" size=\"1\">
               <option value=\"Giorni\">giorni</option>
               <option value=\"Mesi\">mesi</option>
               <option value=\"Anni\">anni</option>
               <option value=\"NoScadenza\">Nessuna scadenza</option>
             </select>
           </td>
           <td>
             <label>Oppure inserisci la data di scadenza
               <input id=\"datetime11\" onClick=\"clearfield(document.getElementById('cifra11b'))\" type=\"date\" name=\"datetime11\">
             </label>
           </td>
         </tr>".
       /*</form>*/"
       </tbody>
     </table>";
     
     echo "</div>
           
           <div class=\"footer\" align=\"center\">
             <div class=\"pagination\">";
            //echo "<button href=\"{$home_url}index.php\" class=\"previous\">&laquo; Indietro</button>";
       echo "<a><strong>1</strong></a>";
       echo "<a>2</a>";
       echo "<a>3</a>";
       echo "<a>4</a>
             <input class=\"next\" type=\"submit\" value=\"Avanti &raquo;\"/>";
       echo"
      
     </div>
   </div>
   
   </form>";

   break;
     
    
   }
   case 2:
   {
      
     echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"DA.php?action=3\">";
     echo "<div class=\"container\">
 <h3>Capitale proprio</h3><br>
</div>";

echo "



<div class=\"container\">
<table class=\"table\">
<thead>
  <tr>
    <th>Capitale sociale</th>
  </tr>
</thead>
<tbody>

  <tr>
    <td>Capitale sociale</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>

      <label>Inserisci cifra
        <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\"/>
      </label>
    </td>
    <td>
      <label>Inserisci periodo temporale<br>
        <input id=\"cifra1b\" name=\"cifra1b\" type=\"number\" maxlength=\"30\" value=\"0\">
      </label>
      <select id=\"cibi1\" name=\"cibi1\" size=\"1\">
        <option value=\"Giorni\">giorni</option>
        <option value=\"Mesi\">mesi</option>
        <option value=\"Anni\">anni</option>
        <option value=\"NoScadenza\">Nessuna scadenza</option>
      </select>
    </td>
    <td>
      <label>Oppure inserisci la data di scadenza
        <input id=\"datetime1\" type=\"date\" name=\"datetime1\">
      </label>
    </td>
  </tr>
</tbody>
<thead>
<tr><th>Riserve</th></tr>
</thead>
<tbody>
<tr>
  <td>Riserva legale</td>
  <td>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
    <title>Inserisci cifra</title>
    <label>Inserisci cifra
      <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\"/>
    </label>
  </td>
  <td>
    <label>Inserisci periodo temporale<br>
      <input id=\"cifra2b\" onClick=\"clearfield(document.getElementById('datetime2'))\" type=\"number\" name=\"cifra2b\" maxlength=\"30\" value=\"0\">
    </label>
    <select id=\"cibi2\" name=\"cibi2\" size=\"1\">
      <option value=\"Giorni\">giorni</option>
      <option value=\"Mesi\">mesi</option>
      <option value=\"Anni\">anni</option>
      <option value=\"NoScadenza\">Nessuna scadenza</option>
    </select>
  </td>
  <td>
    <label>Oppure inserisci la data di scadenza
      <input id=\"datetime2\" onClick=\"clearfield(document.getElementById('cifra2b'))\" type=\"date\" name=\"datetime2\">
    </label>
  </td>
</tr>
<tr>
  <td>Riserva straordinaria</td>
  <td>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
    <title>Inserisci cifra</title>
    <label>Inserisci cifra
      <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\"/>
    </label>
  </td>
  <td>
    <label>Inserisci periodo temporale<br>
      <input id=\"cifra3b\" onClick=\"clearfield(document.getElementById('datetime3'))\" type=\"number\" name=\"cifra3b\" maxlength=\"30\" value=\"0\">
    </label>
    <select id=\"cibi3\" name=\"cibi3\" size=\"1\">
      <option value=\"Giorni\">giorni</option>
      <option value=\"Mesi\">mesi</option>
      <option value=\"Anni\">anni</option>
      <option value=\"NoScadenza\">Nessuna scadenza</option>
    </select>
  </td>
  <td>
    <label>Oppure inserisci la data di scadenza
      <input id=\"datetime3\" onClick=\"clearfield(document.getElementById('cifra3b'))\" type=\"date\" name=\"datetime3\">
    </label>
    </td>
  </tr>
  <tr>
    <td>Utile dell'esercizio</td>
    <td>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <title>Inserisci cifra</title>
      <label>Inserisci cifra
        <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\"/>
      </label>
    </td>
    <td>
      <label>Inserisci periodo temporale<br>
        <input id=\"cifra4b\" onClick=\"clearfield(document.getElementById('datetime4'))\" type=\"number\" name=\"cifra4b\" maxlength=\"30\" value=\"0\">
      </label>
      <select id=\"cibi4\" name=\"cibi4\" size=\"1\">
        <option value=\"Giorni\">giorni</option>
        <option value=\"Mesi\">mesi</option>
        <option value=\"Anni\">anni</option>
        <option value=\"NoScadenza\">Nessuna scadenza</option>
      </select>
    </td>
    <td>
      <label>Oppure inserisci la data di scadenza
        <input id=\"datetime4\" onClick=\"clearfield(document.getElementById('cifra4b'))\" type=\"date\" name=\"datetime4\">
      </label>
    </td>
  </tr>".
/*</form>*/"
</tbody>
</table>";

echo "</div>

<div class=\"footer\" align=\"center\">
       <div class=\"pagination\">";
 
       echo "<a>1</a>";
       echo "<a><strong>2</strong></a>";
       echo "<a>3</a>";
       echo "<a>4</a>
       <input class=\"next\" type=\"submit\" value=\"Salva\"/>";
       echo"
     
     </div>
     </div>
   </form>";

     break;
   }
   case 3:
   {
     echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"DA.php?action=4\">";
     echo "<div class=\"container\">
     <h3>Attivo Corrente</h3><br>
    </div>";
    
    echo "
    
    
    <div>
    <div class=\"container\">
    <table class=\"table\">
    <thead>
      <tr>
        <th>Disponibilità liquide</th>
      </tr>
    </thead>
    <tbody>
  
      <tr>
        <td>Depositi bancari e postali</td>
        <td>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
          <title>Inserisci cifra</title>
    
          <label>Inserisci cifra
            <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\"/>
          </label>
        </td>
        <td>
          <label>Inserisci periodo temporale<br>
            <input id=\"cifra1b\" name=\"cifra1b\" type=\"number\" maxlength=\"30\" value=\"0\">
          </label>
          <select id=\"cibi1\" name=\"cibi1\" size=\"1\">
            <option value=\"Giorni\">giorni</option>
            <option value=\"Mesi\">mesi</option>
            <option value=\"Anni\">anni</option>
            <option value=\"NoScadenza\">Nessuna scadenza</option>
          </select>
        </td>
        <td>
          <label>Oppure inserisci la data di scadenza
            <input id=\"datetime1\" type=\"date\" name=\"datetime1\">
          </label>
        </td>
      </tr>
      <tr>
      <td>Denaro e valori in cassa</td>
      <td>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        <title>Inserisci cifra</title>
    
        <label>Inserisci cifra
          <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\"/>
        </label>
      </td>
      <td>
        <label>Inserisci periodo temporale<br>
          <input id=\"cifra2b\" name=\"cifra2b\" type=\"number\" maxlength=\"30\" value=\"0\">
        </label>
        <select id=\"cibi2\" name=\"cibi2\" size=\"1\">
          <option value=\"Giorni\">giorni</option>
          <option value=\"Mesi\">mesi</option>
          <option value=\"Anni\">anni</option>
          <option value=\"NoScadenza\">Nessuna scadenza</option>
        </select>
      </td>
      <td>
        <label>Oppure inserisci la data di scadenza
          <input id=\"datetime2\" onClick=\"clearfield(document.getElementById('cifra2b'))\" type=\"date\" name=\"datetime2\">
        </label>
      </td>
    </tr>
    </tbody>
    <thead>
    <tr><th>Disponibilità finanziarie</th></tr>
    </thead>
    <tbody>
    <tr>
      <td>Crediti verso clienti</td>
      <td>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        <title>Inserisci cifra</title>
        <label>Inserisci cifra
          <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\"/>
        </label>
      </td>
      <td>
        <label>Inserisci periodo temporale<br>
          <input id=\"cifra3b\" onClick=\"clearfield(document.getElementById('datetime3'))\" type=\"number\" name=\"cifra3b\" maxlength=\"30\" value=\"0\">
        </label>
        <select id=\"cibi3\" name=\"cibi3\" size=\"1\">
          <option value=\"Giorni\">giorni</option>
          <option value=\"Mesi\">mesi</option>
          <option value=\"Anni\">anni</option>
          <option value=\"NoScadenza\">Nessuna scadenza</option>
        </select>
      </td>
      <td>
        <label>Oppure inserisci la data di scadenza
          <input id=\"datetime3\" onClick=\"clearfield(document.getElementById('cifra3b'))\" type=\"date\" name=\"datetime3\">
        </label>
      </td>
    </tr>
    <tr>
      <td>Ratei e riscontri</td>
      <td>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        <title>Inserisci cifra</title>
        <label>Inserisci cifra
          <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\"/>
        </label>
      </td>
      <td>
        <label>Inserisci periodo temporale<br>
          <input id=\"cifra4b\" onClick=\"clearfield(document.getElementById('datetime4'))\" type=\"number\" name=\"cifra4b\" maxlength=\"30\" value=\"0\">
        </label>
        <select id=\"cibi4\" name=\"cibi4\" size=\"1\">
          <option value=\"Giorni\">giorni</option>
          <option value=\"Mesi\">mesi</option>
          <option value=\"Anni\">anni</option>
          <option value=\"NoScadenza\">Nessuna scadenza</option>
        </select>
      </td>
      <td>
        <label>Oppure inserisci la data di scadenza
          <input id=\"datetime4\" onClick=\"clearfield(document.getElementById('cifra4b'))\" type=\"date\" name=\"datetime4\">
        </label>
        </td>
      </tr>
    <thead>
      <tr><th>Rimanenze</th></tr>
    </thead>
    <tbody>
      <tr>
        <td>Materie prime, sussidarie e di consumo</td>
        <td>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
          <title>Inserisci cifra</title>
          <label>Inserisci cifra
            <input id=\"cifra5a\" name=\"cifra5a\" class=\"eltab\" type=\"number\" value=\"0\"/>
          </label>
        </td>
        <td>
          <label>Inserisci periodo temporale<br>
            <input id=\"cifra5b\" onClick=\"clearfield(document.getElementById('datetime5'))\" type=\"number\" name=\"cifra5b\" maxlength=\"30\" value=\"0\">
          </label>
          <select id=\"cibi5\" name=\"cibi5\" size=\"1\">
            <option value=\"Giorni\">giorni</option>
            <option value=\"Mesi\">mesi</option>
            <option value=\"Anni\">anni</option>
            <option value=\"NoScadenza\">Nessuna scadenza</option>
          </select>
        </td>
        <td>
          <label>Oppure inserisci la data di scadenza
            <input id=\"datetime5\" onClick=\"clearfield(document.getElementById('cifra5b'))\" type=\"date\" name=\"datetime5\">
          </label>
        </td>
      </tr>
      <tr>
        <td>Prodotti in corso di lavorazione e semilavorate</td>
        <td>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
          <title>Inserisci cifra</title>
          <label>Inserisci cifra
            <input id=\"cifra6a\" name=\"cifra6a\" class=\"eltab\" type=\"number\" value=\"0\"/>
          </label>
        </td>
        <td>
          <label>Inserisci periodo temporale<br>
            <input id=\"cifra6b\" onClick=\"clearfield(document.getElementById('datetime6'))\" type=\"number\" name=\"cifra6b\" maxlength=\"30\" value=\"0\">
          </label>
          <select id=\"cibi6\" name=\"cibi6\" size=\"1\">
            <option value=\"Giorni\">giorni</option>
            <option value=\"Mesi\">mesi</option>
            <option value=\"Anni\">anni</option>
            <option value=\"NoScadenza\">Nessuna scadenza</option>
          </select>
        </td>
        <td>
          <label>Oppure inserisci la data di scadenza
            <input id=\"datetime6\" onClick=\"clearfield(document.getElementById('cifra6b'))\" type=\"date\" name=\"datetime6\">
          </label>
          </td>
      </tr>
      <tr>
        <td>Prodotti finiti e merci</td>
        <td>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
          <title>Inserisci cifra</title>
          <label>Inserisci cifra
            <input id=\"cifra7a\" name=\"cifra7a\" class=\"eltab\" type=\"number\" value=\"0\"/>
          </label>
        </td>
        <td>
          <label>Inserisci periodo temporale<br>
            <input id=\"cifra7b\" onClick=\"clearfield(document.getElementById('datetime7'))\" type=\"number\" name=\"cifra7b\" maxlength=\"30\" value=\"0\">
          </label>
          <select id=\"cibi7\" name=\"cibi7\" size=\"1\">
            <option value=\"Giorni\">giorni</option>
            <option value=\"Mesi\">mesi</option>
            <option value=\"Anni\">anni</option>
            <option value=\"NoScadenza\">Nessuna scadenza</option>
          </select>
        </td>
        <td>
          <label>Oppure inserisci la data di scadenza
            <input id=\"datetime7\" onClick=\"clearfield(document.getElementById('cifra7b'))\" type=\"date\" name=\"datetime7\">
          </label>
        </td>
      </tr>".
    /*</form>*/"
    </tbody>
    </table>";
    
    echo "</div>


    <div class=\"footer\" align=\"center\">
       <div class=\"pagination\">";
 
       echo "<a>1</a>";
       echo "<a>2</a>";
       echo "<a><strong>3</strong></a>";
       echo "<a>4</a>
       <input class=\"next\" type=\"submit\" value=\"Salva\"/>";
       echo"
     
     </div>
     </div>
   </form>";


     break;
   }
   case 4:
   {
     echo "<form id=\"form\" class=\"appnitro\"  method=\"post\" action=\"DA.php?action=5\">";
     echo "<div class=\"container\">
     <h3>Attivo immobilizzato</h3><br>
    </div>";
    
    echo "
    
    
    <div>
    <div class=\"container\">
    <table class=\"table\">
    <thead>
      <tr>
        <th>Immobilizzazioni immateriali</th>
      </tr>
    </thead>
    <tbody>
    
      <tr>
        <td>Diritti di brevetto industriale e diritti di utilizzazione</td>
        <td>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
          <title>Inserisci cifra</title>
    
          <label>Inserisci cifra
            <input id=\"cifra1a\" name=\"cifra1a\" class=\"eltab\" type=\"number\" value=\"0\"/>
          </label>
        </td>
        <td>
          <label>Inserisci periodo temporale<br>
            <input id=\"cifra1b\" name=\"cifra1b\" type=\"number\" maxlength=\"30\" value=\"0\">
          </label>
          <select id=\"cibi1\" name=\"cibi1\" size=\"1\">
            <option value=\"Giorni\">giorni</option>
            <option value=\"Mesi\">mesi</option>
            <option value=\"Anni\">anni</option>
            <option value=\"NoScadenza\">Nessuna scadenza</option>
          </select>
        </td>
        <td>
          <label>Oppure inserisci la data di scadenza
            <input id=\"datetime1\" type=\"date\" name=\"datetime1\">
          </label>
        </td>
      </tr>
    </tbody>
    <thead>
    <tr><th>Immobilizzazioni materiali</th></tr>
    </thead>
    <tbody>
    <tr>
      <td>Terreni e fabbricati</td>
      <td>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        <title>Inserisci cifra</title>
        <label>Inserisci cifra
          <input id=\"cifra2a\" name=\"cifra2a\" class=\"eltab\" type=\"number\" value=\"0\"/>
        </label>
      </td>
      <td>
        <label>Inserisci periodo temporale<br>
          <input id=\"cifra2b\" onClick=\"clearfield(document.getElementById('datetime2'))\" type=\"number\" name=\"cifra2b\" maxlength=\"30\" value=\"0\">
        </label>
        <select id=\"cibi2\" name=\"cibi2\" size=\"1\">
          <option value=\"Giorni\">giorni</option>
          <option value=\"Mesi\">mesi</option>
          <option value=\"Anni\">anni</option>
          <option value=\"NoScadenza\">Nessuna scadenza</option>
        </select>
      </td>
      <td>
        <label>Oppure inserisci la data di scadenza
          <input id=\"datetime2\" onClick=\"clearfield(document.getElementById('cifra2b'))\" type=\"date\" name=\"datetime2\">
        </label>
      </td>
    </tr>
    <tr>
      <td>Attrezzature industriali e commerciali</td>
      <td>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        <title>Inserisci cifra</title>
        <label>Inserisci cifra
          <input id=\"cifra3a\" name=\"cifra3a\" class=\"eltab\" type=\"number\" value=\"0\"/>
        </label>
      </td>
      <td>
        <label>Inserisci periodo temporale<br>
          <input id=\"cifra3b\" onClick=\"clearfield(document.getElementById('datetime3'))\" type=\"number\" name=\"cifra3b\" maxlength=\"30\" value=\"0\">
        </label>
        <select id=\"cibi3\" name=\"cibi3\" size=\"1\">
          <option value=\"Giorni\">giorni</option>
          <option value=\"Mesi\">mesi</option>
          <option value=\"Anni\">anni</option>
          <option value=\"NoScadenza\">Nessuna scadenza</option>
        </select>
      </td>
      <td>
        <label>Oppure inserisci la data di scadenza
          <input id=\"datetime3\" onClick=\"clearfield(document.getElementById('cifra3b'))\" type=\"date\" name=\"datetime3\">
        </label>
        </td>
      </tr>
      <tr>
        <td>Altri beni</td>
        <td>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
          <title>Inserisci cifra</title>
          <label>Inserisci cifra
            <input id=\"cifra4a\" name=\"cifra4a\" class=\"eltab\" type=\"number\" value=\"0\"/>
          </label>
        </td>
        <td>
          <label>Inserisci periodo temporale<br>
            <input id=\"cifra4b\" onClick=\"clearfield(document.getElementById('datetime4'))\" type=\"number\" name=\"cifra4b\" maxlength=\"30\" value=\"0\">
          </label>
          <select id=\"cibi4\" name=\"cibi4\" size=\"1\">
            <option value=\"Giorni\">giorni</option>
            <option value=\"Mesi\">mesi</option>
            <option value=\"Anni\">anni</option>
            <option value=\"NoScadenza\">Nessuna scadenza</option>
          </select>
        </td>
        <td>
          <label>Oppure inserisci la data di scadenza
            <input id=\"datetime4\" onClick=\"clearfield(document.getElementById('cifra4b'))\" type=\"date\" name=\"datetime4\">
          </label>
        </td>
      </tr>
     </tbody>
     <thead>
    <tr><th>Immobilizzazioni finanziarie</th></tr>
    </thead>
    <tbody>
    <tr>
      <td>Crediti verso clienti</td>
      <td>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        <title>Inserisci cifra</title>
        <label>Inserisci cifra
          <input id=\"cifra5a\" name=\"cifra5a\" class=\"eltab\" type=\"number\" value=\"0\"/>
        </label>
      </td>
      <td>
        <label>Inserisci periodo temporale<br>
          <input id=\"cifra5b\" onClick=\"clearfield(document.getElementById('datetime5'))\" type=\"number\" name=\"cifra5b\" maxlength=\"30\" value=\"0\">
        </label>
        <select id=\"cibi5\" name=\"cibi5\" size=\"1\">
          <option value=\"Giorni\">giorni</option>
          <option value=\"Mesi\">mesi</option>
          <option value=\"Anni\">anni</option>
          <option value=\"NoScadenza\">Nessuna scadenza</option>
        </select>
      </td>
      <td>
        <label>Oppure inserisci la data di scadenza
          <input id=\"datetime5\" onClick=\"clearfield(document.getElementById('cifra5b'))\" type=\"date\" name=\"datetime5\">
        </label>
      </td>
    </tr>".
    "</form>
    </tbody>
    </table>";
    

 
     echo "</div>
     <div class=\"footer\" align=\"center\">
       <div class=\"pagination\">";
 
       echo "<a>1</a>";
       echo "<a>2</a>";
       echo "<a>3</a>";
       echo "<a><strong>4</strong></a>
       <input class=\"next\" type=\"submit\" value=\"Salva\"/>";
       echo"
     
     </div>
     </div>
     </form>";

     break;
   }



 }
 


?>
    


<?php
// footer HTML and JavaScript codes
include "$_SERVER[DOCUMENT_ROOT]/project/layout_foot.php";
?>
