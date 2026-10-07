<?php
require_once 'includes/../includes/header.php';
$id=(int)($_GET['id']??0); if($id>0){$stmt=$pdo->prepare('DELETE FROM restaurants WHERE id=?');$stmt->execute([$id]);} redirect('restaurants.php');
