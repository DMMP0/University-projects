<?php

include_once "../config/core.php";
include_once "login_checker.php";
$page_title="Admin Index";
include 'layout_head.php';
 
echo "<div class='col-md-12'>";
 
$action = $_GET['action'] ?? "";
if($action=='already_logged_in')
	{
        echo "<div class='alert alert-info'>";
        echo "<strong>You</strong> are already logged in.";
        echo "</div>";
    }
	else if($action=='logged_in_as_admin')
	{
        echo "<div class='alert alert-info'>";
        echo "<strong>You</strong> are logged in as admin.";
        echo "</div>";
    }
	echo "<div class='alert alert-info'>";
    echo "Login effettuato come admin.";
    echo "</div>";
    echo "</div>";
 
include_once 'layout_foot.php';
