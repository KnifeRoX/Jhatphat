<?php
require_once 'data.php';

// Dynamically determine the base URL of the project (works in any subfolder)
function baseUrl($page = '') {
    $scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME'])); // go up from /php/
    // Normalize: remove trailing slash, ensure leading slash
    $base = rtrim($scriptDir, '/');
    return $base . '/' . ltrim($page, '/');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $id = intval($_POST['id'] ?? 0);
    addToCart($id, $menu_items);
    $redirect = $_POST['redirect'] ?? '';
    if ($redirect) {
        header('Location: ' . baseUrl($redirect));
    } else {
        header('Location: ' . baseUrl('menu.php'));
    }
    exit;
}

if ($action === 'update') {
    $id = intval($_POST['id'] ?? 0);
    $qty = intval($_POST['qty'] ?? 1);
    if (isset($_SESSION['cart'][$id])) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['qty'] = $qty;
        }
    }
    header('Location: ' . baseUrl('cart.php'));
    exit;
}

if ($action === 'remove') {
    $id = intval($_POST['id'] ?? 0);
    unset($_SESSION['cart'][$id]);
    header('Location: ' . baseUrl('cart.php'));
    exit;
}

if ($action === 'place_order') {
    if (!isLoggedIn()) {
        header('Location: ' . baseUrl('login.php'));
        exit;
    }
    $cart = getCart();
    if (empty($cart)) {
        header('Location: ' . baseUrl('cart.php'));
        exit;
    }
    $order_id = 'JHP' . strtoupper(substr(md5(uniqid()), 0, 6));
    $order = [
        'id' => $order_id,
        'user_id' => getCurrentUser()['id'],
        'user_name' => $_POST['name'] ?? getCurrentUser()['name'],
        'phone' => $_POST['phone'] ?? '',
        'address' => $_POST['address'] ?? '',
        'payment' => $_POST['payment'] ?? 'cod',
        'payment_detail' => $_POST['payment_detail'] ?? '',
        'items' => $cart,
        'total' => getCartTotal(),
        'delivery_fee' => getCartTotal() >= 500 ? 0 : 50,
        'status' => 'Confirmed',
        'time' => date('Y-m-d H:i:s'),
    ];
    $_SESSION['orders'][] = $order;
    $_SESSION['cart'] = [];
    $_SESSION['last_order'] = $order;
    header('Location: ' . baseUrl('cart.php') . '?success=1&order=' . $order_id);
    exit;
}

if ($action === 'ajax_cart_count') {
    header('Content-Type: application/json');
    echo json_encode(['count' => getCartCount(), 'total' => getCartTotal()]);
    exit;
}

header('Location: ' . baseUrl('index.php'));
exit;
?>
