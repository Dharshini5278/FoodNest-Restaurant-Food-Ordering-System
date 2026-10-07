<?php
require_once '../config/database.php'; require_once '../includes/functions.php';
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST; $food_id = (int) ($data['food_id'] ?? 0); $restaurant_id = (int) ($data['restaurant_id'] ?? 0);
$stmt = $pdo->prepare('SELECT f.id, rf.restaurant_id FROM foods f LEFT JOIN restaurant_foods rf ON rf.food_id=f.id AND (? = 0 OR rf.restaurant_id=?) WHERE f.id=? AND f.availability=1 LIMIT 1'); $stmt->execute([$restaurant_id, $restaurant_id, $food_id]);
$food = $stmt->fetch();
if (!$food) json_response(false, 'This food is currently unavailable.');
$restaurant_id = $restaurant_id ?: (int) $food['restaurant_id'];
if (!empty($_SESSION['cart']) && !empty($_SESSION['cart_restaurant_id']) && $_SESSION['cart_restaurant_id'] !== $restaurant_id) json_response(false, 'Your cart can contain food from one restaurant at a time.');
$_SESSION['cart_restaurant_id'] = $restaurant_id;
$_SESSION['cart'][$food_id] = ($_SESSION['cart'][$food_id] ?? 0) + 1; json_response(true, 'Food added to cart.', ['count' => cart_count()]);