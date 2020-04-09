<?php
	session_start();
	$_SESSION = array(); // Détruit toutes les valeurs de session


    // code source de connexion.php
	include("common/db_connect.php");


	$mac = exec('getmac'); 
	$mac = strtok($mac, ' '); 

	// Si l'IP est locale, on définis l'IP sur 'localhost'
   	$ip = $_SERVER['REMOTE_ADDR'];
    if($ip = "::1")
    {
    	$ip = 'localhost';
    }



	if(!empty($_POST['InputLogin']) && !empty($_POST['InputPassword']))
	{
		// $db correspond à la connexion à la base de données (voir mysqli_connect)
		$username = mysqli_real_escape_string($db, $_POST['InputLogin']);
		$password = mysqli_real_escape_string($db, $_POST['InputPassword']);




	    $query = "SELECT member_id, member_login, member_pseudo, member_password, member_registrationIpAddr, member_registrationDate, member_registrationMac, member_registrationToken, member_lastIpAddr, member_lastDateConnection, member_lastMac, member_access, access.access_name  FROM members JOIN access ON member_access = access.access_id WHERE member_login = '".$username."' ";
	    $result = mysqli_query($db, $query);


	    if(mysqli_num_rows($result) == 1)
	    {
	    	$user = mysqli_fetch_assoc($result);
	    	if(password_verify($password, $user['member_password']))
	    	{
				$_SESSION['username'] = $user['member_login']; // admin
				$_SESSION['pseudo'] = $user['member_pseudo']; // Administarteur
				$_SESSION['user_id'] = $user['member_id']; // 1
				$_SESSION['user_lastIpAddr'] = $user['member_lastIpAddr']; // localhost OR IPV4
				$_SESSION['user_lastMac'] = $user['member_lastMac']; // localmac OR MAC ADDREESS
				$_SESSION['user_rank'] = $user['access_name']; // admin
				$_SESSION['CSRF_create_date'] = time();

			    // Update de la dernière IP de connection
				$query = "UPDATE members SET member_lastIpAddr = '".$ip."', member_lastDateConnection = '".date("Y-m-d H:i:s", time())."', member_lastMac = '".$mac."' WHERE member_id = ".$user['member_id']." ";
				$result = mysqli_query($db, $query);


				$query = "INSERT INTO successLogin(success_login, success_ip, success_date, success_mac) VALUES('".$_SESSION['user_id']."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."');";
				$result = mysqli_query($db, $query);


		        echo "<script type='text/javascript'>document.location.replace('members/index.php');</script>";
	    	}
	    	else
	    	{
	    		// HERE PASSWORD IS NOT CRYPTED
			    $query = "INSERT INTO failedLogin(failed_Login, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$username."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '01x0001');";
			    $result = mysqli_query($db, $query);

	    		setcookie("ErrorMessage", "ERROR: 01x0001 - Username or Password incorrect !", time()+30);
	        	echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
	    	}
	    }
	    else
	    {
	    	// HERE PASSWORD IS NOT CRYPTED
		    $query = "INSERT INTO failedLogin(failed_Login, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$username."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '01x0002');";
		    $result = mysqli_query($db, $query);

	        setcookie("ErrorMessage", "ERROR: 01x0002 - Username or Password incorrect !", time()+30);
	        echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
	    }

	    mysqli_close($db);
	}
	else
	{
		$username = "";
		if (empty($_POST['InputLogin']))
			$username = "";
		else
			$username = mysqli_real_escape_string($db, $_POST['InputLogin']);

		$password = "";
		if (empty($_POST['InputPassword']))
			$password = "";
		else
			$password = mysqli_real_escape_string($db, $_POST['InputPassword']);


	    $query = "INSERT INTO failedLogin(failed_Login, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$username."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '00x0006');";
	    $result = mysqli_query($db, $query);

        setcookie("ErrorMessage", "ERROR: 00x0006 - At least one field is empty !", time()+30);
		echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
	}
?>