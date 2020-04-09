<?php
    include("../../common/db_connect_pdo.php");

    // Récupération des 30 derniers messages

    $reponse = $bddPDO->query('SELECT message_id, message_sender, message_date, message_text, message_visible, members.member_pseudo FROM generalChat JOIN members ON members.member_id = generalChat.message_sender ORDER BY message_date DESC LIMIT 0, 30');
    // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
    while ($donnees = $reponse->fetch())
    {
        if ($donnees['message_visible'])
        {
            echo '<div class="row">';
            echo '<div class="col-md-2 offset-md-2" style="border: 1px solid;"><strong>'.htmlspecialchars($donnees['member_pseudo']).'#'.htmlspecialchars($donnees['message_sender']).'</strong><br><ital>'.htmlspecialchars($donnees['message_date']).'</ital></div>';
            echo '<div class="col-md-6" style="border: 1px solid;">' . htmlspecialchars($donnees['message_text']) . '</div>';
            echo '</div>';
        }
    }
    $reponse->closeCursor();
?>