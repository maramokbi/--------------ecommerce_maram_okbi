<?php
session_start();
include '../connection.php';
if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(isset($_POST["update"]))
            {
                $index = array_search($_POST["choosen_id"], $_SESSION["panier"]["id"]);
                $_SESSION["panier"]["stock"][$index] = 
                $_SESSION["panier"]["stock"][$index] - 
                $_SESSION["panier"]["choosen_quantity"][$index] +
                $_POST["quantity"];
                $_SESSION["panier"]["choosen_quantity"][$index] = $_POST["quantity"];

                $query = $pdo->prepare("update article set stock = ? where id = ?");
                $new_stock = $_SESSION["panier"]["stock"][$index];
                $query->execute(array($new_stock, $_POST["choosen_id"]));
            }
        if(isset($_POST["delete"]))
            {
                $index = array_search($_POST["choosen_id"], $_SESSION["panier"]["id"]);
                array_splice($_SESSION["panier"]["id"], $index, 1);
                array_splice($_SESSION["panier"]["name"], $index, 1);
                array_splice($_SESSION["panier"]["price"], $index, 1);
                array_splice($_SESSION["panier"]["stock"], $index, 1);
                array_splice($_SESSION["panier"]["image"], $index, 1);
                array_splice($_SESSION["panier"]["description"], $index, 1);
                array_splice($_SESSION["panier"]["choosen_quantity"], $index, 1);

                $query = $pdo->prepare("update article set stock = ? where id = ?");
                $new_stock = $_POST["choosen_quantity"];
                $query->execute(array($new_stock, $_POST["choosen_id"]));
            }
            header('Location:'.$_SERVER["PHP_SELF"]);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Sport — My Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <!-- Hero banner -->
    <div class="page-hero">
        <div>
            <p>Review your selection</p>
            <h1>MY<br><em>CART</em></h1>
        </div>
    </div>

    <?php
    $cart_count = count($_SESSION["panier"]["id"]);
    $total_panier = 0;
    for ($i = 0; $i < $cart_count; $i++) {
        $total_panier += $_SESSION["panier"]["price"][$i] * $_SESSION["panier"]["choosen_quantity"][$i];
    }
    ?>

    <div class="cart-layout">

        <!-- Items column -->
        <div class="cart-items">
            <?php if($cart_count === 0): ?>
                <div class="cart-empty">
                    <h2>EMPTY CART</h2>
                    <p>You haven't added anything yet.</p>
                    <a href="index.php" style="display:inline-block;margin-top:24px;padding:14px 32px;background:#0A0A0A;color:#CAFF00;font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:2px;text-decoration:none;border-radius:6px;">SHOP NOW</a>
                </div>
            <?php else: ?>
                <?php for ($i = 0; $i < $cart_count; $i++): ?>
                <div class="cart-item">
                    <img class="cart-item-image" 
                         src="../assets/<?= htmlspecialchars($_SESSION["panier"]["image"][$i]) ?>" 
                         alt="<?= htmlspecialchars($_SESSION["panier"]["name"][$i]) ?>">
                    <div class="cart-item-info">
                        <h3><?= htmlspecialchars($_SESSION["panier"]["name"][$i]) ?></h3>
                        <div class="cart-item-price">
                            <?= number_format($_SESSION["panier"]["price"][$i], 2) ?> <span>DNT / unit</span>
                        </div>
                        <div class="cart-item-price" style="margin-top:6px;font-size:14px;color:#888;">
                            Subtotal: <?= number_format($_SESSION["panier"]["price"][$i] * $_SESSION["panier"]["choosen_quantity"][$i], 2) ?> DNT
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <form class="cart-qty-form" action="" method="post">
                            <input type="hidden" name="choosen_id" value="<?= $_SESSION["panier"]["id"][$i] ?>">
                            <input type="hidden" name="choosen_quantity" value="<?= $_SESSION["panier"]["choosen_quantity"][$i] ?>">
                            <input class="qty-input" type="number" name="quantity" 
                                   min="1" max="<?= $_SESSION["panier"]["stock"][$i] ?>" 
                                   value="<?= $_SESSION["panier"]["choosen_quantity"][$i] ?>">
                            <button class="btn-update" type="submit" name="update">UPDATE</button>
                        </form>
                        <form action="" method="post" style="display:inline">
                            <input type="hidden" name="choosen_id" value="<?= $_SESSION["panier"]["id"][$i] ?>">
                            <input type="hidden" name="choosen_quantity" value="<?= $_SESSION["panier"]["stock"][$i] + $_SESSION["panier"]["choosen_quantity"][$i] ?>">
                            <button class="btn-delete" type="submit" name="delete">Remove</button>
                        </form>
                    </div>
                </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

        <!-- Summary column -->
        <?php if($cart_count > 0): ?>
        <div>
            <div class="cart-summary">
                <div class="summary-title">ORDER SUMMARY</div>
                <div class="summary-line">
                    <span class="summary-label">Items</span>
                    <span class="summary-value"><?= $cart_count ?></span>
                </div>
                <div class="summary-line">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value" style="color:#888">Free</span>
                </div>
                <div class="summary-line summary-total">
                    <span class="total-label">TOTAL</span>
                    <span class="total-value"><?= number_format($total_panier, 2) ?> <span style="font-size:18px">DNT</span></span>
                </div>
                <a href="#" class="btn-checkout">CHECKOUT →</a>
                <a href="index.php" style="display:block;text-align:center;margin-top:16px;color:rgba(255,255,255,.5);font-size:13px;text-decoration:none;">← Continue Shopping</a>
            </div>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>
