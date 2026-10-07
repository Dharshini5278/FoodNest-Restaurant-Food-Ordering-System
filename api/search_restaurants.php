<?php
$_POST['search'] = $_POST['search'] ?? $_GET['search'] ?? '';
require __DIR__ . '/nearby_restaurants.php';
