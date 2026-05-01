<?php
    session_start();
    $message = "";
    include '../connection.php';
    if($_SERVER["REQUEST_METHOD"]=="GET" && !empty($_GET["selected_id"]) )
        {
            if($_GET["selected_id"])
                {
                    $_SESSION["selected_id"] = $_GET["selected_id"] ;
                    $_SESSION["crud_task"] = $_GET["crud_task"] ;
                    $query=$pdo->prepare("select * from user where id=?");
                    $query->execute(array($_SESSION["selected_id"]));
                    $selected_user = $query->fetch();
                }
        }
    if($_SERVER["REQUEST_METHOD"]=="POST"  )
        {
            $lname = $_POST["lname"];
            $fname = $_POST["fname"];
            $email = $_POST["email"];
            $role = $_POST["role"];
            if($_SESSION["crud_task"]=="delete")
                {
                    $query=$pdo->prepare("delete from user where id=?");
                    if($query->execute(array($_SESSION["selected_id"])))
                        $message="User successfully deleted!";
                }
            elseif ($_SESSION["crud_task"]=="update")
                {
                    $query=$pdo->prepare("update user set lname=?, fname=?, email=? ,role=? where id=?");
                    if($query->execute(array($lname,$fname,$email,$role,$_SESSION["selected_id"])))
                        $message="User successfully updated!";
                }
            else 
                {
                    $password=password_hash($_POST["password"],PASSWORD_DEFAULT);
                    $query=$pdo->prepare("insert into user(lname,fname,email,password,role) values (?,?,?,?,?)");
                    if($query->execute(array($lname,$fname,$email,$password,$role)))
                        $message="User successfully created!";
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
        $query=$pdo->query("select * from user");
        if($query->rowCount()){
            echo '<table>';
            while($user=$query->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                foreach($user as $key=>$value){
                    if(!in_array($key,["id","password"])){
                        echo '<td>'.$value.'</td>';
                    }
                }
                echo '<td><a href=users.php?selected_id='.$user["id"].'&crud_task=delete><img src="../assets/delete.png"></a></td>';
                echo '<td><a href=users.php?selected_id='.$user["id"].'&crud_task=update><img src="../assets/update.png"></a></td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        
    ?>
    <form action="" method="post">
        <p><?= $message ?></p>
        <label for="fname">First name</label>
        <input type="text" name="fname" value="<?= $selected_user["fname"]??"" ?>"><br>
        <label for="lname">Last name </label>
        <input type="text" name="lname" value="<?= $selected_user["lname"]??"" ?>"><br>
        <label for="email">Email</label>
        <input type="email" name="email" value="<?= $selected_user["email"]??"" ?>"><br>
        <label for="password">Password</label>
        <input type="password" name="password"><br>
        <label for="role" >Role</label>
        <select name="role" id="">
            <?php 
                if($selected_user["role"]=="admin")
                    echo '<option value="admin" selected>Admin</option>
                    <option value="customer">Customer</option>';
                else 
                    echo '<option value="admin" >Admin</option>
                    <option value="customer" selected>Customer</option>';
            ?>
            
        </select>
        <br>
        <button class="submit" type="submit" name="crud_task"><?= $_SESSION["crud_task"]??"Create"; ?></button>
    </form>
</body>
</html>