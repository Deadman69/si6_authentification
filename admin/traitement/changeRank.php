<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");
    include("../../common/check_mod.php");
    include("../../common/db_connect_pdo.php");

    $member = mysqli_real_escape_string($db, $_POST['InputMember']);
    $arrayMember = explode(" ", $member);
    $member = $arrayMember[0];

    $rank = mysqli_real_escape_string($db, $_POST['InputRank']);

    if ($rank != "Select a rank")
    {
        if (mysqli_real_escape_string($db, $_POST['InputMember']) != "Select a member")
        {
            // Registering Message
            $query = "UPDATE members SET member_access = (SELECT access_id FROM access WHERE access_name = '".$rank."') WHERE member_login = '".$member."' ";
            $result = mysqli_query($db, $query);
        }
        else
        {
            echo "Error: You have to select a user !";
        }
    }
    else
    {
        echo "Error: You have to select a rank !";
    }


    echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
?>