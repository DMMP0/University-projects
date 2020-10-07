<?php
include_once "../config/core.php";
include_once "login_checker.php";
include_once '../config/database.php';
include_once '../objects/flow.php';

$database = new Database();
$db = $database->getConnection();
$flow = new Flow($db);
$id=$_GET['id'];
$nome=$_GET['n'];
$uid=$_GET['uid'];
if(isset($_GET['uid']))
	$uid=$_GET['uid'];

$page_title = "Edit Flow";
include_once "layout_head.php";


echo "<div>";
echo 	"<form name=\"form\" method=\"post\" action=\"read_flows.php?action=flow_updated\" > ";
//echo 		"<input type=\"hidden\" name=\"new\" value="1" />
echo 		"<input name=\"id\" type=\"hidden\" value=\"$id\" />";
echo 		"<p>NOME: <input type=\"text\" name=\"name\" value=\"$nome\"  /></p>";
echo 		"<p>DMI: <input type=\"text\" name=\"dmi\" value=\"\"  /></p>";
echo 		"<p>Primo anno?: <input type=\"text\" name=\"status\" value=\"\"  /></p>";
echo 		"<p>ID UTENTE: <input type=\"number\" name=\"uid\" value=\"$uid\" /></p> ";
echo 		"<p><input name=\"submit\" type=\"submit\" value=\"Update\" /></p>";
echo "	</form>";
echo "</div>";

include_once "layout_foot.php";
?>