<?php
session_start();

// تنظیمات دیتابیس - اینجا نیاز داری هاست جداگانه برای دیتابیس
define('DB_HOST', 'localhost'); // بعداً تغییر میدی
define('DB_NAME', 'tiny_app_fa');
define('DB_USER', 'username');
define('DB_PASS', 'password');

// برای کلودفلر Pages
define('BASE_URL', 'https://your-app.pages.dev'); // بعداً تغییر میدی
define('SITE_NAME', 'Tiny App FA');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

require_once 'functions.php';
?>