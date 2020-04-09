<?php
    // code source de connexion.php
    include("common/db_connect.php");

	$mac = exec('getmac'); 
	$mac = strtok($mac, ' '); 

    $ip = $_SERVER['REMOTE_ADDR'];
    if($ip = "::1")
    {
    	$ip = '192.168.0.0';
    }


	if(!empty($_POST['InputNickname']) && !empty($_POST['InputToken']) && !empty($_POST['InputPassword']) && !empty($_POST['InputPasswordVerif']))
	{

		$caracteresInterdit = [" ", "!", ";", ",", ":", "/", "^", "$", "*", "(", ")", "[", "]", "{", "}", "?", "~", "|", "`", "#"];
		$countInterdits = 0;
		foreach ($caracteresInterdit as $caractere) {
			if (strpos($_POST['InputNickname'], $caractere) == true)
			{
				$countInterdits = $countInterdits + 1;
			}
		}
		if ($countInterdits == 0)
		{
			if ($_POST['InputPassword'] == $_POST['InputPasswordVerif'])
			{
				// $db correspond à la connexion à la base de données (voir mysqli_connect)

				$login = mysqli_real_escape_string($db, $_POST['InputNickname']);
				$token = mysqli_real_escape_string($db, $_POST['InputToken']);
				$password = mysqli_real_escape_string($db, $_POST['InputPassword']);
				$passwordVerify = mysqli_real_escape_string($db, $_POST['InputPasswordVerif']);

			    $pass_hache = password_hash($password, PASSWORD_DEFAULT);



			    // Checking if user is already registered
			    $query = "SELECT COUNT(member_id) as total FROM members WHERE member_login = '".$login."' ";
			    $result = mysqli_query($db, $query);
			   	$data=mysqli_fetch_assoc($result);
				if( $data['total'] == 0) // If == 0, then can register
				{
					// CHECK TOKEN TODO
					$canRegisterToken = false;
					$tokenID = 0;
					$promoteTo = 1;

					include("common/db_connect_pdo.php");

				    $reponse = $bddPDO->query("SELECT token_id, token_value, token_expireDate, token_used, token_promoteTo FROM tokens WHERE token_value = '".$token."' ");
				    while ($donnees = $reponse->fetch())
				    {
				    	// Si la date d'expiration est supérieure à la date actuelle (ce qu'on veux vérifier)
				    	if ($donnees['token_value'] == $token && $donnees['token_expireDate'] > date("Y-m-d H:i:s") && $donnees['token_used'] == 0)
				    	{
				    		$canRegisterToken = true;
				    		$tokenID = $donnees['token_id'];
				    		$promoteTo = $donnees['token_promoteTo'];

				    		// Updating tokens
				    		$query = "UPDATE tokens SET token_used = 1, token_usedDate  = '".date("Y-m-d H:i:s", time())."' WHERE token_id = ".$donnees['token_id']." ";
			    			$result = mysqli_query($db, $query);
				    	}
				    }
				    $reponse->closeCursor();




				    // Registering if we can with Token
				    if ($canRegisterToken)
				    {
						// Registering user
					    $query = "INSERT INTO members( member_login, member_pseudo, member_password, member_registrationIpAddr, member_registrationDate, member_registrationMac, member_registrationToken, member_access) VALUES('".$login."', '".$login."', '".$pass_hache."', '".$ip."', '".date("Y/m/d H:i:s")."', '".$mac."', '".$tokenID."', '".$promoteTo."') ";

						if ($db->query($query) === TRUE) 
						{
						    setcookie("SuccessMessage", "Registering successfull ! You can now login to our system.", time()+15);
						    echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
						} else {
						    echo "Error: <br><br>" . $query . "<br><br>" . $db->error;
						}

						$db->close();
					}
					else
					{
					    $query = "INSERT INTO failedRegister(failed_login, failed_ip, failed_date, failed_mac, failed_tokenValue, failed_error) VALUES('".$login."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '".$token."', '00x0003');";
					    $result = mysqli_query($db, $query);

					    	

						setcookie("ErrorMessage", "ERROR: 00x0003 - Token invalid !", time()+15);
						echo "<script type='text/javascript'>document.location.replace('register.php');</script>";
					}
				}
				else
				{
					$query = "INSERT INTO failedRegister(failed_login, failed_ip, failed_date, failed_mac, failed_tokenValue,failed_error) VALUES('".$login."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '".$token."', '00x0001');";
				    $result = mysqli_query($db, $query);


					setcookie("ErrorMessage", "ERROR: 00x0001 - Username already used !", time()+15);
					echo "<script type='text/javascript'>document.location.replace('register.php');</script>";
				}
			}
			else
			{
				$query = "INSERT INTO failedRegister(failed_login, failed_ip, failed_date, failed_mac, failed_tokenValue,failed_error) VALUES('".$login."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '".$token."', '00x0005');";
			    $result = mysqli_query($db, $query);


				setcookie("ErrorMessage", "ERROR: 00x0005 - Password mismatch !", time()+15);
				echo "<script type='text/javascript'>document.location.replace('register.php');</script>";
			}
		}
		else
		{
			$query = "INSERT INTO failedRegister(failed_login, failed_ip, failed_date, failed_mac, failed_tokenValue,failed_error) VALUES('".$login."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '".$token."', '00x0007');";
		    $result = mysqli_query($db, $query);


	        setcookie("ErrorMessage", "ERROR: 00x0007 - You have forbidden caracters in your name !", time()+15);
	        echo "<script type='text/javascript'>document.location.replace('register.php');</script>";
		}
	}
	else
	{
		$query = "INSERT INTO failedRegister(failed_login, failed_ip, failed_date, failed_mac, failed_tokenValue,failed_error) VALUES('".$login."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '".$token."', '00x0006');";
	    $result = mysqli_query($db, $query);

	    

        setcookie("ErrorMessage", "ERROR: 00x0006 - At least one field is not filled !", time()+15);
        echo "<script type='text/javascript'>document.location.replace('register.php');</script>";
	}
?>