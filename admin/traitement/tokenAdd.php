<?php
    include("../../common/reconnect.php");
    include("../../common/check_ban.php");
    include("../../common/check_mod.php");
    include("../../common/db_connect_pdo.php");

    function RandomString($car) {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxy123456789./$*%:[])(-_@";
        srand((double)microtime()*1000000);

        for($i=0; $i<$car; $i++) {
            $string .= $chaine[rand()%strlen($chaine)];
        }

        return $string;
    }

    $InputToken = mysqli_real_escape_string($db, $_POST['InputToken']);
    $InputTokenNumber = mysqli_real_escape_string($db, $_POST['InputTokenNumber']);

    $arrayTokens = array();
    $token = null;
    $numberGenerated = 0;
    $Message = "";

    // Checking if token already exist
    $query = "SELECT COUNT(token_id) as total FROM tokens WHERE token_value = '".$InputToken."' ";
    $result = mysqli_query($db, $query);
    $data=mysqli_fetch_assoc($result);
    if( $data['total'] == 0) // If == 0, then can register
    {
        if(empty($InputToken) && empty($InputTokenNumber))
        {
            // Generate a random token
            $token = RandomString(30);
            $numberGenerated = $numberGenerated + 1;
            array_push($arrayTokens, $token);

            $query = "INSERT INTO tokens(token_value, token_createdBy, token_createDate, token_expireDate) VALUES('".$token."', '".$_SESSION['user_id']."', '".date("Y/m/d H:i:s")."', '".date("Y/m/d H:i:s", time()+604800)."') ";
            $result = mysqli_query($db, $query);

            $token = null;
        }
        elseif (empty($InputToken) && !empty($InputTokenNumber))
        {
            // Générer un certain nombre de tokens
            $i = 0;
            while ($i < $InputTokenNumber)
            {
                $token = RandomString(30);
                $numberGenerated = $numberGenerated + 1;
                array_push($arrayTokens, $token);
                $i = $i + 1;

                $query = "INSERT INTO tokens(token_value, token_createdBy, token_createDate, token_expireDate) VALUES('".$token."', '".$_SESSION['user_id']."', '".date("Y/m/d H:i:s")."', '".date("Y/m/d H:i:s", time()+604800)."') ";
                $result = mysqli_query($db, $query);
            }
            $token = null;

            $query = "INSERT INTO logs(log_login, log_date, log_code) VALUES('".$_SESSION['user_id']."', '".date("Y/m/d H:i:s")."', '0001x01') ";
            $result = mysqli_query($db, $query);
        }
        elseif (!empty($InputToken) && empty($InputTokenNumber))
        {
            // Générer un token précis
            array_push($arrayTokens, $InputToken);
            $numberGenerated = $numberGenerated + 1;

            $query = "INSERT INTO tokens(token_value, token_createdBy, token_createDate, token_expireDate) VALUES('".$InputToken."', '".$_SESSION['user_id']."', '".date("Y/m/d H:i:s")."', '".date("Y/m/d H:i:s", time()+604800)."') ";
            $result = mysqli_query($db, $query);
        }
        elseif (!empty($InputToken) && !empty($InputTokenNumber))
        {
            // Erreur, impossible de créer plusieurs tokens avec la même valeur
            echo "Impossible de créer plusieurs tokens avec la même valeur !";
        }
        $Message = "You have generate $numberGenerated tokens:";
    } // Token already exist
    else
    {
        $Message = "This token already exist in the database, create an other one !";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/bootstrap.min.js"></script>
    <title>Tokens added</title>
    <link rel="icon" href="../../pics/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include("../../common/headerAdminTraitement.php"); ?>
    <br><br>
    <div class="row">
        <div class="col-md-2 offset-md-1">
            <br>
            <span style="color: red; text-align: center; font-weight: bold;">
                <?php
                    if (isset($_COOKIE["ErrorMessage"]))
                    {
                        echo $_COOKIE["ErrorMessage"];
                        setcookie("ErrorMessage", "", time() - 3600);
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-2">
            <span><h3><?php echo $Message; ?></h3></span>
            <ul>
                <?php
                    foreach ($arrayTokens as $column) {
                        echo "<li>".$column."</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
