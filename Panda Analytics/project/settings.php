<?php

include_once "config/core.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/database.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/config/pbkdf2.php";
include_once "$_SERVER[DOCUMENT_ROOT]/project/objects/transaction.php";

$database = new Database();
$db = $database->getConnection();
$t = new TBoh($db);
// var_dump($_SESSION);

$id= $_SESSION['user_id'];



if(isset($_GET['action']))
{
    if($_GET['action']=="updated")
    {
		$q="select salt from utente where id=:id";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->bindParam(':id', $id);
		$stmt1->execute();
		$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
		extract($row1);

		$n=$_POST['name'];
		$p=$_POST['password'];
		$m=$_POST['mail'];
		$pa=pbkdf2('sha3-512' , $p,$salt,1000,100);
		
	   



		$q="update utente set nome=:n,password=:p,mail=:m where id=:id";
		$stmt1 = $t->conn->prepare($q);
		$stmt1->bindParam(':n', $n);
		$stmt1->bindParam(':p', $pa);
		$stmt1->bindParam(':m', $m);
		$stmt1->bindParam(':id', $id);
		$stmt1->execute();

		$_SESSION['username']=$n;

		$_SESSION['mail']= $m ;
    }
}
else
{
    $q="select mail from utente where id=:id";
    $stmt1 = $t->conn->prepare($q);
    $stmt1->bindParam(':id', $id);
    $stmt1->execute();
    $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    extract($row1);

    $n=$_SESSION['username'];
    $p=$_SESSION['Login_password'];
    $m= $mail;
}

$page_title="Edit Profile";
$require_login=true;
include_once "login_checker.php";
include_once 'layout_head.php';
 
echo "<div class='col-md-12'>";
 
  

 

echo "<div class=\"settings\">";
echo"   <section class=\"form-section settings-form-section\">
             <header>
                Impostazioni utente
            </header>
            <form name=\"settings_form\" method=\"post\" action=\"settings.php?action=updated\" class=\"std-form settings-form form-area\" id=\"settings_form\">
                 <div class=\"fields\">
                  <p><label for=\"settings_form_name\" ><span class=\"label-text\">Nome</span><input type=\"text\" value=\"$n\" id=\"name\" name=\"name\" required=\"required\" tabindex=\"1\" class=\"validated-input\"></label></p>
                  <p><label for=\"settings_form_password\" class=\"required\"><span class=\"label-text\">Password</span><input value=\"$p\"  id=\"password\" name=\"password\" required=\"required\" tabindex=\"2\" class=\"validated-input\"></label></p>".  //TODO occhio per nascondere la password
                  "<p><label for=\"settings_form_mail\" class=\"required\"><span class=\"label-text\">Email</span><input type=\"text\" value=\"$m\" id=\"mail\" name=\"mail\" required=\"required\" tabindex=\"1\" pattern=\"([a-zA-Z0-9\-_]{3,29})|(.+@.+\..+)\" data-pattern-mismatch-message=\"Utilizza un indirizzo email valido.\" class=\"validated-input\"></label></p>
                 </div>
                 <button id=\"settings_submit_button\" class=\"action type-primary\" type=\"submit\">
                 Salva
                  </button>
             </form>
         <footer>
         </footer>
      </section>";

// var_dump($_SESSION);
echo "</div>";
 
echo "</div>";
 
// footer HTML and JavaScript codes
include 'layout_foot.php';
?>