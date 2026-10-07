<?php
function osm_request_json($url, $body = null) {
    $curl = curl_init($url);
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'User-Agent: FoodNest/1.0 student project'],
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 5
    ];
    if ($body !== null) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $body;
    }
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $error = curl_error($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($response === false || $error !== '' || $status < 200 || $status >= 300) return ['ok' => false, 'status' => $status ?: 502, 'error' => $error ?: 'OpenStreetMap service request failed.'];
    $data = json_decode($response, true);
    return is_array($data) ? ['ok' => true, 'status' => $status, 'data' => $data] : ['ok' => false, 'status' => 502, 'error' => 'OpenStreetMap returned an invalid response.'];
}

function overpass_request($query) {
    $servers = ['https://overpass-api.de/api/interpreter', 'https://overpass.kumi.systems/api/interpreter'];
    $last_error = 'Nearby restaurant search failed.';
    foreach ($servers as $server) {
        $result = osm_request_json($server, ['data' => $query]);
        if ($result['ok']) return $result;
        $last_error = $result['error'];
    }
    return ['ok' => false, 'status' => 502, 'error' => $last_error];
}
