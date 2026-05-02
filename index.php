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
    <title>Nova Sport — Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Left hero panel -->
    <div class="auth-hero">
        <div class="hero-badge">Premium Athletic Gear</div>
        <div class="hero-brand">Nova<br><span>Sport</span></div>
        <p class="hero-tagline">Elevate your performance. Every rep. Every race.</p>
    </div>

    <!-- Right form panel -->
    <div class="auth-panel">
        <div class="auth-card">
            <h1 class="auth-title">WELCOME<br>BACK</h1>
            <p class="auth-subtitle">Sign in to your Nova account</p>

            <?php if(isset($error)): ?>
                <div class="error-box"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="text" name="email" id="email" placeholder="you@example.com" autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password">
                </div>
                <button class="btn-primary" type="submit">SIGN IN</button>
            </form>

            <div class="auth-footer">
                Not registered yet? <a href="register.php">Create account</a>
            </div>
        </div>
    </div>

</body>
</html>
