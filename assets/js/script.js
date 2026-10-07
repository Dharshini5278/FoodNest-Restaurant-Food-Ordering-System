document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-nav');
    if (toggle) toggle.addEventListener('click', () => nav.classList.toggle('open'));
});

function showToast(message, isError = false) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    if (isError) toast.style.background = '#a9431b';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2600);
}

async function postCart(action, data) {
    const response = await fetch(`ajax/${action}.php`, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    return response.json();
}

async function addToCart(foodId, restaurantId = 0) {
    try { const result = await postCart('add_to_cart', {food_id: foodId, restaurant_id: restaurantId}); showToast(result.message, !result.success); if (result.success) document.querySelector('#cart-count').textContent = result.count; }
    catch (error) { showToast('Could not update cart.', true); }
}

function validatePasswords() {
    const password = document.querySelector('#password');
    const confirm = document.querySelector('#confirm_password');
    if (password && confirm && password.value !== confirm.value) { confirm.setCustomValidity('Passwords do not match.'); return false; }
    if (confirm) confirm.setCustomValidity(''); return true;
}
