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
    <title>APEX SPORT — Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Left hero panel -->
    <div class="auth-hero">
        <div class="hero-badge">Join the Elite</div>
        <div class="hero-brand">JOIN<br><span>APEX</span></div>
        <p class="hero-tagline">Your journey to peak performance starts here.</p>
    </div>

    <!-- Right form panel -->
    <div class="auth-panel">
        <div class="auth-card">
            <h1 class="auth-title">CREATE<br>ACCOUNT</h1>
            <p class="auth-subtitle">Start your APEX journey today</p>

            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" placeholder="Doe" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label for="role">Account Type</label>
                    <select name="role" id="role">
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button class="btn-primary" type="submit">CREATE ACCOUNT</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="index.php">Sign in</a>
            </div>
        </div>
    </div>

</body>
</html>
