<?php
    include '../connection.php';
    $nb_products = $pdo->query("select count(*) from article")->fetchColumn();
    $nb_users    = $pdo->query("select count(*) from user")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APEX SPORT — Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-name">APEX<span>SPORT</span></div>
            <div class="sidebar-badge">Admin Panel</div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="nav-link active">
                <span class="nav-link-icon">⊞</span> Dashboard
            </a>
            <a href="products.php" class="nav-link">
                <span class="nav-link-icon">◈</span> Products
            </a>
            <a href="users.php" class="nav-link">
                <span class="nav-link-icon">◎</span> Users
            </a>
        </nav>
        <div class="sidebar-footer">
            <?= $_COOKIE["name"] ?? "Admin" ?>
        </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">
        <header class="admin-topbar">
            <span class="topbar-title">DASHBOARD</span>
            <div class="topbar-user">
                <span class="topbar-user-dot"></span>
                <?= $_COOKIE["connected_user"] ?? "Admin" ?>
            </div>
        </header>
        <div class="admin-content">

            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-value"><?= $nb_products ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Registered Users</div>
                    <div class="stat-value"><?= $nb_users ?></div>
                </div>
            </div>

            <div class="quick-links">
                <a href="products.php" class="quick-link-card">
                    <span class="qlc-icon">📦</span>
                    <div>
                        <div class="qlc-label">PRODUCTS</div>
                        <div class="qlc-desc">Manage your product catalog</div>
                    </div>
                </a>
                <a href="users.php" class="quick-link-card">
                    <span class="qlc-icon">👥</span>
                    <div>
                        <div class="qlc-label">USERS</div>
                        <div class="qlc-desc">Manage customers and admins</div>
                    </div>
                </a>
            </div>

        </div>
    </div>

</div>
</body>
</html>