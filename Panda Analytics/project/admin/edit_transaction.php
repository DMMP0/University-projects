<?php
include_once "../config/core.php";
include_once "login_checker.php";
include_once '../config/database.php';
include_once '../objects/transaction.php';

$database = new Database();
$db = $database->getConnection();
$id=$_GET['id'];
$amm=$_GET['amm'];
$y=$_GET['y'];
//$ra=$_GET['ra'];
$m=$_GET['m'];
if($m=="/")
	$transaction = new TPrecisa($db);
else
	$transaction = new TBoh($db);
if(isset($_GET['dp']))
	$dp=$_GET['dp'];
$fid=$_GET['fid'];

$page_title = "Edit transaction";
include_once "layout_head.php";


echo "<div>";
echo 	"<form name=\"form\" method=\"post\" action=\"read_transactions.php?action=transaction_updated\" > ";

echo 		"<input name=\"id\" type=\"hidden\" value=\"$id\" />";
echo 		"<p>TIPO : <select name=\"tipo\">
				<option value=111 selected>Depositi bancari e postali</option>
				<option value=112>Denaro e valori in cassa</option>
				<option value=121 >Crediti verso i clienti</option>
				<option value=122>Ratei e risconti</option>
				<option value=131 >Materie prime, sussidiarie e di consumo</option>
				<option value=132>Prodotti in lavorazione</option>
				<option value=133 >Prodotti fini e merci</option>
				<option value=211>Diritti di brevetto industriale e di utilizzatore</option>
				<option value=221 >Terreni e fabbricati</option>
				<option value=222>Attrezzature industriali e commerciali</option>
				<option value=223 >Altri beni</option>
				<option value=231>Crediti verso i clienti</option>
				<option value=311 >Fondi rischi e oneri</option>
				<option value=312>Debiti tfr(Breve)</option>
				<option value=313 >Obbligazioni(breve)</option>
				<option value=314>Debiti verso banche(breve)</option>
				<option value=315 >Debiti verso fornitori</option>
				<option value=316>Debiti tributari</option>
				<option value=317 >Debiti verso istituti di previdenza</option>
				<option value=318>Ratei e risconti</option>
				<option value=321 >Debiti per tfr(lunga)</option>
				<option value=322>Obbligazioni(lunga)</option>
				<option value=323 >Debiti verso banche(lunga)</option>
				<option value=410>Capitale sociale</option>
				<option value=421 >Riserva legale</option>
				<option value=422>Riserva straordinaria</option>
				<option value=423 >utile(perdita) dell' esercizio - dividendi</option>
			</select></p>";
echo 		"<p>MACROAREA : <select name=\"ma\">
				<option value=0 selected>Fonti</option>
				<option value=1>Impieghi</option>
			</select></p>";
echo 		"<p>AMMONTARE : <input type=\"number\" name=\"amm\" value=\"$amm\" /></p> ";
echo 		"<p>ANNO : <input type=\"year\" name=\"y\" value=\"$y\" /></p> ";
echo 		"<p>RIPETIZIONI ANNUALI : <input type=\"number\" name=\"ra\" value=\"0\" /></p> ";
if($m=="/")
	echo 	"<p>DATA : <input type=\"date\" name=\"dp\" value=\"$dp\" /></p> ";
else
	echo 	"<p>MESE: <input type=\"number\" name=\"m\" value=\"$m\" /></p> ";
echo 		"<p>ID FLOW : <input type=\"number\" name=\"fid\" value=\"$fid\" /></p> ";
echo 		"<p><input name=\"submit\" type=\"submit\" value=\"Update\" /></p>";
echo "	</form>";
echo "</div>";

include_once "layout_foot.php";
?>