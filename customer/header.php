<?php
// header.php — included after session_start() in parent page
$cart_count = isset($_SESSION["panier"]) ? count($_SESSION["panier"]["id"]) : 0;
$user_name  = $_COOKIE['connected_user'] ?? '';
?>
<nav class="apex-nav">
    <a href="index.php" class="nav-brand">APEX<span>SPORT</span></a>
    <span class="nav-tagline">Premium Athletic Gear</span>
    <div class="nav-right">
        <?php if($user_name): ?>
            <span class="nav-user">👋 <?= htmlspecialchars($user_name) ?></span>
        <?php endif; ?>
        <a href="panier.php" class="nav-cart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            Cart
            <span class="cart-badge"><?= $cart_count ?></span>
        </a>
    </div>
</nav>
