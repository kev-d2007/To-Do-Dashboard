<?php
include 'database.php';
session_start();
    function logged_in() {
        return isset($_SESSION['user_id']);
    }
    function confirm_logged_in() {
        if (!logged_in()) {
            header("Location: login.php");
        }
    }
    function login($user_id) {
        $_SESSION['user_id'] = $user_id;
    }
    function logout() {
        session_destroy();
        header("Location: login.php");
    }
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
?>
