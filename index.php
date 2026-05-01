<?php
    if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            include 'connection.php';
            $error = "connection impossible";
            $query=$pdo->prepare("select * from user where email=?");
            $query->execute(array($_POST["email"]));
            $user = $query->fetch();
            if($user)
                {
                    if(password_verify($_POST["password"],$user["password"]))
                        {
                            setcookie("connected_user",$user["fname"]." ".$user["lname"],time()+3600*12);
                            if($user["role"]=="admin")
                                {
                                    header('Location:./admin');
                                }
                            else 
                                header('Location:./customer');
                        }
                    else 
                        $error="Incorrect password!";

                }
            else
                $error="Email not found !";
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
    <div>
        <?php
             if(isset($error))
                 echo($error);
                // echo $error ?? "";
        ?>
    </div>
    
    <form action="" method="post">
        <label for="email">Email</label>
        <input type="text" name="email"><br>
        <label for="password">Password</label>
        <input type="password" name="password"><br>
        <button class="submit">Login</button>
        <p>
            Not already registered? 
            <a href="register.php"> Register </a>
        </p>
    </form>
</body>
</html>