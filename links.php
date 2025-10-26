<?php
include 'includes/config.php';
include 'includes/auth.php';

// اگر کاربر لاگین نکرده، برگرد به صفحه لاگین
if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// حذف لینک
if (isset($_GET['delete'])) {
    $linkId = $_GET['delete'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM links WHERE id = ?");
        $stmt->execute([$linkId]);
        $message = 'لینک با موفقیت حذف شد!';
    } catch(PDOException $e) {
        $message = 'خطا در حذف لینک';
    }
}

// دریافت تمام لینک‌ها
try {
    $stmt = $pdo->query("SELECT * FROM links ORDER BY created_at DESC");
    $links = $stmt->fetchAll();
} catch(PDOException $e) {
    $links = [];
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت لینک‌ها - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>مدیریت لینک‌ها</h1>
            <div class="user-menu">
                <span>خوش آمدید, <?php echo $_SESSION['username']; ?></span>
                <a href="dashboard.php">داشبورد</a>
                <a href="logout.php">خروج</a>
            </div>
        </header>

        <main class="main-content">
            <?php if (isset($message)): ?>
                <div class="message <?php echo strpos($message, 'خطا') !== false ? 'error' : 'success'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="links-container">
                <h2>لینک‌های کوتاه شده</h2>
                
                <?php if (empty($links)): ?>
                    <div class="empty-state">
                        <p>هنوز هیچ لینکی ایجاد نکرده‌اید!</p>
                        <a href="dashboard.php" class="btn">ایجاد لینک جدید</a>
                    </div>
                <?php else: ?>
                    <div class="links-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>لینک اصلی</th>
                                    <th>لینک کوتاه</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($links as $link): ?>
                                    <tr>
                                        <td class="original-url">
                                            <a href="<?php echo $link['original_url']; ?>" target="_blank">
                                                <?php echo substr($link['original_url'], 0, 50) . (strlen($link['original_url']) > 50 ? '...' : ''); ?>
                                            </a>
                                        </td>
                                        <td class="short-url">
                                            <a href="<?php echo BASE_URL . '/' . $link['short_code']; ?>" target="_blank">
                                                <?php echo BASE_URL . '/' . $link['short_code']; ?>
                                            </a>
                                        </td>
                                        <td class="date">
                                            <?php echo date('Y/m/d H:i', strtotime($link['created_at'])); ?>
                                        </td>
                                        <td class="actions">
                                            <button onclick="copyToClipboard('<?php echo BASE_URL . '/' . $link['short_code']; ?>')" class="btn-copy">کپی</button>
                                            <a href="?delete=<?php echo $link['id']; ?>" class="btn-delete" onclick="return confirm('آیا از حذف این لینک مطمئن هستید؟')">حذف</a>
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

    <script src="assets/js/script.js"></script>
</body>
</html>