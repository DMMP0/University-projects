<?php

include_once "config/core.php";
$page_title = "Forgot Password";
include_once "login_checker.php";
include_once "config/database.php";
include_once 'objects/user.php';
include_once "libs/php/utils.php";
 

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$utils = new Utils();

include_once "layout_head.php";
 

if($_POST)
{
 
    echo "<div class='col-sm-12'>";
 
   
    $user->email=$_POST['email'];
 
    if($user->emailExists())
	{
 
        $access_code=$utils->getToken();
 
        $user->access_code=$access_code;
        if($user->updateAccessCode())
		{
 
            // send reset link
            $body="Hi there.<br /><br />";
            $body.="Please click the following link to reset your password: {$home_url}reset_password/?access_code={$access_code}";
            $subject="Reset Password";
            $send_to_email=$_POST['email'];
 
            if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body))
			{
                echo "<div class='alert alert-info'>
                            Password reset link was sent to your email.
                            Click that link to reset your password.
                        </div>";
            }
 
             
            else
				echo "<div class='alert alert-danger'>ERROR: Unable to send reset link.</div>"; 
        }
 
          
        else 
			echo "<div class='alert alert-danger'>ERROR: Unable to update access code.</div>"; 
 
        }
 
    else
		echo "<div class='alert alert-danger'>Your email cannot be found.</div>"; 
 
    echo "</div>";
}
 

echo "<div class='col-md-4'></div>";
echo "<div class='col-md-4'>";
 
echo "<div class='account-wall'>
        <div id='my-tab-content' class='tab-content'>
            <div class='tab-pane active' id='login'>
                <img alt='profile image' class='profile-img' src='images/login-icon.png'>
                <form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                    <input type='email' name='email' class='form-control' placeholder='Your email' required autofocus>
                    <input type='submit' class='btn btn-lg btn-primary btn-block' value='Send Reset Link' style='margin-top:1em;' />
                </form>
            </div>
        </div>
    </div>";
 
echo "</div>";
echo "<div class='col-md-4'></div>";
 

include_once "layout_foot.php";
