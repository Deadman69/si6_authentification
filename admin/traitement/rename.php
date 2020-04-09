<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");
    include("../../common/check_mod.php");
    include("../../common/db_connect_pdo.php");

    $newName = mysqli_real_escape_string($db, $_POST['InputNewName']);
    $actualLogin = mysqli_real_escape_string($db, $_POST['InputMember']);

    $arrayActualLogin = explode(" ", $actualLogin);
    $actualLogin = $arrayActualLogin[0];


    if(!empty($newName) and ($newName != "" or $newName != " "))
    {
        $query = "UPDATE members SET member_pseudo = '".$newName."' WHERE member_login = '".$actualLogin."' ";
        $result = mysqli_query($db, $query);

        setcookie("SuccessMessage", "User has been successfully modified !", time()+30);
    }


    echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
?>