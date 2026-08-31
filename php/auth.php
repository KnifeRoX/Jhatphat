<?php
require_once 'data.php';

// Dynamically determine the base URL of the project
function baseUrl($page = '') {
    $scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME'])); // go up from /php/
    $base = rtrim($scriptDir, '/');
    return $base . '/' . ltrim($page, '/');
}

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    foreach ($_SESSION['users'] as $u) {
        if ($u['email'] === $email && password_verify($pass, $u['password'])) {
            $_SESSION['user'] = $u;
            if ($u['is_admin']) {
                header('Location: ' . baseUrl('admin.php'));
            } else {
                header('Location: ' . baseUrl('index.php'));
            }
            exit;
        }
    }
    $_SESSION['auth_error'] = 'Invalid email or password.';
    header('Location: ' . baseUrl('login.php') . '?tab=login');
    exit;
}

if ($action === 'signup') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $addr  = trim($_POST['address'] ?? '');

    foreach ($_SESSION['users'] as $u) {
        if ($u['email'] === $email) {
            $_SESSION['auth_error'] = 'Email already registered.';
            header('Location: ' . baseUrl('login.php') . '?tab=signup');
            exit;
        }
    }
    $new_user = [
        'id'       => count($_SESSION['users']) + 1,
        'name'     => $name,
        'email'    => $email,
        'phone'    => $phone,
        'address'  => $addr,
        'password' => password_hash($pass, PASSWORD_DEFAULT),
        'is_admin' => false,
    ];
    $_SESSION['users'][] = $new_user;
    $_SESSION['user'] = $new_user;
    header('Location: ' . baseUrl('index.php'));
    exit;
}

if ($action === 'logout') {
    unset($_SESSION['user']);
    header('Location: ' . baseUrl('index.php'));
    exit;
}

header('Location: ' . baseUrl('login.php'));
exit;
?>
