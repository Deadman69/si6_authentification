<?php
	// Le session_start est déjà inclus dans le reconnect.php
    include("../common/reconnect.php");
    include("../common/check_ban.php");
    include("../common/db_connect_pdo.php");

    $idConversation = htmlspecialchars($_GET['id']);


    $reponse = $bddPDO->query("SELECT conversation_id FROM conversations JOIN conversationsAccess ON conversations.conversation_id = conversationsAccess.access_conversation WHERE conversationsAccess.access_owner = '".$_SESSION["user_id"]."' ");

    $hasAccess = false; // On part du principe qu'il n'a pas accès à la conversation
    while ($donnees = $reponse->fetch())
    {
        if ($donnees['conversation_id'] == $idConversation) // Si l'ID de la conversation est dans la liste des conversations de l'utilisateur
        {
            $hasAccess = true;
        }
    }
    $reponse->closeCursor();


    if (!$hasAccess) // Si il n'a pas accès, on le redirige
    {
        // Getting the URL requested
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']; 

        // Getting MAC address of client
        $mac = exec('getmac'); 
        $mac = strtok($mac, ' '); 

        // Si l'IP est locale, on définis l'IP sur 'localhost'
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip = "::1")
        {
            $ip = 'localhost';
        }

        // if user is connected we could retrieve him
        $username = "";
        if (isset($_SESSION['user_id']))
            $userID = $_SESSION['user_id'];
        else
            $userID = 1; // anonymous


        $query = "INSERT INTO pageForbidden(failed_login, failed_request, failed_ip, failed_date, failed_mac, failed_error) VALUES('".$userID."', '".$link."', '".$ip."', '".date("Y-m-d H:i:s", time())."', '".$mac."', '02x0005');";
        $result = mysqli_query($db, $query);




        echo "<script type='text/javascript'>document.location.replace('conversations.php');</script>";
    }


    $_SESSION['actualConversationLoaded'] = $idConversation;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <title>Conversation - <?php echo $idConversation; ?></title>
        <link rel="icon" href="../pics/favicon.ico" type="image/x-icon">

        <script type="text/javascript" src="../js/jquery.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script type="text/javascript">
            function refresh() {
                $.ajax({
                type:"get",
                url:"traitement/conversation_load.php",
                }).done(function(data){
                $('#coreChat').html(data);
                })
            }

            refresh() // On charge la page au départ

            window.onload = function() {
                setInterval(refresh, 2000) // Répète la fonction toutes les 2 sec
            };

        </script>
    </head>
    <body>
		<div id="base">
            <?php 
                include("../common/header.php");
            ?>
            <div class="col-md-4 offset-md-4">
                <br>
                <form action="traitement/conversationAdd.php" method="post">
                    <div class="form-group">
                        <label for="InputMessage">Enter your message</label>
                        <input type="text" name="InputMessage" class="form-control" placeholder="Hello, i would like to talk you about....." autofocus autocomplete="off">
                        <input type="hidden" name="InputConversationID" value=<?php echo '"'.$idConversation.'"' ?>>
                    </div>
                    <button type="submit" class="btn btn-primary">Send message</button>
                </form>
                <br>
            </div>
        </div>
    	
        <div id="coreChat">
            <!-- Grâce à l'AJAX on va actualiser cette div avec le code des messages -->
        </div>
	</body>
</html>