<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?= $_COOKIE["name"] ?? ""; ?>
    </div>
    <p><a href="users.php">Users</a></p>
    <p><a href="products.php">Products</a></p>
</body>
</html>