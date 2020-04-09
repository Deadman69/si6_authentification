<?php
    include("../../common/reconnect.php");
    include("../../common/db_connect_pdo.php");

    $reponse = $bddPDO->query("SELECT message_id, message_conversationID, message_sender, message_date, message_text, members.member_pseudo FROM messagesprives JOIN members ON message_sender = member_id WHERE message_conversationID = ".$_SESSION['actualConversationLoaded']." ORDER BY message_date DESC LIMIT 100");
    // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
    while ($donnees = $reponse->fetch())
    {
        if ($donnees['member_pseudo'] == $_SESSION['pseudo'])
        {
            echo '<span style="color: red;">';
                echo '<div class="row">';
                    echo '<div class="col-md-2 offset-md-1">';
                        echo '<b>'.htmlspecialchars($donnees['member_pseudo']).'</b><br>';
                        echo '<i>('.htmlspecialchars($donnees['message_date']).')</i>';
                    echo '</div>';
                    echo '<div class="col-md-8">';
                        echo htmlspecialchars($donnees['message_text']);
                    echo '</div>';
                echo '</div>';
            echo '</span>';
        }
        else
        {
            echo '<span style="color: green;">';
                echo '<div class="row">';
                    echo '<div class="col-md-2 offset-md-1">';
                        echo '<b>'.htmlspecialchars($donnees['member_pseudo']).'</b><br>';
                        echo '<i>('.htmlspecialchars($donnees['message_date']).')</i>';
                    echo '</div>';
                    echo '<div class="col-md-8">';
                        echo htmlspecialchars($donnees['message_text']);
                    echo '</div>';
                echo '</div>';
            echo '</span>';
        }
    }
    $reponse->closeCursor();
?>