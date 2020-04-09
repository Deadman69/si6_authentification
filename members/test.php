<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <input type="button" onclick="refresh()" value="click">
 
    <div id="maDiv">
             
    </div>
       
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
 
    function refresh() {
       $.ajax({
        type:"get",
        url:"traitement.php",
      }).done(function(data){
        $('#maDiv').html(data);
      })
    }
     
    refresh();
    </script>
</body>
</html>