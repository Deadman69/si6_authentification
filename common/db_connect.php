<?php
    $host = "localhost";
    $user_mysql = "admin";    // nom de l'utilisateur MySQL 
    $password_mysql = "admin";    // mot de passe de l'utilisateur MySQL
    $database = "secured";

    $db = mysqli_connect($host, $user_mysql, $password_mysql, $database);

    if(!$db)
    {
        echo "Echec de la connexion\n";
        exit();
    }

    mysqli_set_charset($db, "utf8");
?>