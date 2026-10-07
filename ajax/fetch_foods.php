<?php
require_once '../config/database.php'; require_once '../includes/functions.php';
$category = (int) ($_GET['category'] ?? 0); $search = trim($_GET['search'] ?? '');
$sql = 'SELECT * FROM foods WHERE availability = 1'; $params = [];
if ($category > 0) { $sql .= ' AND category_id = ?'; $params[] = $category; }
if ($search !== '') { $sql .= ' AND (food_name LIKE ? OR description LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
$sql .= ' ORDER BY food_name'; $stmt = $pdo->prepare($sql); $stmt->execute($params); $foods = $stmt->fetchAll();
if (!$foods) { echo '<p class="muted">No food found for this search.</p>'; exit; }
foreach ($foods as $food): ?><article class="food-card"><img src="<?= esc($food['image']) ?>" alt="<?= esc($food['food_name']) ?>"><div class="food-info"><h3><?= esc($food['food_name']) ?></h3><p><?= esc($food['description']) ?></p><div class="food-meta"><span class="price">₹<?= number_format($food['price'], 2) ?></span><button class="small" onclick="addToCart(<?= $food['id'] ?>)">Add to Cart</button></div></div></article><?php endforeach; ?>