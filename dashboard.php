<?php
include 'includes/config.php';
include 'includes/auth.php';

// اگر کاربر لاگین نکرده، برگرد به صفحه لاگین
if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$message = '';
$shortUrl = '';

// پردازش فرم کوتاه کردن لینک
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['url'])) {
    $originalUrl = $_POST['url'];
    
    // تولید کد کوتاه منحصر به فرد
    do {
        $shortCode = generateShortCode();
    } while (!isShortCodeUnique($shortCode));
    
    try {
        $stmt = $pdo->prepare("INSERT INTO links (original_url, short_code) VALUES (?, ?)");
        $stmt->execute([$originalUrl, $shortCode]);
        
        $shortUrl = BASE_URL . '/' . $shortCode;
        $message = 'لینک با موفقیت کوتاه شد!';
    } catch(PDOException $e) {
        $message = 'خطا در ایجاد لینک کوتاه: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>داشبورد - Tiny App FA</h1>
            <div class="user-menu">
                <span>خوش آمدید, <?php echo $_SESSION['username']; ?></span>
                <a href="links.php">مدیریت لینک‌ها</a>
                <a href="logout.php">خروج</a>
            </div>
        </header>

        <main class="main-content">
            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'خطا') !== false ? 'error' : 'success'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="shortener-form">
                <h2>کوتاه کننده لینک</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="url">لینک اصلی:</label>
                        <input type="url" id="url" name="url" placeholder="https://example.com" required>
                    </div>
                    <button type="submit" class="btn">کوتاه کردن لینک</button>
                </form>

                <?php if ($shortUrl): ?>
                    <div class="result-box">
                        <label>لینک کوتاه شده:</label>
                        <div class="short-url">
                            <input type="text" value="<?php echo $shortUrl; ?>" readonly>
                            <button onclick="copyToClipboard('<?php echo $shortUrl; ?>')" class="btn-copy">کپی</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>