<?php
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
$address=trim($_POST['address']??'');$latitude=filter_input(INPUT_POST,'latitude',FILTER_VALIDATE_FLOAT);$longitude=filter_input(INPUT_POST,'longitude',FILTER_VALIDATE_FLOAT);
if(!$address||$latitude===false||$longitude===false||$latitude===null||$longitude===null){http_response_code(422);echo json_encode(['success'=>false,'message'=>'A selected location and coordinates are required.']);exit;}
$_SESSION['delivery_location']=['address'=>$address,'latitude'=>(float)$latitude,'longitude'=>(float)$longitude];echo json_encode(['success'=>true]);
