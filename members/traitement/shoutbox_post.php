<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");

    // code source de connexion.php
    include("../../common/db_connect.php");

    $username = mysqli_real_escape_string($db, $_SESSION["username"]);
    $message = mysqli_real_escape_string($db, $_POST['InputMessage']);

    if(!empty($message) and !empty($username))
    {  
        if ($message != "")
        {
            // Registering Message
            $query = "INSERT INTO generalChat(message_sender, message_date, message_text) VALUES(".$_SESSION['user_id'].", '".date("Y/m/d H:i:s")."', '".$message."' )";

            if ($db->query($query) === TRUE) {
                echo "Enregistrement: Ok";
            } else {
                echo "Error: <br><br>" . $query . "<br><br>" . $db->error;
            }
        }
    }


    echo "<script type='text/javascript'>document.location.replace('../shoutbox.php');</script>";
?>