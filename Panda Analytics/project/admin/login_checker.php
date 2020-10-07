<?php
// login checker per admin
 

if(empty($_SESSION['logged_in']))
	{
		header("Location: {$home_url}login.php?action=not_yet_logged_in");
	}
else if($_SESSION['access_level']!="admin")
	{
		header("Location: {$home_url}login.php?action=not_admin");
	}
else
	{
    // no problemo
	}
?>