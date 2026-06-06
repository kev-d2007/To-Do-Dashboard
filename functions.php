<?php
//require_once 'database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!function_exists('logged_in')) {
    function logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('confirm_logged_in')) {
    function confirm_logged_in() {
        if (!logged_in()) {
            header("Location: login.php");
            exit;
        }
    }
}

if (!function_exists('login')) {
    function login($user_id) {
        $_SESSION['user_id'] = $user_id;
    }
}

if (!function_exists('logout')) {
    function logout() {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
if (!function_exists('reset_ww')) {
    function reset_ww($username, $email, $new_password = null) {
        global $conn;
        if ($new_password === null || $new_password === '') {
            return 'short';
        }
        if (strlen($new_password) < 8) {
            return 'short';
        }

        $stmt = $conn->prepare("SELECT id, wachtwoord FROM users WHERE gebruiker = ? AND email = ?");
        if (!$stmt) return false;
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $id = null;
        $current_hash = null;
        $stmt->bind_result($id, $current_hash);
        $found = $stmt->fetch();
        $stmt->close();
        if (!$found) {
            return 'no_user';
        }
        if ($current_hash !== null && password_verify($new_password, $current_hash)) {
            return 'same';
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmd = $conn->prepare("UPDATE users SET wachtwoord = ? WHERE id = ?");
        if (!$stmd) return false;
        $stmd->bind_param('si', $hashed, $id);
        $ok = $stmd->execute();
        $stmd->close();
        return $ok ? true : false;
    }
}
?>