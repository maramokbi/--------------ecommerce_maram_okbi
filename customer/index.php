<?php 
    session_start();
    $message='';
    include '../connection.php';
    if(!isset($_SESSION["panier"]))
        {
            $_SESSION["panier"]["id"] = [];
            $_SESSION["panier"]["name"] = [];
            $_SESSION["panier"]["price"] = [];
            $_SESSION["panier"]["stock"] = [];
            $_SESSION["panier"]["image"] = [];
            $_SESSION["panier"]["description"] = [];
            $_SESSION["panier"]["choosen_quantity"] = [];
        }
    if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            if($_POST["choosen_id"])
                {
                    $query = $pdo->prepare("select * from article where id=?");
                    $query->execute(array($_POST["choosen_id"]));
                    $choosen_product = $query->fetch();

                    array_push($_SESSION["panier"]["id"],$choosen_product["id"]) ;
                    array_push($_SESSION["panier"]["name"],$choosen_product["name"]) ;
                    array_push($_SESSION["panier"]["price"],$choosen_product["price"]) ;
                    array_push($_SESSION["panier"]["stock"],$choosen_product["stock"]-$_POST["quantity"]) ;
                    array_push($_SESSION["panier"]["image"],$choosen_product["image"]) ;
                    array_push($_SESSION["panier"]["description"],$choosen_product["description"]) ;
                    array_push($_SESSION["panier"]["choosen_quantity"],$_POST["quantity"]) ;

                    $query = $pdo->prepare("update article set stock = ? where id=?");
                    $new_stock=$choosen_product["stock"]-$_POST["quantity"];
                    $query->execute(array($new_stock,$_POST["choosen_id"]));

                    header('Location: ' . $_SERVER["PHP_SELF"]);
                    exit;
                    

                    
                }
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
    <?php include 'header.php'; ?>
    <?php 
       
        $query=$pdo->query("select * from article");
        if($query->rowCount())
        {
            while($product=$query->fetch(PDO::FETCH_ASSOC)){
                echo '<img src="../assets/'.$product["image"].'" style="height:50px">';
                foreach($product as $key=>$value)
                {
                    if(!in_array($key,["description","id", "image"]))
                    {
                            if($key=="price")
                                echo $value." DNT <br>";
                            elseif ($key=="stock")
                                echo 'only '.$value .' units available <br>';
                            else echo $value.'<br>';
                    }
                }
                ?>
                <form action="" method="POST">
                    <input type="hidden" name="choosen_id" value="<?= $product["id"]; ?>">
                    <label for="quantity"></label>
                    <input type="number" name="quantity" id="quantity" min="1" max="<?= $product["stock"] ?>">
                    <button type="submit">Add to cart</button>
                </form>
                <?php  
            }
        }
     ?>
</body>
</html>