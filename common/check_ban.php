<?php
	if($_SESSION['user_rank'] == "banned")
	{
		// Getting the URL requested
		$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']; 

		// Getting MAC address of client
		$mac = exec('getmac'); 
		$mac = strtok($mac, ' '); 

		// Si l'IP est locale, on définis l'IP sur 'localhost'
	   	$ip = $_SERVER['REMOTE_ADDR'];
	    if($ip = "::1")
	    {
	    	$ip = 'localhost';
	    }

	    // if user is connected we could retrieve him
	    $username = "";
	    if (isset($_SESSION['user_id']))
	    	$username = $_SESSION['user_id'];
	    else
	    	$username = "anonymous";


		$query = "INSERT INTO pageForbidden(failed_login, failed_request, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$username."', '".$link."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '02x0004');";
	    $result = mysqli_query($db, $query);
		echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
	}
?>