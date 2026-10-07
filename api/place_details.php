<?php
if (!empty($_GET['place_id']) && empty($_GET['osm_id'])) $_GET['osm_id'] = $_GET['place_id'];
require __DIR__ . '/restaurant_details.php';
