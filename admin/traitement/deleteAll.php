<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");
    include("../../common/check_mod.php");
    include("../../common/db_connect_pdo.php");

    $member = mysqli_real_escape_string($db, $_POST['InputMember']);
    $arrayMember = explode(" ", $member);
    $member = $arrayMember[0];


    if(!empty($member))
    { 
        $query = "SELECT member_id FROM members WHERE member_login = '".$member."' ";
        $result = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($result);

        // Updating messages visiblity
        $query = "UPDATE generalchat SET message_visible = 0 WHERE message_sender = '".$user['member_id']."' ";


        if ($db->query($query) === TRUE) {
            echo "Enregistrement: Ok";
        } else {
            echo "Error: <br><br>" . $query . "<br><br>" . $db->error;
        }
    }


    echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
?>