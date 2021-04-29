<?php
// core configuration
include_once "config/core.php";
include_once "login_checker.php";
include_once 'config/database.php';
include_once 'objects/user.php';
include_once "libs/php/utils.php";

include_once "config/InputCheckFoo.php";
 
	// page title
	$page_title = "Login or Register";
 include_once "layout_head.php";
	// include il login checker
	$require_login=false;
	include_once "login_checker.php";
 
	// default: false
	$access_denied=false;
	
	

	
	
	echo "<div class='col-sm-6 col-md-4 col-md-offset-4'>";
	//messaggi vari
	$action= $_GET['action'] ?? "";
	if($action =='not_yet_logged_in')
	{
		echo "<div class='alert alert-danger margin-top-40' role='alert'>Please login.</div>";
	}
	else if($action=='please_login')
	{
		echo "<div class='alert alert-info'>
        <strong>Please login to access that page.</strong>
		</div>";
	}
	else if($action=='email_verified')
	{
		echo "<div class='alert alert-success'>
        <strong>Your email address have been validated.</strong>
		</div>";
	}
	else if($action=='success')
	{
		echo "<div class='alert alert-success'>
        <strong>Creazione utente avvenuta con successo! Effettuare il login per favore</strong>
		</div>";
	}
	if(isset($_POST['action'])&& $_POST['action']=='login')
	{
		include_once "login.php";
	}
	else if(isset($_POST['action'])&&$_POST['action']=='register')
	{
		include_once "register.php";
	}
	
	
	if($access_denied)
	{
		echo "<div class='alert alert-danger margin-top-40' role='alert'>
        Access Denied.<br /><br />
        Your username or password maybe incorrect
		</div>";
	}
	
	
	//form
	echo "<div>
			<section>
				<header>
					<h2>Accedi</h2>	
				</header>
			<form name=\"login_form\" method=\"post\" action=\"login&register.php\" id=\"login_form\">
				<div class=\"container\">
				<p><label for=\"email\" class=\"required\"><span class=\"label-text\">Email</span></p>
				<p><input type=\"text\" id=\"login_form_name\" name=\"Login_name\" required=\"required\" tabindex=\"1\" pattern=\"([a-zA-Z0-9\-_]{3,29})|(.+@.+\..+)\" data-pattern-mismatch-message=\"Utilizza un indirizzo email valido.\" class=\"validated-input\"></label></p>
				<p><label for=\"psw\" class=\"required\"><span class=\"label-text\">Password</span></p>
				<p><input type=\"password\" id=\"login_form_password\" name=\"Login_password\" required=\"required\" tabindex=\"2\" class=\"validated-input\"></label></p>
				<p><label for=\"psw\" class=\"required\"><span class=\"info forgot-password-link\"><a href=\"/forgot_password.php\" tabindex=\"3\">Hai dimenticato la password?</a></span></label></p>
				</div>
				<button id=\"login_submit_button\" class=\"action type-primary\" type=\"submit\">
				Accedi
				</button>
				<input type=\"hidden\" id=\"action\" name=\"action\" value=\"login\">
			</form>
			<footer>
			</footer>
			</section>";

	echo "<section>
			<header>
				<h2>Registrati con un Account Gratuito</h2>
			</header>
			<form name=\"signup_with_profile\" method=\"post\" action=\"login&register.php\" class=\"std-form signup-form type-with-profile form-area\" id=\"signup_form\">
				<div class=\"container\">
				<p><label for=\"signup_email\" class=\"required\"><span class=\"label-text\">Indirizzo email</span></p>
				<p><input type=\"email\" id=\"signup_with_profile_email\" name=\"Register_email\" required=\"required\" pattern=\"^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,}$\" data-pattern-mismatch-message=\"Please use a valid email address.\"></label></p>
				<p><label for=\"signup_uname\" class=\"required\"><span class=\"label-text\">Username</span></p>
				<p><input type=\"text\" id=\"signup_with_profile_username\" name=\"Register_username\" required=\"required\" minlength=\"3\" maxlength=\"30\" pattern=\"[_\-a-zA-Z0-9]{3,30}\" title=\"La lunghezza del nome utente dev'essere compresa tra 3 e 30 caratteri (sono utilizzabili solo lettere, numeri, underscore e trattino).\" data-pattern-mismatch-message=\"La lunghezza del nome utente dev'essere compresa tra 3 e 30 caratteri (sono utilizzabili solo lettere, numeri, underscore e trattino).\"></label></p>
				<p><label for=\"signup_password\" class=\"required\"><span class=\"label-text\">Password</span></p>
				<p><input type=\"password\" id=\"signup_with_profile_password\" name=\"Register_password\" required=\"required\" minlength=\"6\" pattern=\"\S{6,}\" title=\"Passwords must be at least 6 characters, but no spaces.\" data-pattern-mismatch-message=\"Passwords must be at least 6 characters, but no spaces.\"></label></p>
				</div>
				<button id=\"signup_submit_button\" class=\"action type-primary\" type=\"submit\">
				Crea account
				</button>
				<input type=\"hidden\" id=\"action\" name=\"action\" value=\"register\">
			</form>
			<footer>
				<p>
			<!--	<small>Cliccando su \"Registrati\" accetti i <a href=\"/tos\">Termini di Servizio</a> e l'<a href=\"/privacy\">Informativa sulla Privacy</a>.</small> -->
				</p>
			</footer>
		</section>
		</div>";
		
// footer HTML and JavaScript codes
include_once "layout_foot.php";
		
