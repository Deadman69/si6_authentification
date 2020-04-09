<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");
    include("../../common/check_mod.php");
    include("../../common/db_connect_pdo.php");

    $member = mysqli_real_escape_string($db, $_POST['InputMember']);
    $arrayMember = explode(" ", $member);
    $member = $arrayMember[0];

    $message = mysqli_real_escape_string($db, $_POST['InputMessage']);

    if(!empty($message) and !empty($member))
    {  
        if ($message != "")
        {
            $query = "SELECT member_id FROM members WHERE member_login = '".$member."' ";
            $result = mysqli_query($db, $query);
            $user = mysqli_fetch_assoc($result);

            // Registering Message
            $query = "INSERT INTO generalChat(message_sender, message_date, message_text) VALUES( '".$user['member_id']."', '".date("Y/m/d H:i:s")."', '".$message."' )";


            if ($db->query($query) === TRUE) {
                echo "Enregistrement: Ok";
            } else {
                echo "Error: <br><br>" . $query . "<br><br>" . $db->error;
            }
        }
    }


    echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
?>