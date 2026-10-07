<?php
require_once '../config/database.php'; require_once '../includes/functions.php';
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST; unset($_SESSION['cart'][(int) ($data['food_id'] ?? 0)]); if (!cart_count()) unset($_SESSION['cart_restaurant_id']); $totals = cart_totals($pdo); json_response(true, 'Item removed.', ['count' => cart_count(), 'totals' => $totals]);