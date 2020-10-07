<?php
include_once "config/core.php";
include_once 'config/database.php';
include_once 'objects/user.php';
 

$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
 
if(!$user->accessCodeExists())
{
    die("ERROR: Access code not found.");
}
 
else
{
    $user->status=1;
    $user->updateStatusByAccessCode();
     
    header("Location: {$home_url}login.php?action=email_verified");
}
?>