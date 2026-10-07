<?php
header('Content-Type: application/json; charset=utf-8');
$address = trim($_POST['address'] ?? $_GET['address'] ?? $_POST['q'] ?? $_GET['q'] ?? '');
if ($address === '') { echo json_encode(['success' => false, 'message' => 'Enter a location first.']); exit; }
$url = 'https://nominatim.openstreetmap.org/search?' . http_build_query(['q' => $address, 'format' => 'jsonv2', 'addressdetails' => 1, 'limit' => 1, 'countrycodes' => 'in']);
$curl = curl_init($url);
curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Accept: application/json','User-Agent: FoodNest/1.0 student project'], CURLOPT_TIMEOUT => 15, CURLOPT_CONNECTTIMEOUT => 8]);
$response = curl_exec($curl); $error = curl_error($curl); $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE); curl_close($curl);
if ($response === false || $error !== '' || $status < 200 || $status >= 300) { http_response_code(502); echo json_encode(['success' => false, 'message' => 'Location search failed.']); exit; }
$results = json_decode($response, true);
if (!is_array($results) || empty($results[0])) { echo json_encode(['success' => false, 'message' => 'Location not found.']); exit; }
echo json_encode(['success' => true, 'formatted_address' => $results[0]['display_name'], 'latitude' => (float) $results[0]['lat'], 'longitude' => (float) $results[0]['lon']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
