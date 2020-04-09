<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");

    // code source de connexion.php
    include("../../common/db_connect.php");

    $uid = mysqli_real_escape_string($db, $_SESSION["user_id"]);
    $oldPassword = mysqli_real_escape_string($db, $_POST["InputActualPassword"]);
    $newPassword = mysqli_real_escape_string($db, $_POST["InputPassword"]);
    $newPasswordVerif = mysqli_real_escape_string($db, $_POST["InputPasswordVerif"]);

    if(!empty($oldPassword) and !empty($newPassword) and !empty($newPasswordVerif))
    { 
        $query = "SELECT member_id, member_login, member_password FROM members WHERE member_id = '".$uid."' ";
        $result = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($result);

        if(password_verify($oldPassword, $user['member_password']))
        {
            if ($newPassword == $newPasswordVerif)
            {
                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $query = "UPDATE members SET member_password = '".$newPassword."' WHERE member_id = '".$uid."' ";
                $result = mysqli_query($db, $query);

                setcookie("SuccessMessage", "Your new password has been applied !", time()+30);
            }
            else
            {
                $query = "INSERT INTO failedLogin(failed_Login, failed_password, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$username."', '".$password."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '03x0002');";
                $result = mysqli_query($db, $query);

                setcookie("ErrorMessage", "ERROR: 03x0002 - Your new password don't match with the verification, operation aborted !", time()+30);
            }
        }
        else
        {
            $query = "INSERT INTO failedLogin(failed_Login, failed_password, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$username."', '".$password."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '03x0001');";
            $result = mysqli_query($db, $query);

            setcookie("ErrorMessage", "ERROR: 03x0001 - Your actual password don't match, operation aborted !", time()+30);
        }
    }
    else
    {
        $query = "INSERT INTO failedLogin(failed_Login, failed_password, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$username."', '".$password."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '03x0003');";
        $result = mysqli_query($db, $query);

        setcookie("ErrorMessage", "ERROR: 03x0003 - At least one field is empty !", time()+30);
    }
    

    echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
?>