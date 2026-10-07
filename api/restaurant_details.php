<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/openstreetmap.php';
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$osm_id = trim($_GET['osm_id'] ?? $_POST['osm_id'] ?? '');
if ($id) {
    $stmt = $pdo->prepare('SELECT id,name,description,address,latitude,longitude,phone,image,cuisine,rating,opening_time,closing_time,is_active FROM restaurants WHERE id=? AND is_active=1');
    $stmt->execute([$id]);
    $restaurant = $stmt->fetch();
    if (!$restaurant) {
        echo json_encode(['success' => false, 'message' => 'Restaurant not found.']);
        exit;
    }
    $food_stmt = $pdo->prepare('SELECT f.id AS food_id,f.food_name,f.description,f.price,f.image,f.availability,c.category_name FROM restaurant_foods rf JOIN foods f ON f.id=rf.food_id JOIN categories c ON c.id=f.category_id WHERE rf.restaurant_id=? ORDER BY c.id,f.food_name');
    $food_stmt->execute([$id]);
    $restaurant['is_open'] = restaurant_is_open($restaurant['opening_time'], $restaurant['closing_time']);
    $restaurant['rating'] = $restaurant['rating'] !== null ? (float) $restaurant['rating'] : null;
    echo json_encode(['success' => true, 'source' => 'FoodNest Database', 'restaurant' => array_merge($restaurant, ['menu_available' => true, 'foods' => $food_stmt->fetchAll()])]);
    exit;
}

if (!preg_match('/^osm_(node|way|relation)_(\d+)$/', $osm_id, $parts)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'A valid FoodNest restaurant ID or OpenStreetMap place ID is required.']);
    exit;
}
$result = overpass_request('[out:json][timeout:20];nwr(id:' . $parts[2] . ');out center tags;');
if (!$result['ok'] || empty($result['data']['elements'][0])) {
    echo json_encode(['success' => false, 'message' => 'Restaurant details could not be loaded from OpenStreetMap.']);
    exit;
}
$element = $result['data']['elements'][0];
$tags = $element['tags'] ?? [];
$latitude = $element['lat'] ?? ($element['center']['lat'] ?? null);
$longitude = $element['lon'] ?? ($element['center']['lon'] ?? null);
$address_parts = array_filter([$tags['addr:housenumber'] ?? '', $tags['addr:street'] ?? '', $tags['addr:city'] ?? '', $tags['addr:state'] ?? '']);
echo json_encode(['success' => true, 'source' => 'OpenStreetMap', 'restaurant' => [
    'id' => null,
    'place_id' => $osm_id,
    'name' => $tags['name'] ?? 'Name not available',
    'description' => 'Restaurant information from OpenStreetMap.',
    'address' => $address_parts ? implode(', ', $address_parts) : 'Address not available from OpenStreetMap',
    'latitude' => $latitude,
    'longitude' => $longitude,
    'phone' => $tags['phone'] ?? null,
    'image' => filter_var($tags['image'] ?? '', FILTER_VALIDATE_URL) ? $tags['image'] : null,
    'cuisine' => $tags['cuisine'] ?? ($tags['amenity'] ?? 'Restaurant'),
    'rating' => null,
    'is_open' => null,
    'opening_hours' => $tags['opening_hours'] ?? null,
    'menu_available' => false,
    'foods' => [],
    'menu_message' => 'Menu information is currently unavailable.'
]], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
