<?php
include_once "../config/core.php";
include_once "login_checker.php";
include_once '../config/database.php';
include_once '../objects/transaction.php';
 

$database = new Database();
$db = $database->getConnection();
$transactionP = new TPrecisa($db);
$transactionB = new TBoh($db);
if(isset($_GET['id']))
	$fid=$_GET['id'];
/*var_dump($transactionP);
var_dump($transactionB);*/
$page_title = "Transactions";
include_once "layout_head.php";
if(isset($_GET['action']))
{
	$action=$_GET['action'];
	if($action == 'read_from_flow')
	{
	
		echo "<div class='col-md-12'>";
		$stmt1 = $transactionP->readAllID($from_record_num, $records_per_page,$fid);
		$stmt2 = $transactionB->readAllID($from_record_num, $records_per_page,$fid);
		$num1 = $stmt1->rowCount();
		$num2 = $stmt2->rowCount();
		$page_url="read_transactions.php?";
		include_once "read_transactions_template.php";
		echo "</div>";	
	}
	if($action == 'delete_transaction')
	{
	
		$fid=$_GET['fid'];
		$stmt = $transactionP->del($_GET['id']);
		header("Location: {$home_url}/admin/read_transactions.php?action=read_from_flow&id=$fid");
		
		echo "</div>";	
	}
	if($action == 'transaction_updated')
	{
		
		$id=$_POST['id'];
		$tipo=$_POST['tipo'];
		$macro=$_POST['ma'];
		$amm=$_POST['amm'];
		$y=$_POST['y'];
		$ra=$_POST['ra'];
		$fid=$_POST['fid'];
		if(isset($_POST['m']))
		{
			$flag = false;//non precisa
			$m=$_POST['m'];
			$transactionB->update($id,$tipo,$macro,$amm,$y,$ra,$m,$fid,$flag);
			header("Location: {$home_url}/admin/read_transactions.php?action=read_from_flow&id=$fid");		
			
			
		}
		else
		{
			$flag = true;//precisa
			$dp=$_POST['dp'];
			$transactionP->update($id,$tipo,$macro,$amm,$y,$ra,$dp,$fid,$flag);
			header("Location: {$home_url}/admin/read_transactions.php?action=read_from_flow&id=$fid");
			
			
		}
		
		echo "</div>";	
	}
	
}
else
{

	echo "<div class='col-md-12'>";
	$stmt1 = $transactionP->readAll($from_record_num, $records_per_page);
	$stmt2 = $transactionB->readAll($from_record_num, $records_per_page);
	$num1 = $stmt1->rowCount();
	$num2 = $stmt2->rowCount();
    $page_url="read_transactions.php?";
    include_once "read_transactions_template.php";
	echo "</div>";
}
include_once "layout_foot.php";
?>