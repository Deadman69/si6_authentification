<?php 
    // Le session_start est déjà inclus dans le reconnect.php
    include("../common/reconnect.php");
    include("../common/check_ban.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <title>Conversations</title>
        <link rel="icon" href="../pics/favicon.ico" type="image/x-icon">
        <script type="text/javascript" src="../js/jquery.js"></script>
    </head>
    <body>
        <div id="base">
            <?php 
                include("../common/header.php");
            ?>
            <div class="col-md-4 offset-md-4">
                <br>
                <span style="color: red; text-align: center; font-weight: bold;">
                    <?php
                        if (isset($_COOKIE["ErrorMessage"]))
                        {
                            echo $_COOKIE["ErrorMessage"];
                            //setcookie("ErrorMessage", "", time() - 3600);
                        }
                    ?>
                </span>
                <span style="color: green; text-align: center; font-weight: bold;">
                    <?php
                        if (isset($_COOKIE["SuccessMessage"]))
                        {
                            echo $_COOKIE["SuccessMessage"];
                            setcookie("SuccessMessage", "", time() - 3600);
                        }
                    ?>
                </span>
                <form action="traitement/conversationCreate.php" method="post">
                    <div class="form-group">
                        <label for="InputMember">Enter the pseudo of the member you wish to contact</label>
                        <input type="text" name="InputMember" class="form-control" placeholder="SupraGamer#5246" autofocus autocomplete="off">
                        <label for="InputSubject">Enter the subject</label>
                        <input type="text" name="InputSubject" class="form-control" placeholder="Important Dicussion" autofocus autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Conversation</button>
                </form>
                <br>
            </div>
        </div>

        <div id="coreChat">
            <?php
                include("../common/db_connect_pdo.php");


                $reponse = $bddPDO->query("SELECT conversation_id, conversation_dateCreation, conversation_initiator, conversation_openned, conversation_visible, conversation_subject, members.member_pseudo FROM conversations JOIN conversationsAccess ON conversations.conversation_id = conversationsAccess.access_conversation JOIN members ON conversations.conversation_initiator = members.member_id WHERE conversationsAccess.access_owner = '".$_SESSION["user_id"]."' ORDER BY conversation_dateCreation DESC");
                // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
                while ($donnees = $reponse->fetch())
                {
                    if ($donnees['conversation_visible'])
                    {
                        $conversationID = $donnees['conversation_id'];
                        $query = "SELECT members.member_pseudo FROM members JOIN conversationsAccess ON members.member_id = conversationsAccess.access_owner WHERE access_conversation = '".$conversationID."' ORDER BY access_id DESC ";
                        $result = mysqli_query($db, $query);
                        $data = mysqli_fetch_assoc($result);


                        echo '<div class="row">';
                            echo '<div class="col-md-4 offset-md-4"><strong><a href="conversation_open.php?id='.htmlspecialchars($donnees['conversation_id']).'">'.htmlspecialchars($donnees['conversation_subject']).'</strong><ital> - ('.htmlspecialchars($donnees['conversation_dateCreation']).') </ital> - '.htmlspecialchars($donnees['member_pseudo']).' & '.$data["member_pseudo"].'</a></div>';
                        echo '</div>';
                    }
                }
                $reponse->closeCursor();
            ?>
        </div>
    </body>
</html>