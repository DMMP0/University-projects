<?php
include_once "../config/core.php";
include_once "login_checker.php";
include_once '../config/database.php';
include_once '../objects/user.php';


$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if(isset($_GET['action']))
{
 
	if($_GET['action'] == 'delete_user')
	{
		
		$uid=$_GET['uid'];
		$q="delete from utente where id=$uid";
		$stmt1 = $user->conn->prepare($q);
		$stmt1->execute();
		
		header("Location: {$home_url}/admin/read_users.php");

	}
}

$page_title = "Users";
include_once "layout_head.php";
 
echo "<div class='col-md-12'>";
$stmt = $user->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();
$page_url="read_users.php?";
include_once "read_users_template.php";
echo "</div>";

include_once "layout_foot.php";
