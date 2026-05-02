<?php
    session_start();
    $message = "";
    include '../connection.php';
    if($_SERVER["REQUEST_METHOD"]=="GET" && empty($_GET["selected_id"]))
        {
            $_SESSION["crud_task"] = "Create";
            unset($_SESSION["selected_id"]);
        }
    if($_SERVER["REQUEST_METHOD"]=="GET" && !empty($_GET["selected_id"]) )
        {
            if($_GET["selected_id"])
                {
                    $_SESSION["selected_id"] = $_GET["selected_id"] ;
                    $_SESSION["crud_task"] = $_GET["crud_task"] ;
                    $query=$pdo->prepare("select * from user where id=?");
                    $query->execute(array($_SESSION["selected_id"]));
                    $selected_user = $query->fetch();
                }
        }
    if($_SERVER["REQUEST_METHOD"]=="POST"  )
        {
            $lname = $_POST["lname"];
            $fname = $_POST["fname"];
            $email = $_POST["email"];
            $role = $_POST["role"];
            if($_SESSION["crud_task"]=="delete")
                {
                    $query=$pdo->prepare("delete from user where id=?");
                    if($query->execute(array($_SESSION["selected_id"])))
                        $message="User successfully deleted!";
                }
            elseif ($_SESSION["crud_task"]=="update")
                {
                    $query=$pdo->prepare("update user set lname=?, fname=?, email=? ,role=? where id=?");
                    if($query->execute(array($lname,$fname,$email,$role,$_SESSION["selected_id"])))
                        $message="User successfully updated!";
                }
            else 
                {
                    $password=password_hash($_POST["password"],PASSWORD_DEFAULT);
                    $query=$pdo->prepare("insert into user(lname,fname,email,password,role) values (?,?,?,?,?)");
                    if($query->execute(array($lname,$fname,$email,$password,$role)))
                        $message="User successfully created!";
                }
        } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Sport — Users</title>
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
            <a href="products.php" class="nav-link">
                <span class="nav-link-icon">◈</span> Products
            </a>
            <a href="users.php" class="nav-link active">
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
            <span class="topbar-title">USERS</span>
            <div class="topbar-user">
                <span class="topbar-user-dot"></span>
                <?= $_COOKIE["connected_user"] ?? "Admin" ?>
            </div>
        </header>
        <div class="admin-content">

            <!-- Users Table -->
            <div class="table-card">
                <div class="table-header">
                    <h2>ALL USERS</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query=$pdo->query("select * from user");
                        if($query->rowCount()):
                            while($user=$query->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($user["fname"]) ?></td>
                            <td><?= htmlspecialchars($user["lname"]) ?></td>
                            <td><?= htmlspecialchars($user["email"]) ?></td>
                            <td>
                                <span class="role-badge <?= $user["role"] ?>">
                                    <?= $user["role"] ?>
                                </span>
                            </td>
                            <td style="display:flex;gap:8px;padding:14px 20px;">
                                <a href="users.php?selected_id=<?= $user["id"] ?>&crud_task=update" class="action-btn edit" title="Edit">
                                    <img src="../assets/update.png" alt="Edit">
                                </a>
                                <a href="users.php?selected_id=<?= $user["id"] ?>&crud_task=delete" class="action-btn delete" title="Delete">
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
                        <?php if($task=="Create") echo "ADD NEW USER";
                              elseif($task=="update") echo "EDIT USER";
                              else echo "DELETE USER"; ?>
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
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname"
                                       value="<?= htmlspecialchars($selected_user["fname"] ?? "") ?>"
                                       placeholder="John">
                            </div>
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname"
                                       value="<?= htmlspecialchars($selected_user["lname"] ?? "") ?>"
                                       placeholder="Doe">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email"
                                   value="<?= htmlspecialchars($selected_user["email"] ?? "") ?>"
                                   placeholder="user@example.com">
                        </div>
                        <?php if($task=="Create"): ?>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••">
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role">
                                <?php 
                                    $selectedRole = $selected_user["role"] ?? "customer";
                                    if($selectedRole == "admin"):
                                ?>
                                    <option value="admin" selected>Admin</option>
                                    <option value="customer">Customer</option>
                                <?php else: ?>
                                    <option value="admin">Admin</option>
                                    <option value="customer" selected>Customer</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <button class="btn-submit <?= $taskLower == 'delete' ? 'delete-mode' : '' ?>" type="submit">
                            <?= strtoupper($task) ?> USER
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>
