<?php
$page_title='Dashboard';
include 'includes/header.php';
$users=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
$restaurants=(int)$pdo->query('SELECT COUNT(*) FROM restaurants WHERE is_active=1')->fetchColumn();
$foods=(int)$pdo->query('SELECT COUNT(*) FROM foods')->fetchColumn();
$orders=(int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$pending=(int)$pdo->query("SELECT COUNT(*) FROM orders WHERE order_status IN ('Pending','Preparing')")->fetchColumn();
$revenue=(float)$pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE order_status <> 'Cancelled'")->fetchColumn();
?>
<section class="page-banner"><div class="container"><div class="eyebrow">Management overview</div><h1>Dashboard</h1><p class="muted">Welcome back, <?=esc($_SESSION['admin']['name'])?>.</p></div></section>
<section class="section"><div class="container"><div class="why-grid"><div class="why-item"><h3>Customers</h3><h2><?=$users?></h2></div><div class="why-item"><h3>Restaurants</h3><h2><?=$restaurants?></h2></div><div class="why-item"><h3>Foods</h3><h2><?=$foods?></h2></div><div class="why-item"><h3>Total Orders</h3><h2><?=$orders?></h2></div><div class="why-item"><h3>Pending Orders</h3><h2><?=$pending?></h2></div></div><div class="notice" style="margin-top:28px"><strong>Total Revenue:</strong> ₹<?=number_format($revenue,2)?></div></div></section>
<?php include 'includes/footer.php'; ?>
