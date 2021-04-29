<?php


if(isset($_SESSION['access_level']) && $_SESSION['access_level']=="Admin")
{
    header("Location: {$home_url}admin/index.php?action=logged_in_as_admin");
}
else if(isset($require_login) && $require_login==true)
{
    //qui ridireziona
    if(!isset($_SESSION['access_level']))
	{
        header("Location: {$home_url}login&register.php?action=please_login");
    }
}
 
// se si entra nella pagina di login già loggati ridireziona a index.php
else if(isset($page_title) && ($page_title=="login&register"))
{

    if(isset($_SESSION['access_level']) && $_SESSION['access_level']=="user"){
        header("Location: {$home_url}index.php?action=already_logged_in");
    }
}
else
{
    // no problem boi
}

