<?php require_once 'php/data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Cart — Jhatphat 🛒</title>
<link rel="stylesheet" href="css/style.css">
<style>
.page-hero { padding: 120px 0 50px; background: linear-gradient(135deg, var(--dark2), var(--dark)); border-bottom: 1px solid rgba(232,67,26,0.15); }
.cart-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; padding: 2.5rem 0 5rem; align-items: start; }


.cart-table { width: 100%; }
.cart-row {
  display: grid; grid-template-columns: auto 1fr auto auto;
  align-items: center; gap: 1rem;
  padding: 1rem 1.5rem; background: var(--dark2);
  border: 1px solid rgba(255,255,255,.05); border-radius: var(--radius);
  margin-bottom: 0.8rem; transition: var(--transition);
}
.cart-row:hover { border-color: rgba(232,67,26,.2); }
.cart-emoji { font-size: 2.5rem; }
.cart-item-name { font-family: 'Baloo Da 2', cursive; font-size: 1.1rem; font-weight: 700; }
.cart-item-sub { font-size: 0.85rem; color: rgba(255,255,255,.5); margin-top: .2rem; }
.cart-item-price { font-family: 'Baloo Da 2', cursive; font-size: 1.2rem; font-weight: 800; color: var(--accent2); }
.remove-btn {
  background: rgba(239,68,68,.15); border: 1px solid rgba(239,68,68,.3);
  color: #ef4444; padding: .4rem .7rem; border-radius: 8px;
  cursor: pointer; font-size: .8rem; transition: var(--transition); white-space: nowrap;
}
.remove-btn:hover { background: rgba(239,68,68,.3); }

.empty-cart { text-align: center; padding: 4rem 2rem; background: var(--dark2); border-radius: var(--radius2); }
.empty-cart .empty-emoji { font-size: 4rem; margin-bottom: 1rem; }
.empty-cart h3 { font-family: 'Baloo Da 2', cursive; font-size: 1.5rem; margin-bottom: .5rem; }
.empty-cart p { color: rgba(255,255,255,.5); margin-bottom: 1.5rem; }

.order-sidebar { position: sticky; top: 90px; }
.order-panel {
  background: var(--dark2); border: 1px solid rgba(255,255,255,.06);
  border-radius: var(--radius2); padding: 1.8rem; margin-bottom: 1.2rem;
}
.order-panel-title {
  font-family: 'Baloo Da 2', cursive; font-size: 1.1rem; font-weight: 700;
  margin-bottom: 1.2rem; padding-bottom: .8rem;
  border-bottom: 1px solid rgba(255,255,255,.07);
  display: flex; align-items: center; gap: .5rem;
}
.summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: .6rem; font-size: .9rem; }
.summary-row .label { color: rgba(255,255,255,.6); }
.summary-row .val { font-weight: 600; }
.summary-row.hidden-row { display: none; }
.summary-total {
  display: flex; justify-content: space-between; align-items: center;
  padding-top: .8rem; margin-top: .5rem;
  border-top: 1px solid rgba(232,67,26,.3);
  font-family: 'Baloo Da 2', cursive; font-size: 1.3rem; font-weight: 800;
}
.summary-total .val { color: var(--accent2); }

.free-delivery-banner {
  background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.3);
  border-radius: var(--radius); padding: .7rem 1rem; margin-bottom: 1rem;
  font-size: .85rem; color: #86efac; text-align: center;
}


.payment-method {
  display: flex; align-items: center; gap: 1rem;
  padding: 1rem; border-radius: var(--radius);
  border: 1.5px solid rgba(255,255,255,.08); margin-bottom: .7rem;
  cursor: pointer; transition: var(--transition);
}
.payment-method:hover, .payment-method.active {
  border-color: var(--brand); background: rgba(232,67,26,.08);
}
.payment-method input[type="radio"] { accent-color: var(--brand); }
.pm-icon { font-size: 1.5rem; }
.pm-info .pm-name { font-weight: 700; font-size: .95rem; }
.pm-info .pm-desc { font-size: .78rem; color: rgba(255,255,255,.4); }
.payment-detail {
  display: none; padding: .8rem; background: rgba(255,255,255,.04);
  border-radius: var(--radius); margin-top: .3rem; margin-bottom: .5rem;
}


.speed-method {
  display: flex; align-items: center; gap: 1rem;
  padding: 1rem; border-radius: var(--radius);
  border: 1.5px solid rgba(255,255,255,.08); margin-bottom: .7rem;
  cursor: pointer; transition: var(--transition);
}
.speed-method:hover, .speed-method.active { border-color: var(--brand); background: rgba(232,67,26,.08); }
.speed-method input[type="radio"] { accent-color: var(--brand); }
.speed-icon { font-size: 1.5rem; }
.speed-info { flex: 1; }
.speed-info .speed-name { font-weight: 700; font-size: .95rem; }
.speed-info .speed-desc { font-size: .78rem; color: rgba(255,255,255,.4); }
.speed-price { font-family: 'Baloo Da 2', cursive; font-weight: 800; font-size: .95rem; white-space: nowrap; }
.speed-price.extra { color: var(--accent2); }
.speed-price.discount { color: #22c55e; }
.speed-price.free { color: rgba(255,255,255,.5); }
.schedule-detail {
  display: none; padding: .8rem; background: rgba(255,255,255,.04);
  border-radius: var(--radius); margin-top: .3rem; margin-bottom: .5rem;
}


.utensils-row {
  display: flex; align-items: center; justify-content: space-between; gap: 1rem;
  padding: 1rem; border-radius: var(--radius);
  border: 1.5px solid rgba(255,255,255,.08);
}
.utensils-info { display: flex; align-items: center; gap: .8rem; }
.utensils-info .u-icon { font-size: 1.5rem; }
.utensils-info .u-name { font-weight: 700; font-size: .95rem; }
.utensils-info .u-desc { font-size: .78rem; color: rgba(255,255,255,.4); }
.utensils-fee { font-size: .85rem; color: var(--accent2); font-weight: 700; white-space: nowrap; }

.toggle-switch { position: relative; display: inline-block; width: 46px; height: 26px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
  position: absolute; cursor: pointer; inset: 0;
  background-color: rgba(255,255,255,.15); transition: var(--transition); border-radius: 34px;
}
.toggle-slider:before {
  position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px;
  background-color: #fff; transition: var(--transition); border-radius: 50%;
}
.toggle-switch input:checked + .toggle-slider { background-color: var(--brand); }
.toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }

.tip-options { display: grid; grid-template-columns: repeat(4, 1fr); gap: .6rem; margin-bottom: .8rem; }
.tip-btn {
  padding: .7rem .3rem; text-align: center; border-radius: var(--radius);
  border: 1.5px solid rgba(255,255,255,.08); background: transparent;
  color: #fff; cursor: pointer; font-family: 'Baloo Da 2', cursive; font-weight: 700; font-size: .95rem;
  transition: var(--transition);
}
.tip-btn:hover { border-color: var(--brand); }
.tip-btn.active { border-color: var(--brand); background: rgba(232,67,26,.15); color: var(--accent2); }
.tip-note { font-size: .78rem; color: rgba(255,255,255,.4); margin-bottom: .8rem; text-align: center; }
.tip-custom { display: none; }

.success-icon { font-size: 4rem; margin-bottom: 1rem; animation: bounceIn .5s ease; }
.success-title { font-family: 'Baloo Da 2', cursive; font-size: 2rem; font-weight: 800; margin-bottom: .5rem; }
.order-id-box {
  background: rgba(232,67,26,.15); border: 1px dashed rgba(232,67,26,.4);
  border-radius: var(--radius); padding: .8rem 1.5rem; margin: 1rem auto;
  font-family: 'Baloo Da 2', cursive; font-size: 1.4rem; font-weight: 800;
  color: var(--accent2); letter-spacing: 2px; display: inline-block;
}
#confetti-canvas { position: fixed; inset: 0; pointer-events: none; z-index: 99999; }

@keyframes bounceIn { 0%{transform:scale(.5);opacity:0} 70%{transform:scale(1.1)} 100%{transform:scale(1);opacity:1} }
@media(max-width:900px){ .cart-layout{grid-template-columns:1fr;} .order-sidebar{position:static;} }
@media(max-width:600px){ .cart-row{grid-template-columns:auto 1fr; row-gap:.5rem;} .cart-item-price{grid-column:2;} .tip-options{grid-template-columns:repeat(2,1fr);} }
</style>
</head>
<body>

<canvas id="confetti-canvas"></canvas>

<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-brand">🍽️ Jhatphat</a>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cart.php" class="active">Cart</a>
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

<div class="page-hero">
  <div class="container">
    <div class="section-title" style="margin-bottom:0">
      <div class="badge">🛒 Your Order</div>
      <h2>Review & Checkout</h2>
    </div>
  </div>
</div>

<?php
$cart = getCart();
$total = getCartTotal();
$delivery = $total >= 500 ? 0 : 50;
$grand = $total + $delivery;


$EXPRESS_FEE = 40;      
$SCHEDULE_DISCOUNT = 15; 
$UTENSILS_FEE = 5;      
$TIP_OPTIONS = [0, 20, 50, 100];
?>

<div class="container">
  <div class="cart-layout">

    <div>

      <?php if(empty($cart)): ?>
      <div class="empty-cart">
        <div class="empty-emoji">🛒</div>
        <h3>Your cart is empty!</h3>
        <p>Looks like you haven't added anything yet. Explore our menu!</p>
        <a href="menu.php" class="btn btn-primary">🍴 Browse Menu</a>
      </div>
      <?php else: ?>
      <div style="margin-bottom:2rem">
        <h3 style="font-family:'Baloo Da 2',cursive;font-size:1.2rem;margin-bottom:1rem;opacity:.7">🧾 <?= count($cart) ?> item(s) in your cart</h3>
        <div class="cart-table">
          <?php foreach($cart as $id => $entry): $item=$entry['item']; $qty=$entry['qty']; ?>
          <div class="cart-row">
            <div class="cart-emoji"><?= $item['emoji'] ?></div>
            <div>
              <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
              <div class="cart-item-sub">৳<?= $item['price'] ?> each</div>
            </div>
            <form method="POST" action="php/cart_action.php" style="display:flex;align-items:center;gap:.8rem">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= $id ?>">
              <input type="hidden" name="qty" value="<?= $qty ?>" id="qty-<?= $id ?>">
              <div class="qty-control cart-qty-control">
                <button type="button" class="qty-btn qty-minus">−</button>
                <span class="qty-num"><?= $qty ?></span>
                <button type="button" class="qty-btn qty-plus">+</button>
              </div>
            </form>
            <div>
              <div class="cart-item-price">৳<?= $item['price'] * $qty ?></div>
              <form method="POST" action="php/cart_action.php" style="margin-top:.4rem">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit" class="remove-btn">✕ Remove</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <form method="POST" action="php/cart_action.php" id="checkout-form">
        <input type="hidden" name="action" value="place_order">
        <input type="hidden" name="payment" value="cod" id="payment-hidden">
        <input type="hidden" name="payment_detail" value="" id="payment-detail-hidden">
        <input type="hidden" name="delivery_speed" value="standard" id="speed-hidden">
        <input type="hidden" name="schedule_time" value="" id="schedule-hidden">
        <input type="hidden" name="utensils" value="0" id="utensils-hidden">
        <input type="hidden" name="tip" value="0" id="tip-hidden">

        <div class="order-panel">
          <div class="order-panel-title">📍 Delivery Details</div>
          <?php $u = getCurrentUser(); ?>

          <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($u['name'] ?? '') ?>" required placeholder="Your name">
          </div>
          <div class="grid-2" style="gap:1rem">
            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($u['phone'] ?? '') ?>" required placeholder="01XXXXXXXXX">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Delivery Address</label>
            <textarea name="address" class="form-control" rows="3" required placeholder="House/Road/Area, Dhaka"><?= htmlspecialchars($u['address'] ?? '') ?></textarea>
          </div>
        </div>

      
        <div class="order-panel">
          <div class="order-panel-title">🛵 Delivery Speed</div>

          <div class="speed-method active" data-speed="standard" data-fee="0">
            <input type="radio" name="speed" value="standard" checked>
            <div class="speed-icon">🛵</div>
            <div class="speed-info"><div class="speed-name">Standard</div><div class="speed-desc">Usual 30–35 min delivery</div></div>
            <div class="speed-price free">FREE</div>
          </div>

          <div class="speed-method" data-speed="express" data-fee="<?= $EXPRESS_FEE ?>">
            <input type="radio" name="speed" value="express">
            <div class="speed-icon">⚡</div>
            <div class="speed-info"><div class="speed-name">Express</div><div class="speed-desc">Priority rider, ~15–20 min</div></div>
            <div class="speed-price extra">+৳<?= $EXPRESS_FEE ?></div>
          </div>

          <div class="speed-method" data-speed="schedule" data-fee="-<?= $SCHEDULE_DISCOUNT ?>">
            <input type="radio" name="speed" value="schedule">
            <div class="speed-icon">🕒</div>
            <div class="speed-info"><div class="speed-name">Schedule / Later</div><div class="speed-desc">Pick an off-peak time slot & save</div></div>
            <div class="speed-price discount">−৳<?= $SCHEDULE_DISCOUNT ?></div>
          </div>
          <div class="schedule-detail" id="detail-schedule">
            <label class="form-label">Preferred delivery time</label>
            <input type="time" class="form-control" id="schedule-time-input">
          </div>
        </div>

        <div class="order-panel">
          <div class="order-panel-title">🍴 Utensils & Rider Tip</div>

          <div class="utensils-row" style="margin-bottom:1.4rem">
            <div class="utensils-info">
              <div class="u-icon">🍴</div>
              <div>
                <div class="u-name">Include disposable utensils</div>
                <div class="u-desc">Fork, spoon & napkins</div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:.8rem">
              <span class="utensils-fee">+৳<?= $UTENSILS_FEE ?></span>
              <label class="toggle-switch">
                <input type="checkbox" id="utensils-toggle">
                <span class="toggle-slider"></span>
              </label>
            </div>
          </div>

          <div class="pm-info" style="margin-bottom:.7rem">
            <div class="pm-name">💙 Tip your rider</div>
            <div class="pm-desc" style="margin-top:.2rem">100% goes to the delivery partner</div>
          </div>
          <div class="tip-options" id="tip-options">
            <?php foreach($TIP_OPTIONS as $t): ?>
              <button type="button" class="tip-btn<?= $t===0?' active':'' ?>" data-tip="<?= $t ?>"><?= $t===0 ? 'No Tip' : '৳'.$t ?></button>
            <?php endforeach; ?>
            <button type="button" class="tip-btn" data-tip="custom" style="grid-column: span 4">Custom Amount</button>
          </div>
          <div class="form-group tip-custom" id="tip-custom-wrap">
            <label class="form-label">Enter tip amount (৳)</label>
            <input type="number" min="0" step="5" class="form-control" id="tip-custom-input" placeholder="e.g. 30">
          </div>
        </div>


        <div class="order-panel">
          <div class="order-panel-title">💳 Payment Method</div>

          <div class="payment-method active" data-method="cod">
            <input type="radio" name="pm" value="cod" checked>
            <div class="pm-icon">💵</div>
            <div class="pm-info"><div class="pm-name">Cash on Delivery</div><div class="pm-desc">Pay in cash when your order arrives</div></div>
          </div>

          <div class="payment-method" data-method="bkash">
            <input type="radio" name="pm" value="bkash">
            <div class="pm-icon">🟣</div>
            <div class="pm-info"><div class="pm-name">bKash</div><div class="pm-desc">Pay via bKash mobile banking</div></div>
          </div>
          <div class="payment-detail" id="detail-bkash">
            <label class="form-label">Your bKash Number</label>
            <input type="text" class="form-control" id="bkash-num" placeholder="01XXXXXXXXX">
          </div>

          <div class="payment-method" data-method="nagad">
            <input type="radio" name="pm" value="nagad">
            <div class="pm-icon">🟠</div>
            <div class="pm-info"><div class="pm-name">Nagad</div><div class="pm-desc">Pay via Nagad mobile banking</div></div>
          </div>
          <div class="payment-detail" id="detail-nagad">
            <label class="form-label">Your Nagad Number</label>
            <input type="text" class="form-control" id="nagad-num" placeholder="01XXXXXXXXX">
          </div>

          <div class="payment-method" data-method="card">
            <input type="radio" name="pm" value="card">
            <div class="pm-icon">💳</div>
            <div class="pm-info"><div class="pm-name">Debit / Credit Card</div><div class="pm-desc">Visa, Mastercard, Nexus</div></div>
          </div>
          <div class="payment-detail" id="detail-card">
            <div class="form-group">
              <label class="form-label">Card Number</label>
              <input type="text" class="form-control" id="card-number" placeholder="0000 0000 0000 0000" maxlength="19">
            </div>
            <div class="grid-2" style="gap:1rem">
              <div class="form-group">
                <label class="form-label">Expiry</label>
                <input type="text" class="form-control" id="card-expiry" placeholder="MM/YY" maxlength="5">
              </div>
              <div class="form-group">
                <label class="form-label">CVV</label>
                <input type="text" class="form-control" id="card-cvv" placeholder="123" maxlength="3">
              </div>
            </div>
          </div>
        </div>

        <?php if(isLoggedIn()): ?>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1.1rem;padding:1rem">
          🎉 Place Order — ৳<span id="submit-total"><?= $grand ?></span>
        </button>
        <?php else: ?>
        <a href="login.php" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1.1rem;padding:1rem;text-align:center">
          🔐 Login to Place Order
        </a>
        <?php endif; ?>
      </form>
      <?php endif; ?>
    </div>

    <div class="order-sidebar">
      <div class="order-panel">
        <div class="order-panel-title">📋 Order Summary</div>
        <?php foreach($cart as $entry): ?>
        <div class="summary-row">
          <span class="label"><?= $entry['item']['emoji'] ?> <?= htmlspecialchars($entry['item']['name']) ?> ×<?= $entry['qty'] ?></span>
          <span class="val">৳<?= $entry['item']['price'] * $entry['qty'] ?></span>
        </div>
        <?php endforeach; ?>
        <div class="summary-row" style="margin-top:.5rem">
          <span class="label">Subtotal</span>
          <span class="val">৳<?= $total ?></span>
        </div>
        <div class="summary-row">
          <span class="label">Delivery Fee</span>
          <span class="val" style="color:<?= $delivery===0?'#22c55e':'inherit' ?>">
            <?= $delivery===0 ? '🎉 FREE' : '৳'.$delivery ?>
          </span>
        </div>
        <div class="summary-row hidden-row" id="row-speed">
          <span class="label" id="row-speed-label">Express Delivery</span>
          <span class="val" id="row-speed-val">+৳0</span>
        </div>
        <div class="summary-row hidden-row" id="row-utensils">
          <span class="label">Utensils</span>
          <span class="val" id="row-utensils-val">+৳0</span>
        </div>
        <div class="summary-row hidden-row" id="row-tip">
          <span class="label">Rider Tip 💙</span>
          <span class="val" id="row-tip-val">+৳0</span>
        </div>
        <div class="summary-total">
          <span>Grand Total</span>
          <span class="val">৳<span id="grand-total-display"><?= $grand ?></span></span>
        </div>
      </div>

      <?php if($total > 0 && $total < 500): ?>
      <div class="free-delivery-banner">
        🚀 Add ৳<?= 500-$total ?> more to get <strong>FREE delivery!</strong>
      </div>
      <?php elseif($total >= 500): ?>
      <div class="free-delivery-banner">
        🎉 You've got <strong>FREE delivery!</strong>
      </div>
      <?php endif; ?>

      <div class="order-panel" style="font-size:.85rem;color:rgba(255,255,255,.5)">
        <div style="display:flex;gap:.8rem;align-items:center;margin-bottom:.6rem">
          <span>⚡</span><span>30-min average delivery in Dhaka</span>
        </div>
        <div style="display:flex;gap:.8rem;align-items:center;margin-bottom:.6rem">
          <span>🔒</span><span>Safe & secure checkout</span>
        </div>
        <div style="display:flex;gap:.8rem;align-items:center">
          <span>📞</span><span>Support: 01700-JHATPHAT</span>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="modal-overlay" id="success-modal">
  <div class="modal-box">
    <div class="success-icon">🎉</div>
    <div class="success-title">Order Placed!</div>
    <p style="color:rgba(255,255,255,.7);margin-bottom:.5rem">Your delicious food is on its way!</p>
    <div class="order-id-box" id="order-id-display">JHP------</div>
    <p style="font-size:.85rem;color:rgba(255,255,255,.5);margin-bottom:1.5rem">Show this Order ID at your door.</p>
    <a href="menu.php" class="btn btn-primary" onclick="closeModal('success-modal')">🍴 Order More Food</a>
    <button class="btn btn-outline" onclick="closeModal('success-modal')" style="margin-top:.5rem">Close</button>
  </div>
</div>

<footer>
  <div class="footer-brand">🍽️ Jhatphat</div>
  <p>© 2025 Jhatphat Food Delivery — Dhaka, Bangladesh 🇧🇩</p>
</footer>

<script src="js/main.js"></script>
<script>
const BASE_GRAND = <?= (int)$grand ?>; // subtotal + delivery fee, from server

let state = { speedFee: 0, speedLabel: '', utensilsFee: 0, tip: 0 };

function updateSummary() {
  const extra = state.speedFee + state.utensilsFee + state.tip;
  const newGrand = BASE_GRAND + extra;


  const rowSpeed = document.getElementById('row-speed');
  if (state.speedFee !== 0) {
    rowSpeed.classList.remove('hidden-row');
    document.getElementById('row-speed-label').textContent = state.speedLabel;
    const val = document.getElementById('row-speed-val');
    val.textContent = (state.speedFee > 0 ? '+৳' : '−৳') + Math.abs(state.speedFee);
    val.style.color = state.speedFee > 0 ? '' : '#22c55e';
  } else {
    rowSpeed.classList.add('hidden-row');
  }


  const rowUtensils = document.getElementById('row-utensils');
  if (state.utensilsFee > 0) {
    rowUtensils.classList.remove('hidden-row');
    document.getElementById('row-utensils-val').textContent = '+৳' + state.utensilsFee;
  } else {
    rowUtensils.classList.add('hidden-row');
  }

  
  const rowTip = document.getElementById('row-tip');
  if (state.tip > 0) {
    rowTip.classList.remove('hidden-row');
    document.getElementById('row-tip-val').textContent = '+৳' + state.tip;
  } else {
    rowTip.classList.add('hidden-row');
  }

  document.getElementById('grand-total-display').textContent = newGrand;
  const submitTotal = document.getElementById('submit-total');
  if (submitTotal) submitTotal.textContent = newGrand;

  document.getElementById('utensils-hidden').value = state.utensilsFee > 0 ? '1' : '0';
  document.getElementById('tip-hidden').value = state.tip;
}

document.querySelectorAll('.speed-method').forEach(el => {
  el.addEventListener('click', () => {
    document.querySelectorAll('.speed-method').forEach(x => x.classList.remove('active'));
    el.classList.add('active');
    el.querySelector('input[type="radio"]').checked = true;

    const speed = el.dataset.speed;
    const fee = parseInt(el.dataset.fee, 10);
    document.getElementById('speed-hidden').value = speed;

    document.getElementById('detail-schedule').style.display = speed === 'schedule' ? 'block' : 'none';

    state.speedFee = fee;
    state.speedLabel = speed === 'express' ? 'Express Delivery' : speed === 'schedule' ? 'Schedule Discount' : '';
    updateSummary();
  });
});
document.getElementById('schedule-time-input')?.addEventListener('change', function() {
  document.getElementById('schedule-hidden').value = this.value;
});

document.getElementById('utensils-toggle')?.addEventListener('change', function() {
  state.utensilsFee = this.checked ? <?= $UTENSILS_FEE ?> : 0;
  updateSummary();
});


document.querySelectorAll('.tip-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tip-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const val = btn.dataset.tip;
    const customWrap = document.getElementById('tip-custom-wrap');
    if (val === 'custom') {
      customWrap.style.display = 'block';
      const customVal = parseInt(document.getElementById('tip-custom-input').value, 10) || 0;
      state.tip = customVal;
    } else {
      customWrap.style.display = 'none';
      state.tip = parseInt(val, 10);
    }
    updateSummary();
  });
});
document.getElementById('tip-custom-input')?.addEventListener('input', function() {
  state.tip = parseInt(this.value, 10) || 0;
  updateSummary();
});

document.querySelectorAll('.payment-method').forEach(el => {
  el.addEventListener('click', () => {
    document.querySelectorAll('.payment-method').forEach(x => x.classList.remove('active'));
    el.classList.add('active');
    el.querySelector('input[type="radio"]').checked = true;
    document.querySelectorAll('.payment-detail').forEach(d => d.style.display = 'none');
    const detail = document.getElementById('detail-' + el.dataset.method);
    if (detail) detail.style.display = 'block';
  });
});

document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
  const active = document.querySelector('.payment-method.active');
  if(active) {
    const method = active.dataset.method;
    document.getElementById('payment-hidden').value = method;
    let detail = '';
    if(method === 'bkash') detail = document.getElementById('bkash-num')?.value || '';
    if(method === 'nagad') detail = document.getElementById('nagad-num')?.value || '';
    if(method === 'card') {
      const num = document.getElementById('card-number')?.value || '';
      const exp = document.getElementById('card-expiry')?.value || '';
      detail = num + ' Exp:' + exp;
    }
    document.getElementById('payment-detail-hidden').value = detail;
  }
});
</script>
</body>
</html>
