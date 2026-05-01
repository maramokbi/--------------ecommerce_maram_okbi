<?php
session_start();
include '../connection.php';
if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(isset($_POST["update"]))
            {
                $index = array_search($_POST["choosen_id"], $_SESSION["panier"]["id"]);
                $_SESSION["panier"]["stock"][$index] = 
                $_SESSION["panier"]["stock"][$index] - 
                $_SESSION["panier"]["choosen_quantity"][$index] +
                $_POST["quantity"];
                $_SESSION["panier"]["choosen_quantity"][$index] = $_POST["quantity"];

                $query = $pdo->prepare("update article set stock = ? where id = ?");
                $new_stock = $_SESSION["panier"]["stock"][$index];
                $query->execute(array($new_stock, $_POST["choosen_id"]));

            }
        if(isset($_POST["delete"]))
            {
                $index = array_search($_POST["choosen_id"], $_SESSION["panier"]["id"]);
                array_splice($_SESSION["panier"]["id"], $index, 1);
                array_splice($_SESSION["panier"]["name"], $index, 1);
                array_splice($_SESSION["panier"]["price"], $index, 1);
                array_splice($_SESSION["panier"]["stock"], $index, 1);
                array_splice($_SESSION["panier"]["image"], $index, 1);
                array_splice($_SESSION["panier"]["description"], $index, 1);
                array_splice($_SESSION["panier"]["choosen_quantity"], $index, 1);

                $query = $pdo->prepare("update article set stock = ? where id = ?");
                $new_stock = $_POST["choosen_quantity"];
                $query->execute(array($new_stock, $_POST["choosen_id"]));
            }
            header('Location:'.$_SERVER["PHP_SELF"]);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    include 'header.php';
    $total_panier = 0;
    for ($i=0; $i < count($_SESSION["panier"]["id"]); $i++) 
        { 
            $total_panier += $_SESSION["panier"]["price"][$i] * $_SESSION["panier"]["choosen_quantity"][$i];
            echo '<img src="../assets/'.$_SESSION["panier"]["image"][$i].'" style="height:30px"><br>';
            echo '<h3>'.$_SESSION["panier"]["name"][$i].'</h3>';
            echo 'Price: '.$_SESSION["panier"]["price"][$i].' DNT<br>';
            ?>
            <form action="" method="post">
                <input type="hidden" name="choosen_id" value="<?= $_SESSION["panier"]["id"][$i] ?>">
                <label for="quantity">Quantity</label><br>
                <input type="number" name="quantity" min="1" max="<?= $_SESSION["panier"]["stock"][$i] ?>" value="<?= $_SESSION["panier"]["choosen_quantity"][$i] ?>"><br>
                <button type="submit" name="update">Update</button><br>
                <button type="submit" name="delete">Delete</button><br>
            </form>
            <br>
            <?php
        }
    echo 'Total Amount: '.$total_panier.' DNT';
    ?>
    
</body>
</html>