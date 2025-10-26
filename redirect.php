<?php
include 'includes/config.php';

if (isset($_GET['code'])) {
    $shortCode = $_GET['code'];
    
    try {
        $stmt = $pdo->prepare("SELECT original_url FROM links WHERE short_code = ?");
        $stmt->execute([$shortCode]);
        $link = $stmt->fetch();
        
        if ($link) {
            // ریدایرکت به لینک اصلی
            header('Location: ' . $link['original_url']);
            exit;
        } else {
            // اگر لینک پیدا نشد
            http_response_code(404);
            die('لینک پیدا نشد!');
        }
    } catch(PDOException $e) {
        http_response_code(500);
        die('خطای سرور');
    }
} else {
    // اگر کد مشخص نشده
    header('Location: index.php');
    exit;
}
?>