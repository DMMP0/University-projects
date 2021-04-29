<?php
	
include_once "config/database.php";
include_once "objects/user.php";

//var_dump($_POST);
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->email= $_POST['Login_name'] ?? "";
$email_exists = $user->emailExists();
//var_dump($user);

// validate login

//validate password
$pass= $_POST['Login_password'] ?? "";
$password_ver = false; $p=pbkdf2('sha3-512' , $pass,$user->salt,1000,100);
if($user->password === $p)
	$password_ver = true;
//var_dump($p);var_dump($user);var_dump($_POST['password']);
if ($email_exists && $password_ver)
{
	$_SESSION['logged_in'] = true;
	$_SESSION['user_id'] = $user->id;
	$_SESSION['access_level'] = $user->access_level;
	$_SESSION['username'] = htmlspecialchars($user->name, ENT_QUOTES) ;
	$_SESSION['Login_password'] = htmlspecialchars($_POST['Login_password'], ENT_QUOTES) ;
	$_SESSION['mail']= htmlspecialchars($user->mail, ENT_QUOTES) ;


	if($user->access_level=='admin')
	{
		//  header("Location: {$home_url}admin/index.php?action=login_success");
		echo "<script> location.replace(\"admin/index.php?action=login_success\"); </script>";
	}
	else
	{
		//header("Location: {$home_url}index.php?action=login_success");
		echo "<script> location.replace(\"index.php?action=login_success\"); </script>";
	}
}
else
{
	$access_denied=true;
}


