<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");

    // code source de connexion.php
    include("../../common/db_connect.php");

    $InputMessage = mysqli_real_escape_string($db, $_POST["InputMessage"]);
    $ConversationID = mysqli_real_escape_string($db, $_POST["InputConversationID"]);


    if(!empty($InputMessage) && !empty($ConversationID)) 
    {
        $query = "INSERT INTO messagesPrives(message_conversationID, message_sender, message_date, message_text) VALUES('".$ConversationID."', '".$_SESSION['user_id']."', '".date("Y/m/d H:i:s")."', '".$InputMessage."') ";
        $result = mysqli_query($db, $query);
    }
    else
    {
        echo "erreur";
    }


    echo "<script type='text/javascript'>document.location.replace('../conversation_open.php?id=".$ConversationID."');</script>";
?>