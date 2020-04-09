<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Login</title>
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
		<form action="login_traitement.php" method="post">
			<div class="form-group">
			    <label for="InputLogin">Login</label>
			    <input type="text" name="InputLogin" class="form-control" placeholder="Enter your Nickname" autocomplete="off">
			</div>
		  	<div class="form-group">
		    	<label for="InputPassword">Password</label>
		    	<input type="password" name="InputPassword" class="form-control" placeholder="Enter your Password" autocomplete="off">
		  	</div>
		  	<button type="submit" class="btn btn-primary">Click to Login</button>
			<a href="register.php"><small id="clickRegister" class="form-text text-muted">Switch to Register page</small></a>
		</form>
	</div>
</body>
</html>