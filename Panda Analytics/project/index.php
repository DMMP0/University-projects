<?php

include_once "config/core.php";
$action = '';
$page_title="Home";
$require_login=true;
include_once "login_checker.php";
include_once 'layout_head.php';
 if(isset($_GET['t'])&& $_GET['t']==0)
 {
	 $_SESSION['current_flow_id'] = 0;
	  //header("Location: {$home_url}index.php");
	  echo "<script> location.replace(\"index.php\"); </script>";
 }
  if(isset($_GET['h'])&& $_GET['h']==0)
 {
	
	  //header("Location: {$home_url}index.php");
	  echo "<script> location.replace(\"index.php\"); </script>";
 }
echo "<div class='col-md-12'>";
 
    // per prevenire undefined index notice
    $action = $_GET['action'] ?? "";
	
    if($action=='login_success')
	{
        echo "<div class='alert alert-info'>";
            echo "<strong>Bentornato " . $_SESSION['username'] . "!</strong>";
        echo "</div>";
    }
    else if($action=='already_logged_in')
	{
        echo "<div class='alert alert-info'>";
            echo "<strong>Login già effettuato .</strong>";
        echo "</div>";
    }

	

        include_once "flow.php";

 
echo "</div>";
 
// footer HTML and JavaScript codes
include 'layout_foot.php';
