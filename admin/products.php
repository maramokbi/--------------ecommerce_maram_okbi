<?php
    session_start();
    $message = "";
    include '../connection.php';
    if(!isset($_SESSION["crud_task"])){
        $_SESSION["crud_task"] = "Create";
    }
    if($_SERVER["REQUEST_METHOD"]=="GET" && !empty($_GET["selected_id"]) )
        {
            if($_GET["selected_id"])
                {
                    $_SESSION["selected_id"] = $_GET["selected_id"] ;
                    $_SESSION["crud_task"] = $_GET["crud_task"] ;
                    $query=$pdo->prepare("select * from article where id=?");
                    $query->execute(array($_SESSION["selected_id"]));
                    $selected_product = $query->fetch();
                }
        }
    if($_SERVER["REQUEST_METHOD"]=="POST"  )
        {
            $name = $_POST["name"];
            $price = $_POST["price"];
            $stock = $_POST["stock"];
            $description = $_POST["description"];
            $image = $_POST["image"];
            
            if($_SESSION["crud_task"]=="delete")
                {
                    $query=$pdo->prepare("delete from article where id=?");
                    if($query->execute(array($_SESSION["selected_id"])))
                        $message="Product successfully deleted!";
                    session_unset();
                }
            elseif ($_SESSION["crud_task"]=="update")
                {
                    $query=$pdo->prepare("update article set name=?, price=?, stock=?, description=?, image=? where id=?");
                    if($query->execute(array($name,$price,$stock,$description,$image,$_SESSION["selected_id"])))
                        $message="Product successfully updated!";
                    session_unset();
                }
            else 
                {
                    $query=$pdo->prepare("insert into article(name,price,stock,description,image) values (?,?,?,?,?)");
                    if($query->execute(array($name,$price,$stock,$description,$image)))
                        $message="Product successfully created!";
                }
            
        } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <?php
    include '../connection.php';
        $query=$pdo->query("select * from article");
        if($query->rowCount()){
            echo '<table>';
            while($product=$query->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                foreach($product as $key=>$value){
                    if(!in_array($key,["id","description"])){
                        if($key=="image" ){
                            echo '<td><img src="../assets/'.$value.'" width="50"></td>';
                        }else{
                            echo '<td>'.$value.'</td>';
                        }
                    }
                }
                echo '<td><a href=products.php?selected_id='.$product["id"].'&crud_task=delete><img src="../assets/delete.png"></a></td>';
                echo '<td><a href=products.php?selected_id='.$product["id"].'&crud_task=update><img src="../assets/update.png"></a></td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        
    ?>
    <form action="" method="post">
        <p><?= $message ?></p>
        <label for="name">Name</label><br>
        <input type="text" name="name" id="name" value="<?= $selected_product["name"]??"" ?>"><br>
        <label for="price">Price</label><br>
        <input type="number" min="1" step="0.01" name="price" id="price" value="<?= $selected_product["price"]??"" ?>"><br>
        <label for="stock">Stock</label><br>
        <input type="number" min="0" name="stock" id="stock"     value="<?= $selected_product["stock"]??"" ?>"><br>
        <label for="image">Image</label><br>
        <input type="file" name="image" id="image" accept="image/*"> <br>
        <label for="description">Description</label><br>
        <textarea name="description" id="description" cols="30" rows="5">
            <?= $selected_product["description"]??"" ?>
        </textarea><br>
        <button class="submit" type="submit" name="crud_task"><?= $_SESSION["crud_task"]??"Create"; ?></button>
    </form>
</body>
</html>