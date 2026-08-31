<?php
session_start();

// Initialize users storage
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        ['id' => 1, 'name' => 'Admin', 'email' => 'admin@jhatphat.com', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'is_admin' => true],
        ['id' => 2, 'name' => 'Rahim Uddin', 'email' => 'rahim@example.com', 'password' => password_hash('pass123', PASSWORD_DEFAULT), 'is_admin' => false, 'phone' => '01712345678', 'address' => 'Gulshan-1, Dhaka']
    ];
}

// Initialize orders storage
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

// Menu items
$menu_items = [
    ['id' => 1, 'name' => 'Kacchi Biryani', 'category' => 'Biryani', 'price' => 380, 'veg' => false, 'emoji' => '🍚', 'desc' => 'Slow-cooked mutton layered with fragrant basmati rice, saffron & fried onions', 'rating' => 4.9, 'orders' => 1240],
    ['id' => 2, 'name' => 'Shorshe Ilish', 'category' => 'Fish', 'price' => 450, 'veg' => false, 'emoji' => '🐟', 'desc' => 'Hilsa fish in a pungent mustard paste — the pride of Bengali cuisine', 'rating' => 4.8, 'orders' => 980],
    ['id' => 3, 'name' => 'Chicken Rezala', 'category' => 'Curry', 'price' => 280, 'veg' => false, 'emoji' => '🍗', 'desc' => 'Mughal-style white chicken curry with yogurt, cream & aromatic spices', 'rating' => 4.7, 'orders' => 1560],
    ['id' => 4, 'name' => 'Mishti Doi', 'category' => 'Dessert', 'price' => 80, 'veg' => true, 'emoji' => '🍮', 'desc' => 'Creamy sweet yogurt set in earthen pots — a classic Bengali indulgence', 'rating' => 4.9, 'orders' => 2100],
    ['id' => 5, 'name' => 'Fuchka', 'category' => 'Street Food', 'price' => 60, 'veg' => true, 'emoji' => '🫙', 'desc' => 'Crispy hollow puri filled with spiced mashed potato and tangy tamarind water', 'rating' => 4.8, 'orders' => 3400],
    ['id' => 6, 'name' => 'Haleem', 'category' => 'Curry', 'price' => 220, 'veg' => false, 'emoji' => '🥘', 'desc' => 'Slow-cooked lentils & meat stew, garnished with ginger, lime & crispy onions', 'rating' => 4.7, 'orders' => 870],
    ['id' => 7, 'name' => 'Hilsa Curry', 'category' => 'Fish', 'price' => 420, 'veg' => false, 'emoji' => '🍲', 'desc' => 'Tender hilsa pieces in a bold, spiced curry with turmeric and green chilli', 'rating' => 4.6, 'orders' => 760],
    ['id' => 8, 'name' => 'Vegetable Khichuri', 'category' => 'Rice', 'price' => 150, 'veg' => true, 'emoji' => '🫕', 'desc' => 'Comforting rice-lentil porridge with seasonal vegetables and ghee tempering', 'rating' => 4.5, 'orders' => 640],
    ['id' => 9, 'name' => 'Beef Bhuna', 'category' => 'Curry', 'price' => 320, 'veg' => false, 'emoji' => '🥩', 'desc' => 'Dry-roasted beef in deeply caramelised spices — intensely rich and flavourful', 'rating' => 4.8, 'orders' => 1090],
    ['id' => 10, 'name' => 'Rasgolla', 'category' => 'Dessert', 'price' => 50, 'veg' => true, 'emoji' => '🟤', 'desc' => 'Soft spongy cheese balls soaked in light rose-flavoured sugar syrup', 'rating' => 4.7, 'orders' => 1800],
    ['id' => 11, 'name' => 'Prawn Malai Curry', 'category' => 'Fish', 'price' => 380, 'veg' => false, 'emoji' => '🦐', 'desc' => 'King prawns simmered in a luscious coconut milk gravy with mustard seeds', 'rating' => 4.6, 'orders' => 590],
    ['id' => 12, 'name' => 'Paratha', 'category' => 'Street Food', 'price' => 40, 'veg' => true, 'emoji' => '🫓', 'desc' => 'Flaky layered whole-wheat flatbread cooked on a tawa with a touch of butter', 'rating' => 4.5, 'orders' => 2800],
];

// Cart helpers
function getCart() {
    return $_SESSION['cart'] ?? [];
}

function addToCart($item_id, $menu_items) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    foreach ($menu_items as $item) {
        if ($item['id'] == $item_id) {
            if (isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id]['qty']++;
            } else {
                $_SESSION['cart'][$item_id] = ['item' => $item, 'qty' => 1];
            }
            return true;
        }
    }
    return false;
}

function getCartTotal() {
    $total = 0;
    foreach (getCart() as $entry) {
        $total += $entry['item']['price'] * $entry['qty'];
    }
    return $total;
}

function getCartCount() {
    $count = 0;
    foreach (getCart() as $entry) {
        $count += $entry['qty'];
    }
    return $count;
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}
?>
