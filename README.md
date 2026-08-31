# Jhatphat 🍽️

Jhatphat is a PHP-based food ordering web application for authentic Bangladeshi food delivery. It includes a customer-facing menu and cart system, user authentication, and an admin panel for managing the platform.

## Features

- **Browse Menu** — View available food items on the home page and menu page
- **User Authentication** — Sign up, log in, and log out (`php/auth.php`)
- **Shopping Cart** — Add, update, and remove items from the cart, and place orders (`php/cart_action.php`)
- **Admin Panel** — Manage the platform via `admin.php`
- **Database** — MySQL schema provided in `jhatphat_database.sql`

## Project Structure

```
Aliza Project PHP/
├── admin.php              # Admin panel
├── cart.php                # Shopping cart page
├── index.php                # Home page
├── login.php               # Login / signup page
├── menu.php                 # Food menu page
├── jhatphat_database.sql    # Database schema
├── css/
│   └── style.css            # Site styling
├── js/
│   └── main.js               # Client-side scripts
└── php/
    ├── auth.php               # Login, signup, logout logic
    ├── cart_action.php         # Cart operations (add/update/remove/order)
    └── data.php                 # Application data
```

## Getting Started

### Prerequisites
- PHP 7.4 or higher
- MySQL / MariaDB
- A local server environment (XAMPP, WAMP, MAMP, or PHP's built-in server)

### Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/YOUR_USERNAME/jhatphat.git
   cd jhatphat
   ```

2. Import the database:
   - Create a database (e.g. `jhatphat`) in phpMyAdmin or via MySQL CLI
   - Import `jhatphat_database.sql` into it

3. Configure the database connection in `php/data.php` if needed.

4. Run the project:
   - **XAMPP/WAMP**: Place the folder in `htdocs` (or `www`) and visit `http://localhost/jhatphat`
   - **PHP built-in server**: 
     ```bash
     php -S localhost:8000
     ```
     then visit `http://localhost:8000`

## Demo Accounts

| Role  | Email                  | Password  |
|-------|------------------------|-----------|
| Admin | admin@jhatphat.com     | admin123  |
| User  | rahim@example.com      | pass123   |

> Note: These are demo credentials for local testing only. Do not use them, or this exact setup, in a public production deployment.

## Contributors

- Samin Yeasar
- Miftahul Zinan Aliza
- Mirza Samia Yesmin

## License

This project was created for academic purposes.
