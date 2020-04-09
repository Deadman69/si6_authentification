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
        <title>Shoutbox</title>
        <link rel="icon" href="../pics/favicon.ico" type="image/x-icon">

        <script type="text/javascript" src="../js/jquery.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script type="text/javascript">
            function refresh() {
               $.ajax({
                type:"get",
                url:"traitement/shoutbox_load.php",
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
            <?php include("../common/header.php"); ?>
            <div class="col-md-4 offset-md-4">
                <br>
                <form action="traitement/shoutbox_post.php" method="post">
                    <div class="form-group">
                        <label for="InputMessage">Write your message</label>
                        <input type="text" name="InputMessage" class="form-control" placeholder="Enter a message to send" autofocus autocomplete="off">
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