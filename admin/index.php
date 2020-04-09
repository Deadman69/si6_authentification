<?php
	// db_connect.php already included in reconnect.php
	include("../common/reconnect.php");
	include("../common/check_ban.php");
	include("../common/check_mod.php");
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
	    <div class="col-md-2 offset-md-1">
	        <br>
	        <h3>Rename a member</h3>
	        <form action="traitement/rename.php" method="post">
	            <div class="form-group">
					<SELECT name="InputMember" size="1" class="form-control" >
						<OPTION selected>Select a member</OPTION>
						<?php
							$reponse = $bddPDO->prepare('SELECT member_id, member_login, member_pseudo FROM members');
							$reponse->execute();

							while ($donnees = $reponse->fetch())
							{
								echo '<OPTION>'.$donnees['member_login'].' - '.$donnees['member_pseudo'].'</OPTION>';
							}
						?>
					</SELECT>
	                <label for="InputNewName">Rename a member</label>
	                <input type="text" name="InputNewName" class="form-control" placeholder="Enter the new name" autofocus autocomplete="off">
	            </div>
	            <button type="submit" class="btn btn-primary">Change</button>
	        </form>
	        <br>
	    </div>
		<div class="col-md-3 offset-md-1">
	        <br>
	        <h3>Send Fake Message</h3>
	        <form action="traitement/fakeMessage.php" method="post">
	            <div class="form-group">
					<SELECT name="InputMember" size="1" class="form-control" >
						<OPTION selected>Select a member</OPTION>
						<?php
							$reponse = $bddPDO->prepare('SELECT member_id, member_login, member_pseudo FROM members');
							$reponse->execute();

							while ($donnees = $reponse->fetch())
							{
								echo '<OPTION>'.$donnees['member_login'].' - '.$donnees['member_pseudo'].'</OPTION>';
							}
						?>
					</SELECT>
	                <label for="InputMessage">Message</label>
	                <input type="text" name="InputMessage" class="form-control" placeholder="Enter your message, sender will be the choosen one" autofocus autocomplete="off">
	            </div>
	            <button type="submit" class="btn btn-primary">Send</button>
	        </form>
	        <br>
	    </div>
	    <div class="col-md-3 offset-md-1">
	        <br>
	        <h3>Delete mass messages from shoutbox</h3>
	        <form action="traitement/removeShoutbox.php" method="post">
	            <div class="form-group">
	                <label for="InputNumber">Number to delete</label>
	                <input type="text" name="InputNumber" class="form-control" placeholder="Enter the number of messages to delete" autofocus autocomplete="off">
	            </div>
	            <button type="submit" class="btn btn-primary">Delete</button>
	        </form>
	        <br>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-2 offset-md-1">
	        <br>
	        <h3>Delete all messages from user</h3>
	        <form action="traitement/deleteAll.php" method="post">
	            <div class="form-group">
					<SELECT name="InputMember" size="1" class="form-control" >
						<OPTION selected>Select a member</OPTION>
						<?php
							$reponse = $bddPDO->prepare('SELECT member_id, member_login, member_pseudo FROM members');
							$reponse->execute();

							while ($donnees = $reponse->fetch())
							{
								echo '<OPTION>'.$donnees['member_login'].' - '.$donnees['member_pseudo'].'</OPTION>';
							}
						?>
					</SELECT>
	            </div>
	            <button type="submit" class="btn btn-primary">Delete</button>
	        </form>
	        <br>
	    </div>
		<div class="col-md-3 offset-md-1">
	        <br>
	        <h3>Add New Token</h3>
	        <form action="traitement/tokenAdd.php" method="post">
	            <div class="form-group">
					<label for="InputToken">Token Value</label>
	                <input type="text" name="InputToken" class="form-control" placeholder="Let empty to create a random one" autofocus autocomplete="off">
					<label for="InputTokenNumber">Number to create</label>
	                <input type="text" name="InputTokenNumber" class="form-control" placeholder="Enter number to create" autofocus autocomplete="off">
	                <small id="clickRegister" class="form-text text-muted">Not available if you had let the upper value empty. Anyway, it will expire in 7 days</small>
	            </div>
	            <button type="submit" class="btn btn-primary">Add</button>
	        </form>
	        <br>
	    </div>
	    <div class="col-md-3 offset-md-1">
	        <br>
	        <h3>Change rank of user</h3>
	        <form action="traitement/changeRank.php" method="post">
	            <div class="form-group">
					<SELECT name="InputMember" size="1" class="form-control" >
						<OPTION selected>Select a member</OPTION>
						<?php
							$reponse = $bddPDO->prepare('SELECT member_id, member_login, member_pseudo FROM members');
							$reponse->execute();

							while ($donnees = $reponse->fetch())
							{
								echo '<OPTION>'.$donnees['member_login'].' - '.$donnees['member_pseudo'].'</OPTION>';
							}
						?>
					</SELECT><br>
					<SELECT name="InputRank" size="1" class="form-control" >
						<OPTION selected>Select a rank</OPTION>
						<?php
							$reponse = $bddPDO->prepare('SELECT access_name FROM access');
							$reponse->execute();

							while ($donnees = $reponse->fetch())
							{
								echo '<OPTION>'.$donnees['access_name'].'</OPTION>';
							}
						?>
					</SELECT>
	            </div>
	            <button type="submit" class="btn btn-primary">Send</button>
	        </form>
	        <br>
	    </div>
	</div>
</body>
</html>