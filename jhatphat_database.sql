-- ============================================================
--  Jhatphat Food Ordering App — MySQL Database Schema
--  Generated for: jhatphat (Bengali food delivery platform)
-- ============================================================

CREATE DATABASE IF NOT EXISTS jhatphat
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE jhatphat;

-- ============================================================
-- 1. USERS
-- ============================================================
CREATE TABLE users (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120)    NOT NULL,
    email       VARCHAR(180)    NOT NULL UNIQUE,
    phone       VARCHAR(20)     DEFAULT NULL,
    address     VARCHAR(300)    DEFAULT NULL,
    password    VARCHAR(255)    NOT NULL,           -- bcrypt hash
    is_admin    TINYINT(1)      NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed: default admin + sample user
INSERT INTO users (name, email, phone, address, password, is_admin) VALUES
(
    'Admin',
    'admin@jhatphat.com',
    NULL,
    NULL,
    '$2y$10$exampleHashReplaceMeWithRealHash1111111111111111111111u', -- replace with real hash
    1
),
(
    'Rahim Uddin',
    'rahim@example.com',
    '01712345678',
    'Gulshan-1, Dhaka',
    '$2y$10$exampleHashReplaceMeWithRealHash2222222222222222222222u', -- replace with real hash
    0
);


-- ============================================================
-- 2. CATEGORIES
-- ============================================================
CREATE TABLE categories (
    id    TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(60) NOT NULL UNIQUE
);

INSERT INTO categories (name) VALUES
    ('Biryani'),
    ('Fish'),
    ('Curry'),
    ('Dessert'),
    ('Street Food'),
    ('Rice');


-- ============================================================
-- 3. MENU ITEMS
-- ============================================================
CREATE TABLE menu_items (
    id           SMALLINT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(120)       NOT NULL,
    category_id  TINYINT UNSIGNED   NOT NULL,
    price        DECIMAL(10,2)      NOT NULL,
    is_veg       TINYINT(1)         NOT NULL DEFAULT 0,
    emoji        VARCHAR(10)        DEFAULT NULL,
    description  TEXT               DEFAULT NULL,
    rating       DECIMAL(3,1)       NOT NULL DEFAULT 0.0,
    total_orders INT UNSIGNED       NOT NULL DEFAULT 0,
    is_active    TINYINT(1)         NOT NULL DEFAULT 1,
    created_at   DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_menu_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

INSERT INTO menu_items (name, category_id, price, is_veg, emoji, description, rating, total_orders) VALUES
('Kacchi Biryani',   1, 380.00, 0, '🍚', 'Slow-cooked mutton layered with fragrant basmati rice, saffron & fried onions',     4.9, 1240),
('Shorshe Ilish',    2, 450.00, 0, '🐟', 'Hilsa fish in a pungent mustard paste — the pride of Bengali cuisine',              4.8,  980),
('Chicken Rezala',   3, 280.00, 0, '🍗', 'Mughal-style white chicken curry with yogurt, cream & aromatic spices',             4.7, 1560),
('Mishti Doi',       4,  80.00, 1, '🍮', 'Creamy sweet yogurt set in earthen pots — a classic Bengali indulgence',            4.9, 2100),
('Fuchka',           5,  60.00, 1, '🫙', 'Crispy hollow puri filled with spiced mashed potato and tangy tamarind water',      4.8, 3400),
('Haleem',           3, 220.00, 0, '🥘', 'Slow-cooked lentils & meat stew, garnished with ginger, lime & crispy onions',      4.7,  870),
('Hilsa Curry',      2, 420.00, 0, '🍲', 'Tender hilsa pieces in a bold, spiced curry with turmeric and green chilli',        4.6,  760),
('Vegetable Khichuri',6,150.00, 1, '🫕', 'Comforting rice-lentil porridge with seasonal vegetables and ghee tempering',       4.5,  640),
('Beef Bhuna',       3, 320.00, 0, '🥩', 'Dry-roasted beef in deeply caramelised spices — intensely rich and flavourful',     4.8, 1090),
('Rasgolla',         4,  50.00, 1, '🟤', 'Soft spongy cheese balls soaked in light rose-flavoured sugar syrup',               4.7, 1800),
('Prawn Malai Curry',2, 380.00, 0, '🦐', 'King prawns simmered in a luscious coconut milk gravy with mustard seeds',         4.6,  590),
('Paratha',          5,  40.00, 1, '🫓', 'Flaky layered whole-wheat flatbread cooked on a tawa with a touch of butter',       4.5, 2800);


-- ============================================================
-- 4. ORDERS
-- ============================================================
CREATE TABLE orders (
    id              VARCHAR(20)     PRIMARY KEY,          -- e.g. JHP3A9F2C
    user_id         INT UNSIGNED    NOT NULL,
    user_name       VARCHAR(120)    NOT NULL,
    phone           VARCHAR(20)     NOT NULL,
    address         VARCHAR(300)    NOT NULL,
    payment_method  ENUM('cod','bkash','nagad','card') NOT NULL DEFAULT 'cod',
    payment_detail  VARCHAR(50)     DEFAULT NULL,         -- mobile number / last-4 digits
    subtotal        DECIMAL(10,2)   NOT NULL,
    delivery_fee    DECIMAL(10,2)   NOT NULL DEFAULT 50.00,
    total           DECIMAL(10,2)   NOT NULL,
    status          ENUM('Confirmed','Preparing','On the Way','Delivered','Cancelled')
                                    NOT NULL DEFAULT 'Confirmed',
    placed_at       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);


-- ============================================================
-- 5. ORDER ITEMS  (line-items within an order)
-- ============================================================
CREATE TABLE order_items (
    id           INT UNSIGNED        AUTO_INCREMENT PRIMARY KEY,
    order_id     VARCHAR(20)         NOT NULL,
    menu_item_id SMALLINT UNSIGNED   NOT NULL,
    item_name    VARCHAR(120)        NOT NULL,   -- snapshot at time of order
    unit_price   DECIMAL(10,2)       NOT NULL,   -- snapshot at time of order
    quantity     SMALLINT UNSIGNED   NOT NULL DEFAULT 1,
    line_total   DECIMAL(10,2)       NOT NULL,   -- unit_price * quantity

    CONSTRAINT fk_oi_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_oi_menu_item
        FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);


-- ============================================================
-- 6. CART  (persisted cart — optional if you move away from sessions)
-- ============================================================
CREATE TABLE cart (
    id           INT UNSIGNED        AUTO_INCREMENT PRIMARY KEY,
    user_id      INT UNSIGNED        NOT NULL,
    menu_item_id SMALLINT UNSIGNED   NOT NULL,
    quantity     SMALLINT UNSIGNED   NOT NULL DEFAULT 1,
    added_at     DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_cart_user_item (user_id, menu_item_id),

    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_cart_menu_item
        FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);


-- ============================================================
-- INDEXES  (extra read-performance helpers)
-- ============================================================
CREATE INDEX idx_orders_user_id  ON orders(user_id);
CREATE INDEX idx_orders_status   ON orders(status);
CREATE INDEX idx_orders_placed   ON orders(placed_at);
CREATE INDEX idx_oi_order_id     ON order_items(order_id);
CREATE INDEX idx_menu_category   ON menu_items(category_id);
CREATE INDEX idx_menu_active     ON menu_items(is_active);


-- ============================================================
-- USEFUL VIEWS
-- ============================================================

-- Full order summary
CREATE OR REPLACE VIEW v_order_summary AS
SELECT
    o.id            AS order_id,
    o.placed_at,
    o.status,
    u.name          AS customer_name,
    u.email,
    o.phone,
    o.address,
    o.payment_method,
    o.subtotal,
    o.delivery_fee,
    o.total
FROM orders o
JOIN users u ON u.id = o.user_id;


-- Popular items (by total orders)
CREATE OR REPLACE VIEW v_popular_items AS
SELECT
    m.id,
    m.name,
    c.name  AS category,
    m.price,
    m.rating,
    m.total_orders
FROM menu_items m
JOIN categories c ON c.id = m.category_id
WHERE m.is_active = 1
ORDER BY m.total_orders DESC;


-- Revenue per menu item (from placed orders)
CREATE OR REPLACE VIEW v_item_revenue AS
SELECT
    oi.menu_item_id,
    oi.item_name,
    SUM(oi.quantity)   AS units_sold,
    SUM(oi.line_total) AS revenue
FROM order_items oi
JOIN orders o ON o.id = oi.order_id
WHERE o.status != 'Cancelled'
GROUP BY oi.menu_item_id, oi.item_name
ORDER BY revenue DESC;
