<?php
include_once "config/database.php";
include_once "config/core.php";
include_once "objects/flow.php";
include_once "objects/user.php";

//$action = isset($_GET['action']) ? $_GET['action'] : "";
//$page_title=($action=='create_flow')?"Crea Flow":"Cash Flow";
//$require_login=true;

//include_once "login_checker.php";
//include_once 'layout_head.php';

$database = new Database();
$db = $database->getConnection();
$flow=new Flow($db);
$user=new User($db);
if($user->countAllFlows($_SESSION['username']) == 0 && !isset($_GET['firstTime']))
  {
	 $action = 'create_flow';
  }
 // var_dump($_SESSION);
 // var_dump($action);
//echo "<div class='col-md-12'>";
if($action=='create_flow')
{
    echo "<div class=\"flow-creation\">";
	echo 	"<form name=\"form\" method=\"post\" action=\"index.php?action=flow_created&firstTime=1\" > ";
	echo 		"<p>Nome azienda: <input type=\"text\" name=\"name\" value=\"\" maxlength=\"15\" required /></p>";
	echo "<p>Data fondazione: <input type=\"date\" id=\"foundation\" name=\"foundation\" value=\"1990-01-01\" min=\"1900-01-01\" max=\"2119-12-31\">";
	echo "<p>Primo anno di esercizio?:  
	<input type=\"radio\" id=\"si\" name=\"padi\" value=0 checked> <label for=\"si\">Si</label>
	<input type=\"radio\" id=\"no\" name=\"padi\" value=1 > <label for=\"no\">No</label></p>";
	echo 		"<p>Disponibilità monetaria iniziale: <input type=\"number\" name=\"dmi\" value=\"\" /></p> ";
	echo 		"<p><input name=\"submit\" type=\"submit\" value=\"Crea\" /></p>";
	echo "	</form>";
	echo "</div>";
 }
else if($action=='flow_created')
{
	$flow->name=$_POST['name'];
	$flow->status=$_POST['padi'];
	$flow->dmi=$_POST['dmi'];
	$flow->fondazione=$_POST['foundation'];
	$name=$_SESSION['username'];

	if(!isset($_SESSION['user_id']))
	{
		$sql = "SELECT id FROM Users WHERE username='$name' limit 1";
		$result = mysqli_query($sql);
		$value = mysqli_fetch_object($result);
		$_SESSION['user_id'] = $value->id;
	}
	$flow->user_id=$_SESSION['user_id'];
	$flow->create();
	
	
	
	//creazione transazioni, nella sezione di creazione avverrà l'update
	$data=date('Y');
	$m=date('m');
	$fid=$flow->id;
	

	
	
	//TODO
	$tr= array();
	$tr=  array_push_assoc($tr,'tipo1',111);
    $tr = array_push_assoc($tr,'tipo2',112);
    $tr = array_push_assoc($tr,'tipo3',121);
    $tr = array_push_assoc($tr,'tipo4',122);
    $tr = array_push_assoc($tr,'tipo5',131);
    $tr = array_push_assoc($tr,'tipo6',132);
	$tr = array_push_assoc($tr,'tipo7',133);
	$tr=  array_push_assoc($tr,'tipo8',211);
    $tr= array_push_assoc($tr,'tipo9',221);
	$tr= array_push_assoc($tr,'tipo10',222);
    $tr= array_push_assoc($tr,'tipo11',223);
    $tr= array_push_assoc($tr,'tipo12',231);
	$tr= array_push_assoc($tr,'tipo13',311);
    $tr= array_push_assoc($tr,'tipo14',312);
    $tr= array_push_assoc($tr,'tipo15',313);
    $tr= array_push_assoc($tr,'tipo16',314);
    $tr= array_push_assoc($tr,'tipo17',315);
    $tr= array_push_assoc($tr,'tipo18',316);
    $tr= array_push_assoc($tr,'tipo19',317);
    $tr= array_push_assoc($tr,'tipo20',318);
    $tr= array_push_assoc($tr,'tipo21',321);
    $tr= array_push_assoc($tr,'tipo22',322);
	$tr= array_push_assoc($tr,'tipo23',323);
	$tr =array_push_assoc($tr,'tipo24',410);
    $tr = array_push_assoc($tr,'tipo25',421);
    $tr  =array_push_assoc($tr,'tipo26',422);
    $tr = array_push_assoc($tr,'tipo27',423);

	$q="insert into transazioni values(:id,:tipo,0,1,$data,0,$fid)";
	$q2="insert into tboh values($m,:id)";
	//var_dump($fid);
	for($i=1;$i<12;$i++)
	{
		$id=$_SESSION["user_id"].$fid.date('Y'). $i;
		$stmt1 = $flow->conn->prepare($q);
		$stmt1->bindParam(':id', $id);
		$stmt1->bindParam(':tipo', $tr["tipo".$i]);
		$stmt1->execute();
		
		$stmt1 = $flow->conn->prepare($q2);
		$stmt1->bindParam(':id', $id);
		$stmt1->execute();
	}
	$q="insert into transazioni values(:id,:tipo,0,0,$data,0,$fid)";
	for($i=12;$i<28;$i++)
	{
		$id=$_SESSION["user_id"].$fid.date('Y'). $i;
		$stmt1 = $flow->conn->prepare($q);
		$stmt1->bindParam(':id', $id);
		$stmt1->bindParam(':tipo', $tr["tipo".$i]);
		$stmt1->execute();
		
		$stmt1 = $flow->conn->prepare($q2);
		$stmt1->bindParam(':id', $id);
		$stmt1->execute();
	}
/*	$q="insert into tboh values(0,:id)";
	for($i=1;$i<28;$i++)
	{
		$id=$_SESSION["user_id"].$fid.date('Y'). $i;
		$stmt1 = $flow->conn->prepare($q);
		$stmt1->bindParam(':id', $id);
		$stmt1->execute();
	}
	$q="insert into tprecisa values('0000-00-00',:id)";
	for($i=1;$i<28;$i++)
	{
		$id=$_SESSION["user_id"].$fid.date('Y'). $i;
		$stmt1 = $flow->conn->prepare($q);
		$stmt1->bindParam(':id', $id);
		$stmt1->execute();
	}*/

	
	
	//var_dump($_SESSION);
	//header("Location: {$home_url}index.php");
	echo "<script> location.replace(\"index.php\"); </script>";
	
}
else if($action=='refresh1')
{
	$_SESSION['current_flow_id']=$_GET['flow'];
	echo "<script> location.replace(\"index.php?action=refresh2\"); </script>";
	// header("Location: {$home_url}index.php?");
	
}
else if($action=='delete')
{
	$flow->NuclearOption($_GET['flow']);
	 //header("Location: {$home_url}index.php");
	 echo "<script> location.replace(\"index.php\"); </script>";
	
}
else
  {

      echo "<div class='col-md-12'>";
      if(isset($_SESSION['current_flow_id'])&&$_SESSION['current_flow_id']!=0)
		{
            include_once "singleFlowDisplay.php";
        }
		else
		{
            include_once "flowDisplay.php";
        }
      echo "</div>";


      echo "</div>";
   }
	
    
 
echo "</div>";
 


