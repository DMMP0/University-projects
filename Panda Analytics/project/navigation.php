<!-- navbar -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">


<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">

        <div class="navbar-header">
            <!-- to enable navigation dropdown when viewed in mobile device -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>



			<ul class="nav navbar-nav">
				<li class="nav-item active" <?php echo $page_title=="Home" ? "class='active'" : ""; ?>>
					<a class="nav-link" href="<?php echo $home_url."?h=0"; ?>"><strong>Panda Analytics</strong>
					<img  alt="icon" src=<?php  echo "\"http://localhost/project/img/panda.svg\""?> width="35" height="35" class="d-inline-block align-top" >
					</a>
				</li>
			</ul>


        </div>

        <div class="navbar-collapse collapse">


			<?php
            // check if users / customer was logged in
			// if user was logged in, show "Edit Profile" and "Logout" options
			if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true && $_SESSION['access_level']=='user'){
			?>
			<ul class="nav navbar-nav navbar-right">
				<li <?php echo $page_title=="Edit Profile" ? "class='active'" : ""; ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
					&nbsp;&nbsp;<?php echo $_SESSION['username']; ?>
					&nbsp;&nbsp;<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $home_url; ?>settings.php">Impostazioni</a></li>
           				<li><a href="<?php echo $home_url; ?>logout.php">Logout</a></li>
       				</ul>
   				</li>
			</ul>
			<?php
			}

			// if user was not logged in, show the "login" and "register" options
			
            ?>
        </div> <!--/.nav-collapse-->

    </div>
</div>
<!-- /navbar -->
