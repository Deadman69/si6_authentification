<?php
	// DOIT ETRE APPELLER APRES UN RECONNECT !!!!

	$tempsDeconection = 20;
	if($_SESSION['create_date'] + $tempsDeconection <= time() )
	{
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}

		// Finalement, on détruit la session.
		session_destroy();


		setcookie("ErrorMessage", "Vous êtes rester inactif trop longtemps !");
		echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
	}
?>