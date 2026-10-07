<?php
require_once '../config/database.php'; require_once '../includes/functions.php';
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST; $food_id = (int) ($data['food_id'] ?? 0); $quantity = max(0, min(20, (int) ($data['quantity'] ?? 0)));
if ($quantity === 0) { unset($_SESSION['cart'][$food_id]); if (!cart_count()) unset($_SESSION['cart_restaurant_id']); } else $_SESSION['cart'][$food_id] = $quantity;
$totals = cart_totals($pdo); json_response(true, 'Cart updated.', ['count' => cart_count(), 'totals' => $totals]);