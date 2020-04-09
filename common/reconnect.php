<?php 
	session_start();
	// On re-défini les infos de la session au cas ou on change des choses sur l'utilisateur

    include("db_connect.php");




    $tempsDeconexion = 600;

    if (!isset($_SESSION['CSRF_create_date']))
        echo "<script type='text/javascript'>document.location.replace('../members/disconnect.php');</script>";

    if($_SESSION['CSRF_create_date'] + $tempsDeconexion <= time() )
    {
        echo "<script type='text/javascript'>document.location.replace('../members/disconnect.php');</script>";
    }
    else // if CSRF is not expired
    {
        $query = "SELECT member_id, member_login, member_pseudo, member_password, member_registrationIpAddr, member_registrationDate, member_registrationMac, member_registrationToken, member_lastIpAddr, member_lastDateConnection, member_lastMac, member_access, access.access_name  FROM members JOIN access ON member_access = access.access_id WHERE member_id = '".$_SESSION['user_id']."' ";
        $rs = mysqli_query($db, $query);

        if(mysqli_num_rows($rs) == 1)
        {
        	$user = mysqli_fetch_assoc($rs);
    		$_SESSION['username'] = $user['member_login']; // admin
            $_SESSION['pseudo'] = $user['member_pseudo']; // Administrateur
    		$_SESSION['user_id'] = $user['member_id']; // 1
    		$_SESSION['user_lastIpAddr'] = $user['member_lastIpAddr']; // localhost OR IPV4
    		$_SESSION['user_lastMac'] = $user['member_lastMac']; // localmac OR MAC ADDREESS
    		$_SESSION['user_rank'] = $user['access_name']; // admin
            $_SESSION['CSRF_create_date'] = time();
    	}


    	if(empty($_SESSION["username"]))
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

            $query = "INSERT INTO pageForbidden(failed_login, failed_request, failed_ip, failed_date, failed_mac, failed_error) VALUES('anonymous', '".$link."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '02x0003');";
            $result = mysqli_query($db, $query);
            echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
        }
    }
?>