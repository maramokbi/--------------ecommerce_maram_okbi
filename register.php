<?php
    if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            include 'connection.php';
            $fname=$_POST["fname"];
            $lname=$_POST["lname"];
            $email=$_POST["email"];
            $password=password_hash($_POST["password"],PASSWORD_DEFAULT);
            $role=$_POST["role"];
            $query=$pdo->prepare("insert into user (fname,lname,email,password,role) values (?,?,?,?,?)");
            $query->execute(array($fname,$lname,$email,$password,$role));
            header("location:index.php");
            exit();
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
    <form action="" method="post">
        <label for="fname">First name</label>
        <input type="text" name="fname"><br>
        <label for="lname">Last name </label>
        <input type="text" name="lname"><br>
        <label for="email">Email</label>
        <input type="email" name="email"><br>
        <label for="password">Password</label>
        <input type="password" name="password"><br>
        <label for="role" >Role</label>
        <select name="role" id="">
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
        </select>
        <button class="submit">Register</button>
    </form>
</body>
</html>