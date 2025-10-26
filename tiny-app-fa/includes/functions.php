<?php
function generateShortCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $shortCode = '';
    
    for ($i = 0; $i < $length; $i++) {
        $shortCode .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $shortCode;
}

// تابع برای بررسی تکراری نبودن کد
function isShortCodeUnique($shortCode) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT id FROM links WHERE short_code = ?");
        $stmt->execute([$shortCode]);
        return $stmt->rowCount() === 0;
    } catch(PDOException $e) {
        return false;
    }
}
?>