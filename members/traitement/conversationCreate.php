<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");

    // code source de connexion.php
    include("../../common/db_connect.php");

    $InputMember = mysqli_real_escape_string($db, $_POST["InputMember"]);
    $InputSubject = mysqli_real_escape_string($db, $_POST["InputSubject"]);
    $UID = explode("#", $InputMember);

    if(!empty($InputMember) && !empty($UID) && (!empty($InputSubject && $InputSubject != "" && $InputSubject != " ")) )
    { 
        // On chercher le member rechercher dans la BDD
        $query = "SELECT member_id FROM members WHERE member_id = '".$UID[1]."' ";
        $result = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($result);

        if ($user['member_id'] != $_SESSION['user_id'])
        {
            // Registering Conversation
            $query = "INSERT INTO conversations(conversation_dateCreation, conversation_initiator, conversation_subject) VALUES('".date("Y/m/d H:i:s")."', ".$_SESSION['user_id'].", '".$InputSubject."' )";
            $result = mysqli_query($db, $query);

            // On récupère l'ID de la dernière conversation créer
            $query = "SELECT conversation_id FROM conversations ORDER BY conversation_dateCreation DESC LIMIT 1 ";
            $result = mysqli_query($db, $query);
            $conversation = mysqli_fetch_assoc($result);

            // Adding access to Owner
            $query = "INSERT INTO conversationsAccess(access_owner, access_conversation) VALUES('".$_SESSION['user_id']."', ".$conversation['conversation_id']." )";
            $result = mysqli_query($db, $query);

            // Adding access to Victim
            $query = "INSERT INTO conversationsAccess(access_owner, access_conversation) VALUES('".$user['member_id']."', ".$conversation['conversation_id']." )";
            $result = mysqli_query($db, $query);
        }
        else
        {
            echo "You can't send message to yourself !";
            setcookie("ErrorMessage", "ERROR: You can't send message to yourself !", time()+30);
        }
    }
    else
    {
        echo "erreur";
    }


    echo "<script type='text/javascript'>document.location.replace('../conversations.php');</script>";
?>