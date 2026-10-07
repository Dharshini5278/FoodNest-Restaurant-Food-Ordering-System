<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
$page_title = $page_title ?? 'FoodNest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page_title) ?> | FoodNest</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="logo" href="index.php">FoodNest <small>Good food. Simple ordering.</small></a>
        <button class="menu-toggle" type="button" aria-label="Open menu">Menu</button>
        <nav class="main-nav">
            <a href="index.php">Home</a><a href="menu.php">Menu</a><a href="index.php#restaurant-results">Explore</a>
            <?php if (!empty($_SESSION['user'])): ?><a href="my_orders.php">My Orders</a><?php endif; ?>
            <?php if (!empty($_SESSION['user'])): ?><span class="welcome">Hi, <?= esc($_SESSION['user']['name']) ?></span><a href="logout.php">Logout</a>
            <?php else: ?><a href="login.php">Login</a><a href="register.php">Register</a><?php endif; ?>
            <a class="cart-link" href="cart.php">Cart <span id="cart-count"><?= cart_count() ?></span></a>
        </nav>
    </div>
</header>
<main>