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
    <title>Nova Sport — Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <!-- Hero banner -->
    <div class="page-hero">
        <div>
            <p>New Collection 2026</p>
            <h1>GEAR<br>UP <em>NOW</em></h1>
        </div>
    </div>

    <!-- Products section -->
    <div class="section-label">
        <h2>ALL PRODUCTS</h2>
        <?php
            $count_query = $pdo->query("select count(*) from article");
            $total = $count_query->fetchColumn();
        ?>
        <span class="section-count"><?= $total ?> items</span>
    </div>

    <div class="products-grid">
    <?php 
        $query=$pdo->query("select * from article");
        if($query->rowCount()):
            while($product=$query->fetch(PDO::FETCH_ASSOC)):
                $stock = (int)$product["stock"];
                $stockClass = $stock > 10 ? '' : ($stock > 0 ? 'low' : 'out');
                $stockLabel = $stock > 10 ? $stock.' in stock' : ($stock > 0 ? 'Only '.$stock.' left!' : 'Out of stock');
    ?>
        <div class="product-card">
            <div class="card-image">
                <img src="../assets/<?= htmlspecialchars($product["image"]) ?>" alt="<?= htmlspecialchars($product["name"]) ?>">
                <span class="stock-badge <?= $stockClass ?>"><?= $stockLabel ?></span>
            </div>
            <div class="card-body">
                <div class="card-name"><?= htmlspecialchars($product["name"]) ?></div>
                <div class="card-price">
                    <?= number_format($product["price"], 2) ?> <span>DNT</span>
                </div>
            </div>
            <div class="card-footer">
                <?php if($stock > 0): ?>
                <form action="" method="POST" style="display:contents">
                    <input type="hidden" name="choosen_id" value="<?= $product["id"] ?>">
                    <input class="qty-input" type="number" name="quantity" min="1" max="<?= $stock ?>" value="1">
                    <button class="btn-add" type="submit">ADD TO CART</button>
                </form>
                <?php else: ?>
                    <button class="btn-add" disabled>OUT OF STOCK</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; endif; ?>
    </div>

</body>
</html>
