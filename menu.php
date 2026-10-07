<?php
require_once 'config/database.php'; require_once 'includes/functions.php'; $page_title = 'Menu'; include 'includes/header.php';
$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();
?>
<section class="page-banner"><div class="container"><div class="eyebrow">Choose your meal</div><h1>Our Menu</h1><p class="muted">Fresh favourites, ready when you are.</p></div></section>
<section class="section"><div class="container"><input class="search-box" id="food-search" type="search" placeholder="Search food..." aria-label="Search food"><div class="filter-bar"><button class="active" data-category="0">All</button><?php foreach ($categories as $category): ?><button data-category="<?= $category['id'] ?>"><?= esc($category['category_name']) ?></button><?php endforeach; ?></div><div id="food-results" class="food-grid"><p class="muted">Loading menu...</p></div></div></section>
<script>
let selectedCategory = 0;
async function loadFoods() { const search = document.querySelector('#food-search').value; const response = await fetch(`ajax/fetch_foods.php?category=${selectedCategory}&search=${encodeURIComponent(search)}`); document.querySelector('#food-results').innerHTML = await response.text(); }
document.querySelectorAll('[data-category]').forEach(button => button.addEventListener('click', () => { document.querySelectorAll('[data-category]').forEach(item => item.classList.remove('active')); button.classList.add('active'); selectedCategory = button.dataset.category; loadFoods(); }));
document.querySelector('#food-search').addEventListener('input', loadFoods); loadFoods();
</script>
<?php include 'includes/footer.php'; ?>