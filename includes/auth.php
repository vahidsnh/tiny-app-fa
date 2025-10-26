<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function login($username, $password) {
    // کاربر پیشفرض موقت
    if ($username === 'admin' && $password === '123456') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';
        return true;
    }
    
    // بررسی از دیتابیس
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            return true;
        }
    } catch(PDOException $e) {
        // اگر دیتابیس مشکل داشت، از کاربر پیشفرض استفاده کن
        if ($username === 'admin' && $password === '123456') {
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = 'admin';
            return true;
        }
    }
    
    return false;
}

function logout() {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>