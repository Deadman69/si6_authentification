<?php
	// db_connect.php already included in reconnect.php
	include("../common/reconnect.php");
	include("../common/check_ban.php");
	include("../common/db_connect_pdo.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<script src="../js/bootstrap.min.js"></script>
	<title>Index page</title>
	<link rel="icon" href="../pics/favicon.ico" type="image/x-icon">
</head>
<body>
	<?php include("../common/header.php"); ?>
	<br><br>
	<div class="row">
		<div class="col-md-2 offset-md-1">
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
	        <h3>Change your password</h3>
	        <form action="traitement/passwordChange.php" method="post">
	            <div class="form-group">
	            	<label for="InputActualPassword">Enter your actual password</label>
	                <input type="password" name="InputActualPassword" class="form-control" placeholder="Enter your actual password" autofocus autocomplete="off">
	            	<label for="InputPassword">Enter your new password</label>
	                <input type="password" name="InputPassword" class="form-control" placeholder="Enter the new password" autofocus autocomplete="off">
	                <label for="InputPasswordVerif">Confirm your new password</label>
	                <input type="password" name="InputPasswordVerif" class="form-control" placeholder="Enter the new password" autofocus autocomplete="off">
	            </div>
	            <button type="submit" class="btn btn-primary">Change</button>
	        </form>
	        <br>
	    </div>
	</div>
</body>
</html>