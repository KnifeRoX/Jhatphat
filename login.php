<?php require_once 'php/data.php'; ?>
<?php if(isLoggedIn()) { header('Location: index.php'); exit; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login / Sign Up — Jhatphat 🍽️</title>
<link rel="stylesheet" href="css/style.css">
<style>
body { background: radial-gradient(ellipse at 30% 50%, #2D0800 0%, var(--dark) 70%); min-height: 100vh; }
.auth-page {
  min-height: 100vh; display: flex; align-items: center; justify-content: center;
  padding: 6rem 1rem 3rem;
}
.auth-container {
  width: 100%; max-width: 480px;
  background: var(--dark2); border: 1px solid rgba(232,67,26,.2);
  border-radius: 28px; overflow: hidden;
  box-shadow: 0 30px 80px rgba(0,0,0,.5);
}
.auth-brand {
  text-align: center; padding: 2.5rem 2rem 1.5rem;
  background: linear-gradient(135deg, rgba(232,67,26,.15), rgba(255,107,53,.05));
  border-bottom: 1px solid rgba(232,67,26,.15);
}
.auth-logo {
  font-family: 'Baloo Da 2', cursive; font-size: 2.5rem; font-weight: 800;
  background: linear-gradient(135deg, var(--brand2), var(--accent));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.auth-tagline { color: rgba(255,255,255,.5); font-size: .9rem; margin-top: .3rem; }

/* TABS */
.auth-tabs {
  display: flex; background: rgba(0,0,0,.3);
}
.auth-tab {
  flex: 1; padding: .9rem; text-align: center;
  cursor: pointer; font-weight: 700; font-size: .95rem;
  color: rgba(255,255,255,.5); border: none; background: transparent;
  transition: var(--transition); border-bottom: 2px solid transparent;
  font-family: 'Baloo Da 2', cursive;
}
.auth-tab:hover { color: rgba(255,255,255,.8); }
.auth-tab.active {
  color: var(--brand2); border-bottom-color: var(--brand2);
  background: rgba(232,67,26,.07);
}

/* PANELS */
.auth-panel { display: none; padding: 2rem; }
.auth-panel.active { display: block; }

.divider {
  display: flex; align-items: center; gap: 1rem;
  margin: 1rem 0; color: rgba(255,255,255,.3); font-size: .8rem;
}
.divider::before, .divider::after {
  content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.1);
}
.social-btn {
  width: 100%; padding: .75rem; border-radius: var(--radius);
  background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
  color: #fff; font-family: inherit; font-size: .95rem; font-weight: 600;
  cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: .7rem;
}
.social-btn:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); }

.auth-footer-text { text-align: center; font-size: .85rem; color: rgba(255,255,255,.5); margin-top: 1rem; }
.auth-footer-text a { color: var(--brand2); text-decoration: none; font-weight: 600; }

/* DECORATIVE ORBS */
.bg-orb {
  position: fixed; border-radius: 50%; pointer-events: none; z-index: -1;
  filter: blur(80px);
}
.bg-orb1 { width: 500px; height: 500px; background: rgba(232,67,26,.08); top: -100px; right: -100px; }
.bg-orb2 { width: 300px; height: 300px; background: rgba(255,215,0,.05); bottom: -50px; left: -50px; }
</style>
</head>
<body>
<div class="bg-orb bg-orb1"></div>
<div class="bg-orb bg-orb2"></div>

<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-brand">🍽️ Jhatphat</a>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cart.php">Cart</a>
  </div>
  <div class="nav-right">
    <a href="cart.php" class="cart-btn">🛒 Cart
      <?php $cnt = getCartCount(); ?>
      <span class="cart-badge" style="<?= $cnt===0?'display:none':'' ?>"><?= $cnt ?></span>
    </a>
  </div>
</nav>

<div class="auth-page">
  <div class="auth-container">
    <div class="auth-brand">
      <div class="auth-logo">🍽️ Jhatphat</div>
      <p class="auth-tagline">Your favourite Bangladeshi food, delivered fast</p>
    </div>

    <?php if(isset($_SESSION['auth_error'])): ?>
    <div style="padding:1rem 2rem 0">
      <div class="alert alert-error"><?= $_SESSION['auth_error']; unset($_SESSION['auth_error']); ?></div>
    </div>
    <?php endif; ?>

    <div class="auth-tabs">
      <button class="auth-tab active" data-tab="login">🔐 Login</button>
      <button class="auth-tab" data-tab="signup">✨ Sign Up</button>
    </div>

    <!-- LOGIN PANEL -->
    <div class="auth-panel active" id="panel-login">
      <form method="POST" action="php/auth.php">
        <input type="hidden" name="action" value="login">
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" required placeholder="you@example.com" autocomplete="email">
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required placeholder="••••••••" autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.9rem;margin-top:.5rem">
          🔐 Login to Jhatphat
        </button>
      </form>
      <div class="divider">or continue with</div>
      <div style="padding:0 0 .5rem">
        <p style="font-size:.8rem;color:rgba(255,255,255,.4);text-align:center;margin-bottom:.5rem">Demo accounts:</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;font-size:.78rem;text-align:center;color:rgba(255,255,255,.5)">
          <div style="background:rgba(255,255,255,.05);padding:.5rem;border-radius:8px">
            <strong style="color:var(--brand2)">User</strong><br>rahim@example.com<br>pass123
          </div>
          <div style="background:rgba(255,255,255,.05);padding:.5rem;border-radius:8px">
            <strong style="color:var(--accent2)">Admin</strong><br>admin@jhatphat.com<br>admin123
          </div>
        </div>
      </div>
      <p class="auth-footer-text">Don't have an account? <a href="#" onclick="document.querySelector('[data-tab=signup]').click();return false">Sign Up</a></p>
    </div>

    <!-- SIGNUP PANEL -->
    <div class="auth-panel" id="panel-signup">
      <form method="POST" action="php/auth.php">
        <input type="hidden" name="action" value="signup">
        <div class="form-group">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required placeholder="Rahim Uddin" autocomplete="name">
        </div>
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" required placeholder="you@example.com" autocomplete="email">
        </div>
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX">
        </div>
        <div class="form-group">
          <label class="form-label">Delivery Address</label>
          <input type="text" name="address" class="form-control" placeholder="House/Road/Area, Dhaka">
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required placeholder="Minimum 6 characters" autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.9rem;margin-top:.5rem">
          ✨ Create My Account
        </button>
      </form>
      <p class="auth-footer-text">Already have an account? <a href="#" onclick="document.querySelector('[data-tab=login]').click();return false">Login</a></p>
    </div>
  </div>
</div>

<script src="js/main.js"></script>
</body>
</html>
