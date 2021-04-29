<?php

error_reporting(E_ALL);
 
session_start();

date_default_timezone_set('Europe/Rome');
 
// home page url
$home_url="http://localhost/project/";
 
// default page 1
$page = $_GET['page'] ?? 1;

$records_per_page = 5;
 
// calcola la clausola LIMIT delle query
$from_record_num = ($records_per_page * $page) - $records_per_page;
