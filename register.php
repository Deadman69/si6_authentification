<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Register</title>
	<link rel="icon" href="pics/favicon.ico" type="image/x-icon">
</head>
<body>
	<div class="col-md-4 offset-md-4">
		<br>
		<br>
		<span style="color: red; text-align: center; font-weight: bold;">
			<?php
				if (isset($_COOKIE["ErrorMessage"]))
				{
					echo $_COOKIE["ErrorMessage"];
					setcookie("ErrorMessage", "", time() - 3600);
				}
			?>
		</span>
		<span style="color: green; text-align: center; font-weight: bold;">
			<?php
				if (isset($_COOKIE["SuccessMessage"]))
				{
					echo $_COOKIE["SuccessMessage"];
					setcookie("SuccessMessage", "", time() - 3600);
				}
			?>
		</span>
		<form action="register_traitement.php" method="post">
			<div class="form-group">
			    <label for="InputToken">Registration Token</label>
			    <input type="text" name="InputToken" class="form-control" id="InputToken" placeholder="Enter Token" autocomplete="off">
			    <small id="tokenHelp" class="form-text text-muted">Contact an admin to get one</small>
			</div>
			<div class="form-group">
			    <label for="InputNickname">Nickname</label>
			    <input type="text" name="InputNickname" class="form-control" id="InputNickname" placeholder="Enter nickname" autocomplete="off">
			    <small id="emailHelp" class="form-text text-muted">Just to identify you !</small>
			</div>
		  	<div class="form-group">
		    	<label for="InputPassword">Password</label>
		    	<input type="password" name="InputPassword" class="form-control" id="InputPassword" placeholder="Password" autocomplete="off">
		    	<small id="passwordHelp" class="form-text text-muted">We are going to encrypt it, don't worry !</small>
		  	</div>
		  	<div class="form-group">
		    	<label for="InputPasswordVerif">Password Confirmation</label>
		    	<input type="password" name="InputPasswordVerif" class="form-control" id="InputPasswordVerif" placeholder="Password verification" autocomplete="off">
		  	</div>
		  	<button type="submit" class="btn btn-primary">Click to Register</button>
		  	<a href="index.php"><small id="clickLogin" class="form-text text-muted">Switch to Login page</small></a>
		</form>
	</div>
</body>
</html>