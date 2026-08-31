<?php require_once 'php/data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Menu — Jhatphat 🍽️</title>
<link rel="stylesheet" href="css/style.css">
<style>
.page-hero {
  padding: 120px 0 60px;
  background: linear-gradient(135deg, var(--dark2) 0%, var(--dark) 100%);
  border-bottom: 1px solid rgba(232,67,26,0.15);
}
.menu-layout {
  display: grid; grid-template-columns: 280px 1fr;
  gap: 2rem; padding: 2.5rem 0 4rem; align-items: start;
}
/* ─── SIDEBAR ─── */
.sidebar {
  position: sticky; top: 90px;
  background: var(--dark2); border-radius: var(--radius2);
  border: 1px solid rgba(255,255,255,0.06); overflow: hidden;
}
.sidebar-header {
  padding: 1.2rem 1.5rem;
  background: rgba(232,67,26,0.1);
  border-bottom: 1px solid rgba(232,67,26,0.15);
  font-family: 'Baloo Da 2', cursive;
  font-size: 1rem; font-weight: 700;
  display: flex; align-items: center; gap: 0.5rem;
}
.sidebar-section { padding: 1.2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
.sidebar-section-title {
  font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;
  color: rgba(255,255,255,0.4); font-weight: 600; margin-bottom: 0.8rem;
}
.filter-item { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem; cursor: pointer; }
.filter-item input[type="checkbox"] {
  accent-color: var(--brand); width: 16px; height: 16px; cursor: pointer;
}
.filter-item label { font-size: 0.9rem; cursor: pointer; color: rgba(255,255,255,0.75); }
.filter-item:hover label { color: #fff; }

/* ─── MINI CART IN SIDEBAR ─── */
.mini-cart { padding: 1.2rem 1.5rem; }
.mini-cart-title {
  font-family: 'Baloo Da 2', cursive;
  font-size: 1rem; font-weight: 700;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 0.8rem;
}
.mini-cart-items { max-height: 200px; overflow-y: auto; margin-bottom: 0.8rem; }
.mini-cart-item {
  display: flex; align-items: center; gap: 0.6rem;
  padding: 0.4rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);
  font-size: 0.85rem;
}
.mini-cart-item .mci-name { flex: 1; color: rgba(255,255,255,.8); }
.mini-cart-item .mci-qty { color: var(--brand2); font-weight: 600; }
.mini-cart-item .mci-price { color: var(--accent2); font-weight: 700; }
.mini-cart-total {
  display: flex; justify-content: space-between;
  font-weight: 700; font-size: 0.95rem;
  padding-top: 0.6rem; border-top: 1px solid rgba(232,67,26,0.2);
  margin-bottom: 0.8rem;
}
.mini-cart-empty { color: rgba(255,255,255,.4); font-size: 0.85rem; text-align: center; padding: 1rem 0; }

/* ─── SEARCH BAR ─── */
.search-wrap {
  position: relative; margin-bottom: 1.5rem;
}
.search-wrap input {
  width: 100%; padding: 0.9rem 1.2rem 0.9rem 3rem;
  background: var(--dark2); border: 1.5px solid rgba(255,255,255,0.1);
  border-radius: 50px; color: #fff; font-size: 1rem; font-family: inherit;
  transition: var(--transition);
}
.search-wrap input:focus { outline: none; border-color: var(--brand2); box-shadow: 0 0 0 3px rgba(232,67,26,.15); }
.search-icon { position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); font-size: 1.1rem; }

/* ─── MENU GRID ─── */
.menu-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.2rem; }
.menu-card-wrap { transition: var(--transition); }

.food-card-actions {
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 1.2rem 1.2rem;
}

@media(max-width:1000px){ .menu-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:768px){
  .menu-layout { grid-template-columns: 1fr; }
  .sidebar { position: static; }
  .menu-grid { grid-template-columns: repeat(2,1fr); }
}
@media(max-width:480px){ .menu-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-brand">🍽️ Jhatphat</a>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="menu.php" class="active">Menu</a>
    <a href="cart.php">Cart</a>
    <?php if(isLoggedIn() && getCurrentUser()['is_admin']): ?><a href="admin.php">Admin</a><?php endif; ?>
  </div>
  <div class="nav-right">
    <a href="cart.php" class="cart-btn">
      🛒 Cart
      <?php $cnt = getCartCount(); ?>
      <span class="cart-badge" style="<?= $cnt===0?'display:none':'' ?>"><?= $cnt ?></span>
    </a>
    <?php if(isLoggedIn()): $u = getCurrentUser(); ?>
      <div class="user-pill"><div class="avatar"><?= strtoupper(substr($u['name'],0,1)) ?></div><?= htmlspecialchars($u['name']) ?></div>
      <a href="#" onclick="document.getElementById('lf').submit();return false" class="btn-auth btn-login">Logout</a>
      <form id="lf" method="POST" action="php/auth.php" style="display:none"><input type="hidden" name="action" value="logout"></form>
    <?php else: ?>
      <a href="login.php" class="btn-auth btn-login">Login</a>
      <a href="login.php?tab=signup" class="btn-auth btn-signup">Sign Up</a>
    <?php endif; ?>
  </div>
</nav>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="container">
    <div class="section-title" style="margin-bottom:0">
      <div class="badge">🍴 Full Menu</div>
      <h2>Authentic Bangladeshi Dishes</h2>
      <p>12 hand-picked dishes from the heart of Bangladesh. Filter, search, and order!</p>
    </div>
  </div>
</div>

<!-- MENU LAYOUT -->
<div class="container">
  <div class="menu-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-header">🔍 Filters</div>

      <div class="sidebar-section">
        <div class="sidebar-section-title">Category</div>
        <?php
        $cats = array_unique(array_column($menu_items, 'category'));
        $activeCat = $_GET['cat'] ?? '';
        foreach($cats as $cat): ?>
        <div class="filter-item">
          <input type="checkbox" class="filter-check cat-filter" id="cat-<?= $cat ?>" value="<?= $cat ?>" <?= $activeCat===$cat?'checked':'' ?>>
          <label for="cat-<?= $cat ?>"><?= $cat ?></label>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-section-title">Diet</div>
        <div class="filter-item">
          <input type="checkbox" class="filter-check" id="filter-veg">
          <label for="filter-veg">🥦 Vegetarian</label>
        </div>
        <div class="filter-item">
          <input type="checkbox" class="filter-check" id="filter-nonveg">
          <label for="filter-nonveg">🍖 Non-Vegetarian</label>
        </div>
      </div>

      <!-- MINI CART -->
      <div class="mini-cart">
        <div class="mini-cart-title">
          🛒 Your Order
          <span style="background:rgba(232,67,26,.2);color:var(--brand2);padding:.1rem .5rem;border-radius:50px;font-size:.8rem"><?= getCartCount() ?> items</span>
        </div>
        <?php $cart = getCart(); ?>
        <?php if(empty($cart)): ?>
          <div class="mini-cart-empty">Your cart is empty.<br>Add something tasty! 🍽️</div>
        <?php else: ?>
          <div class="mini-cart-items">
            <?php foreach($cart as $entry): ?>
            <div class="mini-cart-item">
              <span><?= $entry['item']['emoji'] ?></span>
              <span class="mci-name"><?= htmlspecialchars($entry['item']['name']) ?></span>
              <span class="mci-qty">×<?= $entry['qty'] ?></span>
              <span class="mci-price">৳<?= $entry['item']['price'] * $entry['qty'] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <div class="mini-cart-total">
            <span>Total</span>
            <span style="color:var(--accent2)">৳<?= getCartTotal() ?></span>
          </div>
          <a href="cart.php" class="btn btn-primary" style="width:100%;justify-content:center;padding:.7rem">Go to Cart →</a>
        <?php endif; ?>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main>
      <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" id="menu-search" placeholder="Search dishes… e.g. Biryani, Hilsa, Mishti" autocomplete="off">
      </div>

      <div class="menu-grid">
        <?php foreach($menu_items as $item): ?>
        <div class="menu-card-wrap" data-name="<?= strtolower(htmlspecialchars($item['name'])) ?>" data-cat="<?= htmlspecialchars($item['category']) ?>" data-veg="<?= $item['veg']?'1':'0' ?>">
          <div class="food-card">
            <div class="food-card-img">
              <span style="position:relative;z-index:1"><?= $item['emoji'] ?></span>
              <div class="food-card-overlay">
                <form method="POST" action="php/cart_action.php">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <input type="hidden" name="redirect" value="menu.php">
                  <button type="submit" class="btn btn-gold" style="padding:.6rem 1.4rem;font-size:.9rem">🛒 Quick Add</button>
                </form>
              </div>
            </div>
            <div class="food-card-body">
              <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:.3rem">
                <h3 class="food-card-title"><?= htmlspecialchars($item['name']) ?></h3>
                <span class="food-badge-veg <?= $item['veg']?'badge-veg':'badge-nonveg' ?>"><?= $item['veg']?'🥦':'🍖' ?></span>
              </div>
              <p class="food-card-desc"><?= htmlspecialchars($item['desc']) ?></p>
              <div class="food-card-footer" style="margin-bottom:.8rem">
                <span class="food-price">৳<?= $item['price'] ?></span>
                <span class="star-rating">★ <?= $item['rating'] ?></span>
              </div>
            </div>
            <div class="food-card-actions">
              <form method="POST" action="php/cart_action.php" class="qty-form" style="display:flex;align-items:center;gap:.8rem">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <input type="hidden" name="redirect" value="menu.php">
                <div class="qty-control">
                  <button type="button" class="qty-btn qty-minus">−</button>
                  <span class="qty-num">1</span>
                  <button type="button" class="qty-btn qty-plus">+</button>
                </div>
                <button type="submit" class="btn btn-primary" style="padding:.5rem 1.1rem;font-size:.85rem">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </main>

  </div>
</div>

<footer>
  <div class="footer-brand">🍽️ Jhatphat</div>
  <p>© 2025 Jhatphat Food Delivery — Dhaka, Bangladesh 🇧🇩</p>
</footer>

<script src="js/main.js"></script>
<script>
// Pre-activate cat filter from URL
const urlCat = new URLSearchParams(location.search).get('cat');
if (urlCat) {
  const cb = document.querySelector(`.cat-filter[value="${urlCat}"]`);
  if (cb) { cb.checked = true; filterMenu(); }
}
</script>
</body>
</html>
