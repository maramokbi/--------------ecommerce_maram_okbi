<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?= $_COOKIE['connected_user']?? "" ?>
        <a href="panier.php"><img src="../assets/panier.png" style="height: 20px;"></a> <br>
        <p><?= $_SESSION["panier"]?count($_SESSION["panier"]["id"]):0 ?></p>

    </div>
</body>
</html>