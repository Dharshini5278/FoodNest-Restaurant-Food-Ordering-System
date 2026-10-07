<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

$latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
$longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);
if ($latitude === false || $longitude === false || $latitude === null || $longitude === null || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'A valid latitude and longitude are required.']);
    exit;
}

try {
    $stmt = $pdo->query('SELECT id,name,address,latitude,longitude,phone,image,cuisine,rating,opening_time,closing_time,is_active FROM restaurants WHERE is_active=1 ORDER BY id');
    $restaurants = [];
    foreach ($stmt->fetchAll() as $restaurant) {
        $restaurants[] = [
            'place_id' => 'foodnest_' . $restaurant['id'],
            'foodnest_restaurant_id' => (int) $restaurant['id'],
            'name' => $restaurant['name'],
            'formatted_address' => $restaurant['address'],
            'latitude' => (float) $restaurant['latitude'],
            'longitude' => (float) $restaurant['longitude'],
            'phone' => $restaurant['phone'],
            'image' => $restaurant['image'],
            'photo' => null,
            'cuisine' => $restaurant['cuisine'],
            'rating' => $restaurant['rating'] !== null ? (float) $restaurant['rating'] : null,
            'user_rating_count' => null,
            'business_status' => null,
            'open_now' => restaurant_is_open($restaurant['opening_time'], $restaurant['closing_time']),
            'delivery_time' => '25-35 mins',
            'distance_km' => haversine_distance($latitude, $longitude, $restaurant['latitude'], $restaurant['longitude']),
            'source' => 'FoodNest Database'
        ];
    }
    usort($restaurants, fn($left, $right) => $left['distance_km'] <=> $right['distance_km']);
    echo json_encode(['success' => true, 'source' => 'FoodNest Database', 'restaurants' => $restaurants, 'message' => $restaurants ? null : 'No restaurants found near this location.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Nearby restaurant search failed.']);
}
