<?php
require_once 'php/data.php';
if (!isLoggedIn() || !getCurrentUser()['is_admin']) {
    header('Location: login.php');
    exit;
}
$u = getCurrentUser();

// Add some demo orders if none exist
if (empty($_SESSION['orders'])) {
    $_SESSION['orders'] = [
        [
            'id' => 'JHPA1B2C3',
            'user_id' => 2,
            'user_name' => 'Rahim Uddin',
            'phone' => '01712345678',
            'address' => 'House 12, Road 5, Gulshan-1, Dhaka',
            'payment' => 'bkash',
            'payment_detail' => '01712345678',
            'items' => [
                1 => ['item' => $menu_items[0], 'qty' => 2],
                3 => ['item' => $menu_items[2], 'qty' => 1],
            ],
            'total' => 1040,
            'delivery_fee' => 0,
            'status' => 'Confirmed',
            'time' => date('Y-m-d H:i:s', strtotime('-2 hours')),
        ],
        [
            'id' => 'JHPD4E5F6',
            'user_id' => 2,
            'user_name' => 'Fatema Begum',
            'phone' => '01987654321',
            'address' => 'Apt 4B, Building 7, Dhanmondi 27, Dhaka',
            'payment' => 'cod',
            'payment_detail' => '',
            'items' => [
                5 => ['item' => $menu_items[4], 'qty' => 3],
                4 => ['item' => $menu_items[3], 'qty' => 2],
            ],
            'total' => 340,
            'delivery_fee' => 50,
            'status' => 'Preparing',
            'time' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
        ],
        [
            'id' => 'JHPG7H8I9',
            'user_id' => 3,
            'user_name' => 'Karim Mia',
            'phone' => '01611223344',
            'address' => 'Sector 6, Uttara, Dhaka',
            'payment' => 'nagad',
            'payment_detail' => '01611223344',
            'items' => [
                2 => ['item' => $menu_items[1], 'qty' => 1],
                9 => ['item' => $menu_items[8], 'qty' => 2],
            ],
            'total' => 1090,
            'delivery_fee' => 0,
            'status' => 'On the Way',
            'time' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
        ],
    ];
}

$orders = array_reverse($_SESSION['orders']);
$total_revenue = array_sum(array_map(fn($o) => $o['total'] + $o['delivery_fee'], $orders));
$total_orders = count($orders);
$statuses = ['Confirmed' => 0, 'Preparing' => 0, 'On the Way' => 0, 'Delivered' => 0];
foreach($orders as $o) { if(isset($statuses[$o['status']])) $statuses[$o['status']]++; }

// Status update
if ($_POST['action'] ?? '' === 'update_status') {
    $oid = $_POST['order_id'] ?? '';
    $ns  = $_POST['status'] ?? '';
    foreach ($_SESSION['orders'] as &$o) {
        if ($o['id'] === $oid) { $o['status'] = $ns; break; }
    }
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Admin Dashboard — Jhatphat</title>
<link rel="stylesheet" href="css/style.css">
<style>
body { background: #0D0600; }
.admin-layout { display: flex; min-height: 100vh; }

/* ─── SIDEBAR ─── */
.admin-sidebar {
  width: 240px; background: var(--dark2);
  border-right: 1px solid rgba(232,67,26,.15);
  padding: 1.5rem 0; position: fixed; top: 0; left: 0; height: 100vh;
  display: flex; flex-direction: column;
}
.admin-logo {
  padding: 1rem 1.5rem 2rem;
  font-family: 'Baloo Da 2', cursive; font-size: 1.5rem; font-weight: 800;
  background: linear-gradient(135deg, var(--brand2), var(--accent));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  border-bottom: 1px solid rgba(232,67,26,.1); margin-bottom: 1rem;
}
.admin-logo span { font-size: 1rem; display: block; color: rgba(255,255,255,.4); -webkit-text-fill-color: rgba(255,255,255,.4); font-family: 'Hind Siliguri', sans-serif; font-weight: 400; }
.admin-nav a {
  display: flex; align-items: center; gap: .8rem;
  padding: .8rem 1.5rem; color: rgba(255,255,255,.6);
  text-decoration: none; font-weight: 600; font-size: .9rem;
  transition: var(--transition); border-left: 3px solid transparent;
}
.admin-nav a:hover, .admin-nav a.active {
  color: #fff; background: rgba(232,67,26,.1); border-left-color: var(--brand);
}
.admin-sidebar-footer {
  margin-top: auto; padding: 1.5rem;
  border-top: 1px solid rgba(255,255,255,.05);
}
.admin-user { display: flex; align-items: center; gap: .7rem; margin-bottom: 1rem; }
.admin-user .avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--brand), var(--accent)); display: flex; align-items: center; justify-content: center; font-weight: 700; }
.admin-user .info .name { font-weight: 700; font-size: .9rem; }
.admin-user .info .role { font-size: .75rem; color: var(--brand2); }

/* ─── MAIN CONTENT ─── */
.admin-main { margin-left: 240px; flex: 1; padding: 2rem; }
.admin-header {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 2rem; padding-bottom: 1.5rem;
  border-bottom: 1px solid rgba(255,255,255,.06);
}
.admin-header h1 { font-family: 'Baloo Da 2', cursive; font-size: 1.8rem; font-weight: 800; }
.admin-header p { color: rgba(255,255,255,.5); font-size: .9rem; margin-top: .2rem; }

/* ─── STAT CARDS ─── */
.stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 2.5rem; }
.stat-card {
  background: var(--dark2); border: 1px solid rgba(255,255,255,.05);
  border-radius: var(--radius2); padding: 1.5rem; transition: var(--transition);
}
.stat-card:hover { transform: translateY(-4px); border-color: rgba(232,67,26,.2); }
.stat-card-icon { font-size: 1.8rem; margin-bottom: .8rem; }
.stat-card-val { font-family: 'Baloo Da 2', cursive; font-size: 2rem; font-weight: 800; color: var(--accent2); line-height: 1; }
.stat-card-label { font-size: .8rem; color: rgba(255,255,255,.5); margin-top: .3rem; }

/* ─── ORDERS TABLE ─── */
.orders-panel {
  background: var(--dark2); border: 1px solid rgba(255,255,255,.05);
  border-radius: var(--radius2); overflow: hidden;
}
.orders-panel-header {
  padding: 1.2rem 1.5rem; background: rgba(232,67,26,.08);
  border-bottom: 1px solid rgba(232,67,26,.15);
  display: flex; justify-content: space-between; align-items: center;
}
.orders-panel-header h2 { font-family: 'Baloo Da 2', cursive; font-size: 1.2rem; }
.order-table { width: 100%; border-collapse: collapse; }
.order-table th {
  padding: .8rem 1rem; text-align: left; font-size: .75rem;
  text-transform: uppercase; letter-spacing: 1px;
  color: rgba(255,255,255,.4); font-weight: 600;
  background: rgba(0,0,0,.2); border-bottom: 1px solid rgba(255,255,255,.05);
}
.order-table td {
  padding: 1rem; border-bottom: 1px solid rgba(255,255,255,.04);
  font-size: .88rem; vertical-align: top;
}
.order-table tr:last-child td { border-bottom: none; }
.order-table tr:hover td { background: rgba(232,67,26,.04); }

.status-badge {
  display: inline-block; padding: .25rem .7rem; border-radius: 50px;
  font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
}
.status-Confirmed { background: rgba(96,165,250,.15); color: #93c5fd; border: 1px solid rgba(96,165,250,.3); }
.status-Preparing { background: rgba(251,191,36,.15); color: #fde68a; border: 1px solid rgba(251,191,36,.3); }
.status-On-the-Way { background: rgba(255,107,53,.15); color: #fdba74; border: 1px solid rgba(255,107,53,.3); }
.status-Delivered { background: rgba(34,197,94,.15); color: #86efac; border: 1px solid rgba(34,197,94,.3); }

.pay-badge {
  font-size: .75rem; padding: .2rem .5rem; border-radius: 6px;
  font-weight: 600;
}
.pay-cod { background: rgba(255,255,255,.08); color: rgba(255,255,255,.6); }
.pay-bkash { background: rgba(168,85,247,.15); color: #d8b4fe; }
.pay-nagad { background: rgba(255,107,53,.15); color: #fdba74; }
.pay-card { background: rgba(59,130,246,.15); color: #93c5fd; }

.items-list { font-size: .8rem; color: rgba(255,255,255,.6); line-height: 1.7; }

.status-form select {
  background: var(--dark3); border: 1px solid rgba(255,255,255,.1);
  color: #fff; padding: .3rem .5rem; border-radius: 8px; font-family: inherit;
  font-size: .8rem; cursor: pointer;
}
.status-form button {
  background: var(--brand); border: none; color: #fff;
  padding: .3rem .6rem; border-radius: 8px; cursor: pointer;
  font-size: .75rem; font-weight: 700; transition: var(--transition);
}
.status-form button:hover { background: var(--brand2); }

.no-orders { text-align: center; padding: 3rem; color: rgba(255,255,255,.4); }

@media(max-width:1000px){ .stats-grid{grid-template-columns:repeat(2,1fr);} }
@media(max-width:768px){
  .admin-sidebar{display:none;}
  .admin-main{margin-left:0;}
}
</style>
</head>
<body>

<div class="admin-layout">
  <!-- SIDEBAR -->
  <aside class="admin-sidebar">
    <div class="admin-logo">🍽️ Jhatphat<span>Admin Dashboard</span></div>
    <nav class="admin-nav">
      <a href="admin.php" class="active">📊 Overview</a>
      <a href="admin.php">🧾 Orders</a>
      <a href="menu.php">🍴 Menu</a>
      <a href="index.php">🏠 View Site</a>
    </nav>
    <div class="admin-sidebar-footer">
      <div class="admin-user">
        <div class="avatar"><?= strtoupper(substr($u['name'],0,1)) ?></div>
        <div class="info">
          <div class="name"><?= htmlspecialchars($u['name']) ?></div>
          <div class="role">👑 Admin</div>
        </div>
      </div>
      <form method="POST" action="php/auth.php">
        <input type="hidden" name="action" value="logout">
        <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center;padding:.5rem;font-size:.85rem">Logout</button>
      </form>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="admin-main">
    <div class="admin-header">
      <div>
        <h1>Good <?= date('G')<12?'Morning':( date('G')<17?'Afternoon':'Evening') ?>, <?= htmlspecialchars(explode(' ',$u['name'])[0]) ?>! 👋</h1>
        <p><?= date('l, d F Y') ?> — Here's what's happening today.</p>
      </div>
      <a href="index.php" class="btn btn-outline" style="padding:.5rem 1.2rem;font-size:.85rem">← View Site</a>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
      <div class="stat-card fade-up">
        <div class="stat-card-icon">🧾</div>
        <div class="stat-card-val"><?= $total_orders ?></div>
        <div class="stat-card-label">Total Orders</div>
      </div>
      <div class="stat-card fade-up">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-val">৳<?= number_format($total_revenue) ?></div>
        <div class="stat-card-label">Total Revenue</div>
      </div>
      <div class="stat-card fade-up">
        <div class="stat-card-icon">👥</div>
        <div class="stat-card-val"><?= count($_SESSION['users']) - 1 ?></div>
        <div class="stat-card-label">Registered Users</div>
      </div>
      <div class="stat-card fade-up">
        <div class="stat-card-icon">🚀</div>
        <div class="stat-card-val"><?= $statuses['On the Way'] + $statuses['Preparing'] ?></div>
        <div class="stat-card-label">Active Orders</div>
      </div>
    </div>

    <!-- STATUS BREAKDOWN -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
      <?php foreach($statuses as $s => $c): ?>
      <div style="background:var(--dark2);border-radius:var(--radius);padding:1rem;text-align:center;border:1px solid rgba(255,255,255,.05)">
        <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.4);margin-bottom:.3rem"><?= $s ?></div>
        <div style="font-family:'Baloo Da 2',cursive;font-size:1.6rem;font-weight:800;color:var(--accent2)"><?= $c ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- ORDERS TABLE -->
    <div class="orders-panel">
      <div class="orders-panel-header">
        <h2>📋 All Orders (<?= $total_orders ?>)</h2>
        <span style="font-size:.8rem;color:rgba(255,255,255,.4)">Latest first</span>
      </div>
      <?php if(empty($orders)): ?>
        <div class="no-orders">
          <div style="font-size:3rem;margin-bottom:.5rem">📭</div>
          <p>No orders yet. Share the site to get orders!</p>
        </div>
      <?php else: ?>
      <div style="overflow-x:auto">
        <table class="order-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Delivery Address</th>
              <th>Items Ordered</th>
              <th>Payment</th>
              <th>Total</th>
              <th>Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($orders as $o): 
              $statusClass = 'status-' . str_replace(' ','-',$o['status']);
              $payClass = 'pay-' . $o['payment'];
            ?>
            <tr>
              <td>
                <span style="font-family:'Baloo Da 2',cursive;font-weight:700;color:var(--brand2)"><?= htmlspecialchars($o['id']) ?></span>
              </td>
              <td>
                <div style="font-weight:700"><?= htmlspecialchars($o['user_name']) ?></div>
                <div style="font-size:.78rem;color:rgba(255,255,255,.45);margin-top:.2rem">📞 <?= htmlspecialchars($o['phone']) ?></div>
              </td>
              <td>
                <div style="max-width:180px;font-size:.82rem;color:rgba(255,255,255,.75);line-height:1.5">
                  📍 <?= htmlspecialchars($o['address']) ?>
                </div>
              </td>
              <td>
                <div class="items-list">
                  <?php foreach($o['items'] as $entry): ?>
                  <div><?= $entry['item']['emoji'] ?> <?= htmlspecialchars($entry['item']['name']) ?> ×<?= $entry['qty'] ?></div>
                  <?php endforeach; ?>
                </div>
              </td>
              <td>
                <span class="pay-badge <?= $payClass ?>">
                  <?php
                  $pm_icons = ['cod'=>'💵','bkash'=>'🟣','nagad'=>'🟠','card'=>'💳'];
                  $pm_names = ['cod'=>'Cash','bkash'=>'bKash','nagad'=>'Nagad','card'=>'Card'];
                  echo ($pm_icons[$o['payment']] ?? '') . ' ' . ($pm_names[$o['payment']] ?? $o['payment']);
                  ?>
                </span>
                <?php if(!empty($o['payment_detail'])): ?>
                <div style="font-size:.72rem;color:rgba(255,255,255,.35);margin-top:.2rem"><?= htmlspecialchars(substr($o['payment_detail'],0,20)) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-family:'Baloo Da 2',cursive;font-weight:800;color:var(--accent2)">৳<?= number_format($o['total'] + $o['delivery_fee']) ?></div>
                <?php if($o['delivery_fee'] === 0): ?>
                <div style="font-size:.72rem;color:#22c55e">+Free delivery</div>
                <?php else: ?>
                <div style="font-size:.72rem;color:rgba(255,255,255,.4)">+৳<?= $o['delivery_fee'] ?> delivery</div>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-size:.78rem;color:rgba(255,255,255,.45)"><?= date('d M, h:i A', strtotime($o['time'])) ?></div>
              </td>
              <td>
                <div style="margin-bottom:.5rem">
                  <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($o['status']) ?></span>
                </div>
                <form method="POST" action="admin.php" class="status-form" style="display:flex;gap:.3rem;align-items:center">
                  <input type="hidden" name="action" value="update_status">
                  <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                  <select name="status">
                    <?php foreach(['Confirmed','Preparing','On the Way','Delivered'] as $s): ?>
                    <option value="<?= $s ?>" <?= $o['status']===$s?'selected':'' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit">✓</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>

  </main>
</div>

<script src="js/main.js"></script>
</body>
</html>
