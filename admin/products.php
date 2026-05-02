<?php
    session_start();
    $message = "";
    include '../connection.php';
    if($_SERVER["REQUEST_METHOD"]=="GET" && empty($_GET["selected_id"]))
        {
        $_SESSION["crud_task"] = "Create";
        unset($_SESSION["selected_id"]);
        unset($selected_product); 
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
    <title>Nova Sport — Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-name">Nova<span>Sport</span></div>
            <div class="sidebar-badge">Admin Panel</div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="nav-link">
                <span class="nav-link-icon">⊞</span> Dashboard
            </a>
            <a href="products.php" class="nav-link active">
                <span class="nav-link-icon">◈</span> Products
            </a>
            <a href="users.php" class="nav-link">
                <span class="nav-link-icon">◎</span> Users
            </a>
        </nav>
        <div class="sidebar-footer">
            <?= $_COOKIE["connected_user"] ?? "Admin" ?>
        </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">
        <header class="admin-topbar">
            <span class="topbar-title">PRODUCTS</span>
            <div class="topbar-user">
                <span class="topbar-user-dot"></span>
                <?= $_COOKIE["connected_user"] ?? "Admin" ?>
            </div>
        </header>
        <div class="admin-content">

            <!-- Products Table -->
            <div class="table-card">
                <div class="table-header">
                    <h2>PRODUCT CATALOG</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query=$pdo->query("select * from article");
                        if($query->rowCount()):
                            while($product=$query->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><img src="../assets/<?= htmlspecialchars($product["image"]) ?>" alt="<?= htmlspecialchars($product["name"]) ?>"></td>
                            <td><?= htmlspecialchars($product["name"]) ?></td>
                            <td><?= number_format($product["price"],2) ?> DNT</td>
                            <td><?= $product["stock"] ?></td>
                            <td style="display:flex;gap:8px;padding:14px 20px;">
                                <a href="products.php?selected_id=<?= $product["id"] ?>&crud_task=update" class="action-btn edit" title="Edit">
                                    <img src="../assets/update.png" alt="Edit">
                                </a>
                                <a href="products.php?selected_id=<?= $product["id"] ?>&crud_task=delete" class="action-btn delete" title="Delete">
                                    <img src="../assets/delete.png" alt="Delete">
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Form Panel -->
            <?php 
                $task = $_SESSION["crud_task"] ?? "Create";
                $taskLower = strtolower($task);
            ?>
            <div class="form-panel">
                <div class="form-panel-header">
                    <span class="form-panel-title">
                        <?php if($task=="Create") echo "ADD NEW PRODUCT";
                              elseif($task=="update") echo "EDIT PRODUCT";
                              else echo "DELETE PRODUCT"; ?>
                    </span>
                    <span class="crud-mode-badge <?= $taskLower == 'create' ? 'create' : ($taskLower == 'update' ? 'update' : 'delete') ?>">
                        <?= strtoupper($task) ?>
                    </span>
                </div>
                <div class="form-panel-body">
                    <?php if($message): ?>
                        <div class="message-banner"><?= $message ?></div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" id="name" 
                                       value="<?= htmlspecialchars($selected_product["name"] ?? "") ?>"
                                       placeholder="e.g. Pro Running Shoes">
                            </div>
                            <div class="form-group">
                                <label for="image">Image Filename</label>
                                <input type="text" name="image" id="image"
                                       value="<?= htmlspecialchars($selected_product["image"] ?? "") ?>"
                                       placeholder="images/produits/filename.jpg">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Price (DNT)</label>
                                <input type="number" min="1" step="0.01" name="price" id="price"
                                       value="<?= $selected_product["price"] ?? "" ?>"
                                       placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock Quantity</label>
                                <input type="number" min="0" name="stock" id="stock"
                                       value="<?= $selected_product["stock"] ?? "" ?>"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      placeholder="Product description..."><?= htmlspecialchars(trim($selected_product["description"] ?? "")) ?></textarea>
                        </div>
                        <button class="btn-submit <?= $taskLower == 'delete' ? 'delete-mode' : '' ?>" type="submit">
                            <?= strtoupper($task) ?> PRODUCT
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>
