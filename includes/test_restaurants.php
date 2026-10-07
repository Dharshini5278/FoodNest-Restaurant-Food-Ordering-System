<?php

$url = "https://overpass-api.de/api/interpreter";

$query = '[out:json][timeout:20];nwr["amenity"="restaurant"](around:5000,9.45083,77.7976686);out center tags;';

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'data' => $query
    ],
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'User-Agent: FoodNest/1.0 student project'
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CONNECTTIMEOUT => 10
]);

$response = curl_exec($ch);

$error = curl_error($ch);

$status = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

curl_close($ch);

echo "<h2>FoodNest Restaurant API Test</h2>";

echo "<p><strong>HTTP Status:</strong> "
    . htmlspecialchars((string)$status)
    . "</p>";

if ($error) {

    echo "<p><strong>cURL Error:</strong> "
        . htmlspecialchars($error)
        . "</p>";

} else {

    echo "<h3>OpenStreetMap Response:</h3>";

    echo "<pre style='white-space: pre-wrap; word-wrap: break-word;'>";

    echo htmlspecialchars($response);

    echo "</pre>";
}

?>