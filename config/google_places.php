<?php
const GOOGLE_GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json';
const GOOGLE_PLACES_URL = 'https://places.googleapis.com/v1';

function google_places_api_key() {
    $local_config = __DIR__ . '/google_places.local.php';

    if (is_file($local_config)) {
        require_once $local_config;
    }

    return defined('FOODNEST_GOOGLE_PLACES_KEY')
        ? FOODNEST_GOOGLE_PLACES_KEY
        : '';
}
function google_request($url, $method = 'GET', $body = null, $headers = []) {
    $key = google_places_api_key();
    if ($key === '') return ['ok' => false, 'status' => 500, 'error' => 'Google Places API key is not configured.'];
    $curl = curl_init($url);
    $request_headers = array_merge(['Accept: application/json', 'X-Goog-Api-Key: ' . $key], $headers);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $request_headers,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
    ]);
    if ($body !== null) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        $request_headers[] = 'Content-Type: application/json';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
    }
    $response = curl_exec($curl);
    $error = curl_error($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($response === false || $error !== '') return ['ok' => false, 'status' => 502, 'error' => 'Unable to reach Google Places.'];
    $decoded = json_decode($response, true);
    if (!is_array($decoded)) return ['ok' => false, 'status' => 502, 'error' => 'Google Places returned an invalid response.'];
    if ($status < 200 || $status >= 300) return ['ok' => false, 'status' => $status, 'error' => $decoded['error']['message'] ?? 'Google Places request failed.'];
    return ['ok' => true, 'status' => $status, 'data' => $decoded];
}

function google_photo_proxy_url($photo_name) {
    if (!is_string($photo_name) || !preg_match('#^places/[A-Za-z0-9_-]+/photos/[A-Za-z0-9_-]+$#', $photo_name)) return null;
    return 'api/restaurant_photo.php?name=' . rawurlencode($photo_name);
}
