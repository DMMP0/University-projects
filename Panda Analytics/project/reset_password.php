<?php

include_once "config/core.php";
$page_title = "Reset Password";
include_once "login_checker.php";
include_once "config/database.php";
include_once "objects/user.php";
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
include_once "layout_head.php";
 
echo "<div class='col-sm-12'>";
 
 
$access_code= $_GET['access_code'] ?? die("Access code not found.");
 

$user->access_code=$access_code;
 
if(!$user->accessCodeExists())
{
    die('Access code not found.');
}
 
else
{

	if($_POST)
	{
	 

		$user->password=$_POST['password'];
	 

		if($user->updatePassword()){
			echo "<div class='alert alert-info'>Password was reset. Please <a href='{$home_url}login'>login.</a></div>";
		}
	 
		else{
			echo "<div class='alert alert-danger'>Unable to reset password.</div>";
		}
	}
 
	echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?access_code={$access_code}' method='post'>
		<table class='table table-hover table-responsive table-bordered'>
			<tr>
				<td>Password</td>
				<td><input type='password' name='password' class='form-control' required></td>
			</tr>
			<tr>
				<td></td>
				<td><button type='submit' class='btn btn-primary'>Reset Password</button></td>
			</tr>
		</table>
	</form>";
}
 
echo "</div>";
 
include_once "layout_foot.php";
