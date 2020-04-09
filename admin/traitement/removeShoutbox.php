<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");
    include("../../common/check_mod.php");
    include("../../common/db_connect_pdo.php");

    $number = mysqli_real_escape_string($db, $_POST['InputNumber']);

    if(!empty($number))
    {
        $reponse = $bddPDO->prepare('SELECT message_id FROM generalchat ORDER BY message_date DESC LIMIT '.$number.' ');
        $reponse->execute();

        $array = ["("]; // On ajoute la paranthèse initialise
        while ($donnees = $reponse->fetch())
        {
            array_push($array, $donnees['message_id']); // On ajoute l'id du message
            array_push($array, ", "); // On ajotue une virgule pour la liste
        }
        array_pop($array); // On enlève la dernière virgule
        array_push($array, ")"); // On ajoute la dernière parenthèse
        $stringListe = implode($array); // On implode pour en faire un string format:     (1, 2, 3, 4 , 5, 6, 8, 1,9)

        $query = "UPDATE generalchat SET message_visible = 0 WHERE message_id IN ".$stringListe." ";
        $result = mysqli_query($db, $query);
    }


    echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
?>