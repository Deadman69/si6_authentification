<?php
    try
    {
        $bddPDO = new PDO('mysql:host=localhost;dbname=secured;charset=utf8', 'admin', 'admin');
    }
    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }
?>