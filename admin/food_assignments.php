<?php
require_once 'includes/../includes/header.php';
$restaurant_id = (int) ($_GET['restaurant_id'] ?? $_POST['restaurant_id'] ?? 0);
$restaurants = $pdo->query('SELECT id,name FROM restaurants ORDER BY name')->fetchAll();
$foods = $pdo->query('SELECT id,food_name FROM foods ORDER BY food_name')->fetchAll();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $restaurant_id > 0) {
    try {
        $pdo->beginTransaction();
        $pdo->prepare('DELETE FROM restaurant_foods WHERE restaurant_id=?')->execute([$restaurant_id]);
        $insert = $pdo->prepare('INSERT INTO restaurant_foods (restaurant_id,food_id) VALUES (?,?)');
        foreach (($_POST['food_ids'] ?? []) as $food_id) $insert->execute([$restaurant_id, (int) $food_id]);
        $pdo->commit();
        redirect('restaurants.php');
    } catch (PDOException $error_exception) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $error = 'Could not update the restaurant menu.';
    }
}
$assigned = [];
if ($restaurant_id) {
    $stmt = $pdo->prepare('SELECT food_id FROM restaurant_foods WHERE restaurant_id=?');
    $stmt->execute([$restaurant_id]);
    $assigned = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}
$page_title = 'Assign Restaurant Foods';
?>
<div class="form-shell"><h1>Assign menu foods</h1><?php if($error): ?><p class="error"><?=esc($error)?></p><?php endif; ?><form method="post"><label>Restaurant<select name="restaurant_id" onchange="this.form.submit()" required><option value="">Choose restaurant</option><?php foreach($restaurants as $restaurant): ?><option value="<?=$restaurant['id']?>" <?=$restaurant_id===$restaurant['id']?'selected':''?>><?=esc($restaurant['name'])?></option><?php endforeach; ?></select></label><?php if($restaurant_id): ?><fieldset><legend>Foods on this menu</legend><?php foreach($foods as $food): ?><label><input type="checkbox" name="food_ids[]" value="<?=$food['id']?>" <?=in_array((int)$food['id'],$assigned,true)?'checked':''?> style="width:auto"> <?=esc($food['food_name'])?></label><?php endforeach; ?></fieldset><div class="form-actions"><button type="submit">Save Menu</button> <a class="button secondary" href="restaurants.php">Cancel</a></div><?php endif; ?></form></div><?php include 'includes/footer.php'; ?>
