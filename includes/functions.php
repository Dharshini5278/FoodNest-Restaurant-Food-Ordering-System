<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esc($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function cart_count() {
    return array_sum($_SESSION['cart'] ?? []);
}

function cart_items(PDO $pdo) {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [];
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE id IN ($placeholders) AND availability = 1");
    $stmt->execute($ids);
    $items = [];
    foreach ($stmt->fetchAll() as $food) {
        $food['quantity'] = (int) ($cart[$food['id']] ?? 0);
        $food['subtotal'] = $food['quantity'] * (float) $food['price'];
        $items[] = $food;
    }
    return $items;
}

function cart_totals(PDO $pdo) {
    $subtotal = 0;
    foreach (cart_items($pdo) as $item) $subtotal += $item['subtotal'];
    $delivery = $subtotal > 0 ? 40 : 0;
    return ['subtotal' => $subtotal, 'delivery' => $delivery, 'total' => $subtotal + $delivery];
}

function haversine_distance($latitude1, $longitude1, $latitude2, $longitude2) {
    if ($latitude1 === null || $longitude1 === null || $latitude2 === null || $longitude2 === null) return null;
    $earth_radius = 6371;
    $lat_delta = deg2rad((float) $latitude2 - (float) $latitude1);
    $lon_delta = deg2rad((float) $longitude2 - (float) $longitude1);
    $a = sin($lat_delta / 2) ** 2 + cos(deg2rad((float) $latitude1)) * cos(deg2rad((float) $latitude2)) * sin($lon_delta / 2) ** 2;
    return round($earth_radius * 2 * asin(min(1, sqrt($a))), 1);
}

function restaurant_is_open($opening_time, $closing_time, $timestamp = null) {
    if (!$opening_time || !$closing_time) return null;
    $now = date('H:i:s', $timestamp ?? time());
    if ($opening_time <= $closing_time) return $now >= $opening_time && $now <= $closing_time;
    return $now >= $opening_time || $now <= $closing_time;
}

function json_response($success, $message, $extra = []) {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}
?>