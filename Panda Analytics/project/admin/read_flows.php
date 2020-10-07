<?php
include_once "../config/core.php";
include_once "login_checker.php";
include_once '../config/database.php';
include_once '../objects/flow.php';
 

$database = new Database();
$db = $database->getConnection();
$flow = new Flow($db);
if(isset($_GET['id']))
	$uid=$_GET['id'];
//var_dump($flow);
$page_title = "Flows";
include_once "layout_head.php";
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	if($action == 'read_from_user')
	{
	
		echo "<div class='col-md-12'>";
		$stmt = $flow->readAllID($from_record_num, $records_per_page,$_GET['id']);
		$num = $stmt->rowCount();
		//var_dump($stmt);
		$page_url="read_flows.php?";
		include_once "read_flows_template.php";
		echo "</div>";
	}
	if($action == 'delete_flow')
	{
	
		$uid=$_GET['uid'];
		$stmt = $flow->NuclearOption($_GET['id']);
		if(isset($_GET['uid']))
		{
			$uid=$_GET['uid'];
			header("Location: {$home_url}/admin/read_flows.php?action=read_from_user&id=$uid");
	
		}
		else
			{header("Location: {$home_url}/admin/read_flows.php");}
		
		echo "</div>";	
	}
	if($action == 'flow_updated')
	{
	
		$id=$_POST['id'];
		$name=$_POST['name'];
		$uid=$_POST['uid'];
		$dmi=$_POST['dmi'];
		$status=$_POST['status'];
		if($flow->update($id,$name,$uid,$dmi,$status))
			header("Location: {$home_url}/admin/read_flows.php?action=read_from_user&id=$uid");
	
		else
			echo "Errore durante l'update";
	
		
		
		echo "</div>";	
	}
}
else
{
 
	echo "<div class='col-md-12'>";
    $stmt = $flow->readAll($from_record_num, $records_per_page);
    $num = $stmt->rowCount();
    $page_url="read_flows.php?";
    include_once "read_flows_template.php";
	echo "</div>";
}
include_once "layout_foot.php";
?>