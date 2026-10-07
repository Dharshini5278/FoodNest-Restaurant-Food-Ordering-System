<?php

header('Content-Type: application/json; charset=utf-8');

// Get search text
$query = trim($_GET['q'] ?? $_POST['q'] ?? '');

if ($query === '') {
    echo json_encode([
        'success' => true,
        'suggestions' => []
    ]);
    exit;
}

// OpenStreetMap Nominatim search API
$url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
    'q' => $query,
    'format' => 'jsonv2',
    'addressdetails' => 1,
    'limit' => 5,
    'countrycodes' => 'in'
]);

$curl = curl_init($url);

curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'User-Agent: FoodNest/1.0 student project'
    ],
    CURLOPT_TIMEOUT => 15,
    CURLOPT_CONNECTTIMEOUT => 8
]);

$response = curl_exec($curl);
$error = curl_error($curl);
$status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

// Connection error
if ($response === false || $error !== '') {
    http_response_code(502);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to connect to location service.'
    ]);

    exit;
}

// HTTP error
if ($status < 200 || $status >= 300) {
    http_response_code($status ?: 502);

    echo json_encode([
        'success' => false,
        'message' => 'Location search failed.'
    ]);

    exit;
}

// Decode response
$data = json_decode($response, true);

if (!is_array($data)) {
    http_response_code(502);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid response from location service.'
    ]);

    exit;
}

// Prepare suggestions
$suggestions = [];

foreach ($data as $place) {

    if (!isset($place['lat'], $place['lon'])) {
        continue;
    }

    $suggestions[] = [
        'place_id' => 'osm_' . ($place['place_id'] ?? ''),
        'display_name' => $place['display_name'] ?? 'Location',
        'latitude' => (float) $place['lat'],
        'longitude' => (float) $place['lon'],
        'type' => $place['type'] ?? null
    ];
}

// Final response
echo json_encode([
    'success' => true,
    'source' => 'OpenStreetMap',
    'suggestions' => $suggestions
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
