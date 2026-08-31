<?php require_once 'php/data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jhatphat 🍽️ — Authentic Bangladeshi Food Delivery</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* ─── HERO ─── */
.hero {
  min-height: 100vh; position: relative; overflow: hidden;
  display: flex; align-items: center;
  background: radial-gradient(ellipse at 20% 50%, #3D0E00 0%, var(--dark) 60%);
}
#particles-canvas { position: absolute; inset: 0; pointer-events: none; z-index: 0; }
.hero-orb {
  position: absolute; border-radius: 50%;
  filter: blur(80px); pointer-events: none; z-index: 0;
}
.orb1 { width: 600px; height: 600px; background: rgba(232,67,26,0.12); top: -100px; right: -100px; }
.orb2 { width: 400px; height: 400px; background: rgba(255,107,53,0.08); bottom: -100px; left: -50px; }
.orb3 { width: 300px; height: 300px; background: rgba(255,215,0,0.06); top: 50%; right: 30%; }

.hero-content { position: relative; z-index: 1; flex: 1; max-width: 600px; }
.hero-badge {
  display: inline-flex; align-items: center; gap: 0.5rem;
  background: rgba(232,67,26,0.15); border: 1px solid rgba(232,67,26,0.3);
  padding: 0.4rem 1.2rem; border-radius: 50px; color: var(--brand2);
  font-size: 0.85rem; font-weight: 600; letter-spacing: 1px;
  text-transform: uppercase; margin-bottom: 1.5rem;
  animation: fadeInDown 0.8s ease 0.2s both;
}
.hero-title {
  font-family: 'Baloo Da 2', cursive;
  font-size: clamp(3rem, 6vw, 5rem); font-weight: 800;
  line-height: 1.0; margin-bottom: 1.5rem;
  animation: fadeInDown 0.8s ease 0.4s both;
}
.hero-title .highlight {
  background: linear-gradient(135deg, var(--brand2), var(--accent));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.hero-desc {
  font-size: 1.1rem; color: rgba(255,255,255,0.7);
  margin-bottom: 2rem; line-height: 1.7;
  animation: fadeInDown 0.8s ease 0.6s both;
}
.hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; animation: fadeInDown 0.8s ease 0.8s both; }

.hero-stats {
  display: flex; gap: 2rem; margin-top: 3rem;
  animation: fadeInDown 0.8s ease 1s both;
}
.stat { }
.stat-num {
  font-family: 'Baloo Da 2', cursive;
  font-size: 1.8rem; font-weight: 800; color: var(--accent2);
  line-height: 1;
}
.stat-label { font-size: 0.8rem; color: rgba(255,255,255,0.5); }

/* ─── FLOATING FOOD EMOJI ─── */
.hero-visual { position: relative; flex: 1; height: 500px; z-index: 1; }
.food-ring {
  position: absolute; border-radius: 50%;
  border: 1.5px dashed rgba(232,67,26,0.3);
  left: 50%; top: 50%; transform: translate(-50%,-50%);
}
.ring1 { width: 360px; height: 360px; animation: spinCW 30s linear infinite; }
.ring2 { width: 260px; height: 260px; animation: spinCCW 20s linear infinite; }
.ring3 { width: 460px; height: 460px; animation: spinCW 50s linear infinite; }

.center-emoji {
  position: absolute; left: 50%; top: 50%;
  transform: translate(-50%,-50%);
  font-size: 5rem; z-index: 2;
  animation: float 3s ease-in-out infinite;
  filter: drop-shadow(0 20px 40px rgba(232,67,26,0.5));
}

.orbit-emoji {
  position: absolute; font-size: 2rem;
  animation: float 3s ease-in-out infinite;
}
/* position emojis around the rings */
.oe1 { top: 0; left: 50%; transform: translateX(-50%); animation-delay: 0s; }
.oe2 { top: 50%; right: 0; transform: translateY(-50%); animation-delay: 0.5s; }
.oe3 { bottom: 0; left: 50%; transform: translateX(-50%); animation-delay: 1s; }
.oe4 { top: 50%; left: 0; transform: translateY(-50%); animation-delay: 1.5s; }
.oe5 { top: 15%; right: 15%; animation-delay: 0.3s; }
.oe6 { bottom: 15%; left: 15%; animation-delay: 0.8s; }

.info-card {
  position: absolute; background: rgba(26,10,0,0.85);
  border: 1px solid rgba(232,67,26,0.3); border-radius: 14px;
  padding: 0.6rem 1rem; backdrop-filter: blur(10px);
  animation: float 4s ease-in-out infinite;
  white-space: nowrap;
}
.info-card.card1 { top: 10%; left: 0; animation-delay: 0.2s; }
.info-card.card2 { bottom: 20%; right: 0; animation-delay: 1.2s; }
.info-card.card3 { bottom: 5%; left: 20%; animation-delay: 0.7s; }
.info-card-title { font-size: 0.7rem; color: rgba(255,255,255,0.5); }
.info-card-val { font-family: 'Baloo Da 2', cursive; font-size: 1rem; font-weight: 700; color: var(--accent2); }

/* ─── CATEGORIES ─── */
.categories { padding: 4rem 0; background: var(--dark2); }
.cat-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; }
.cat-card {
  background: var(--dark3); border: 1px solid rgba(255,255,255,0.06);
  border-radius: var(--radius2); padding: 1.5rem 1rem;
  text-align: center; text-decoration: none; color: #fff;
  transition: var(--transition); cursor: pointer;
}
.cat-card:hover {
  background: rgba(232,67,26,0.15); border-color: rgba(232,67,26,0.4);
  transform: translateY(-6px); color: #fff;
}
.cat-emoji { font-size: 2.5rem; margin-bottom: 0.5rem; }
.cat-name { font-size: 0.85rem; font-weight: 600; }

/* ─── FEATURED ─── */
.featured { padding: 5rem 0; }

/* ─── WHY US ─── */
.why-us { padding: 5rem 0; background: var(--dark2); }
.why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
.why-card {
  background: var(--dark3); border: 1px solid rgba(255,255,255,0.06);
  border-radius: var(--radius2); padding: 2rem 1.5rem; text-align: center;
  transition: var(--transition);
}
.why-card:hover { transform: translateY(-6px); border-color: rgba(232,67,26,0.3); }
.why-icon {
  width: 64px; height: 64px; border-radius: 16px;
  background: rgba(232,67,26,0.15); margin: 0 auto 1.2rem;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.8rem; border: 1px solid rgba(232,67,26,0.2);
}
.why-title { font-family: 'Baloo Da 2', cursive; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
.why-desc { font-size: 0.85rem; color: rgba(255,255,255,0.55); line-height: 1.6; }

@keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
@keyframes spinCW { from { transform: translate(-50%,-50%) rotate(0deg); } to { transform: translate(-50%,-50%) rotate(360deg); } }
@keyframes spinCCW { from { transform: translate(-50%,-50%) rotate(0deg); } to { transform: translate(-50%,-50%) rotate(-360deg); } }
@keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:none; } }

@media(max-width:900px){
  .hero { flex-direction: column; padding-top: 100px; text-align: center; }
  .hero-actions { justify-content: center; }
  .hero-stats { justify-content: center; }
  .hero-visual { display: none; }
  .cat-grid { grid-template-columns: repeat(3,1fr); }
  .why-grid { grid-template-columns: repeat(2,1fr); }
}
@media(max-width:480px){
  .cat-grid { grid-template-columns: repeat(2,1fr); }
  .why-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-brand">🍽️ Jhatphat</a>
  <div class="nav-links">
    <a href="index.php" class="active">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cart.php">Cart</a>
    <?php if(isLoggedIn() && getCurrentUser()['is_admin']): ?>
    <a href="admin.php">Admin</a>
    <?php endif; ?>
  </div>
  <div class="nav-right">
    <a href="cart.php" class="cart-btn">
      🛒 Cart
      <?php $cnt = getCartCount(); ?>
      <span class="cart-badge" style="<?= $cnt === 0 ? 'display:none' : '' ?>"><?= $cnt ?></span>
    </a>
    <?php if(isLoggedIn()): $u = getCurrentUser(); ?>
      <div class="user-pill">
        <div class="avatar"><?= strtoupper(substr($u['name'],0,1)) ?></div>
        <?= htmlspecialchars($u['name']) ?>
      </div>
      <a href="php/auth.php" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn-auth btn-login">Logout</a>
      <form id="logout-form" method="POST" action="php/auth.php" style="display:none">
        <input type="hidden" name="action" value="logout">
      </form>
    <?php else: ?>
      <a href="login.php?tab=login" class="btn-auth btn-login">Login</a>
      <a href="login.php?tab=signup" class="btn-auth btn-signup">Sign Up</a>
    <?php endif; ?>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <canvas id="particles-canvas"></canvas>
  <div class="hero-orb orb1"></div>
  <div class="hero-orb orb2"></div>
  <div class="hero-orb orb3"></div>
  <div class="container" style="display:flex;align-items:center;gap:3rem;width:100%;padding-top:80px;">
    <div class="hero-content">
      <div class="hero-badge">⚡ Jhatphat Delivery</div>
      <h1 class="hero-title">
        Authentic<br><span class="highlight">Bangladeshi</span><br>Food At Your Door
      </h1>
      <p class="hero-desc">
        From sizzling Kacchi Biryani to tangy Fuchka — real home-style flavours, delivered hot & fast anywhere in Dhaka.
      </p>
      <div class="hero-actions">
        <a href="menu.php" class="btn btn-primary">🍴 Order Now</a>
        <a href="#categories" class="btn btn-outline">Browse Menu</a>
      </div>
      <div class="hero-stats">
        <div class="stat"><div class="stat-num">50K+</div><div class="stat-label">Happy Customers</div></div>
        <div class="stat"><div class="stat-num">4.9★</div><div class="stat-label">Average Rating</div></div>
        <div class="stat"><div class="stat-num">30min</div><div class="stat-label">Avg Delivery</div></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="food-ring ring3"></div>
      <div class="food-ring ring1">
        <span class="orbit-emoji oe1">🍚</span>
        <span class="orbit-emoji oe2">🐟</span>
        <span class="orbit-emoji oe3">🍗</span>
        <span class="orbit-emoji oe4">🍮</span>
      </div>
      <div class="food-ring ring2">
        <span class="orbit-emoji oe5">🫙</span>
        <span class="orbit-emoji oe6">🥘</span>
      </div>
      <div class="center-emoji">🍽️</div>
      <div class="info-card card1">
        <div class="info-card-title">Today's Special</div>
        <div class="info-card-val">Kacchi Biryani ৳380</div>
      </div>
      <div class="info-card card2">
        <div class="info-card-title">Free Delivery</div>
        <div class="info-card-val">Orders over ৳500 🎉</div>
      </div>
      <div class="info-card card3">
        <div class="info-card-title">New on Menu</div>
        <div class="info-card-val">Prawn Malai Curry 🦐</div>
      </div>
    </div>
  </div>
</section>

<!-- CATEGORIES -->
<section class="categories" id="categories">
  <div class="container">
    <div class="section-title fade-up">
      <div class="badge">🗂️ Browse By Category</div>
      <h2>What Are You Craving?</h2>
    </div>
    <div class="cat-grid">
      <?php
      $cats = [
        ['emoji'=>'🍚','name'=>'Biryani','cat'=>'Biryani'],
        ['emoji'=>'🍲','name'=>'Curry','cat'=>'Curry'],
        ['emoji'=>'🐟','name'=>'Fish','cat'=>'Fish'],
        ['emoji'=>'🍮','name'=>'Dessert','cat'=>'Dessert'],
        ['emoji'=>'🫙','name'=>'Street Food','cat'=>'Street Food'],
        ['emoji'=>'🫕','name'=>'Rice','cat'=>'Rice'],
      ];
      foreach($cats as $c): ?>
      <a href="menu.php?cat=<?= urlencode($c['cat']) ?>" class="cat-card fade-up">
        <div class="cat-emoji"><?= $c['emoji'] ?></div>
        <div class="cat-name"><?= $c['name'] ?></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CHEF'S PICKS -->
<section class="featured">
  <div class="container">
    <div class="section-title fade-up">
      <div class="badge">👨‍🍳 Chef's Picks</div>
      <h2>Handpicked Just For You</h2>
      <p>Our most-loved dishes, crafted with authentic recipes passed down generations.</p>
    </div>
    <?php
    $shuffled = $menu_items;
    shuffle($shuffled);
    $picks = array_slice($shuffled, 0, 4);
    ?>
    <div class="grid-4">
      <?php foreach($picks as $item): ?>
      <div class="food-card fade-up">
        <div class="food-card-img">
          <span style="position:relative;z-index:1"><?= $item['emoji'] ?></span>
          <div class="food-card-overlay">
            <form method="POST" action="php/cart_action.php">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="id" value="<?= $item['id'] ?>">
              <input type="hidden" name="redirect" value="index.php">
              <button type="submit" class="btn btn-gold" style="padding:.6rem 1.4rem;font-size:.9rem">🛒 Add to Cart</button>
            </form>
            <a href="menu.php" class="btn btn-outline" style="padding:.5rem 1.2rem;font-size:.85rem">View All</a>
          </div>
        </div>
        <div class="food-card-body">
          <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:.4rem">
            <h3 class="food-card-title"><?= htmlspecialchars($item['name']) ?></h3>
            <span class="food-badge-veg <?= $item['veg'] ? 'badge-veg' : 'badge-nonveg' ?>"><?= $item['veg'] ? '🥦 Veg' : '🍖 Non-Veg' ?></span>
          </div>
          <p class="food-card-desc"><?= htmlspecialchars($item['desc']) ?></p>
          <div class="food-card-footer">
            <span class="food-price">৳<?= $item['price'] ?></span>
            <span class="star-rating">★ <?= $item['rating'] ?> <span style="color:rgba(255,255,255,.4);font-size:.75rem">(<?= number_format($item['orders']) ?>)</span></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem">
      <a href="menu.php" class="btn btn-primary">🍴 View Full Menu</a>
    </div>
  </div>
</section>

<!-- WHY US -->
<section class="why-us">
  <div class="container">
    <div class="section-title fade-up">
      <div class="badge">💎 Why Jhatphat</div>
      <h2>The Jhatphat Promise</h2>
    </div>
    <div class="why-grid">
      <?php
      $whys = [
        ['icon'=>'⚡','title'=>'Jhatphat Fast','desc'=>'Average delivery in under 30 minutes across Dhaka. Because hunger doesn\'t wait!'],
        ['icon'=>'👨‍🍳','title'=>'Authentic Recipes','desc'=>'Every dish follows age-old Bengali recipes cooked by experienced khansamas.'],
        ['icon'=>'🌿','title'=>'Fresh Ingredients','desc'=>'We source fresh local produce daily from Karwan Bazar — no compromise on quality.'],
        ['icon'=>'💳','title'=>'Easy Payment','desc'=>'Pay with bKash, Nagad, Card, or Cash — whatever suits you best.'],
      ];
      foreach($whys as $w): ?>
      <div class="why-card fade-up">
        <div class="why-icon"><?= $w['icon'] ?></div>
        <div class="why-title"><?= $w['title'] ?></div>
        <p class="why-desc"><?= $w['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-brand">🍽️ Jhatphat</div>
  <div class="footer-links">
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cart.php">Cart</a>
    <a href="login.php">Account</a>
  </div>
  <p>© 2025 Jhatphat Food Delivery — Dhaka, Bangladesh 🇧🇩 | Made with ❤️ for foodies</p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
