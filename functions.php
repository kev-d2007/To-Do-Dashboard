<?php
require_once 'database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!function_exists('logged_in')) {
    function logged_in() {
        return isset($_SESSION['user_id']);
    }
}
    if (isset($_SESSION['user_id']) && (!isset($_SESSION['username']) || !isset($_SESSION['email']))) {
        $uid = (int) $_SESSION['user_id'];
        if ($uid > 0) {
            $stmt = $conn->prepare("SELECT gebruiker, email, gemaakt_op FROM users WHERE id = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('i', $uid);
                $stmt->execute();
                $g = '';
                $e = '';
                $gemaakt = '';
                $stmt->bind_result($g, $e, $gemaakt);
                if ($stmt->fetch()) {
                    $_SESSION['username'] = $g !== null ? $g : '';
                    $_SESSION['email'] = $e !== null ? $e : '';
                    $_SESSION['gemaakt_op'] = $gemaakt !== null ? $gemaakt : '';
                }
            }
        }
    }

    // laad thema-instelling altijd uit de DB voor gebruiker
    if (isset($_SESSION['user_id'])) {
        $uid = (int) $_SESSION['user_id'];
        $theme_val = 1;
        if ($uid > 0) {
            $stmt_t = $conn->prepare("SELECT thema FROM settings WHERE user_id = ? LIMIT 1");
            if ($stmt_t) {
                $stmt_t->bind_param('i', $uid);
                $stmt_t->execute();
                $t = null;
                $stmt_t->bind_result($t);
                if ($stmt_t->fetch()) {
                    $theme_val = ((string)$t === '2') ? 2 : 1;
                }
                $stmt_t->close();
            }
        }
        $_SESSION['theme'] = $theme_val;
    }


if (!function_exists('confirm_logged_in')) {
    function confirm_logged_in() {
    // tijdelijk uitgeschakeld zodat je direct naar menu.php kan
    // waarom doe je dit?
    return true;
}
    }

if (!function_exists('login')) {
    function login($user_id = 0, $gebruiker = '', $email = '', $gemaakt_op = null) {
        $_SESSION['user_id'] = isset($user_id) ? (int)$user_id : 0;
        $_SESSION['username'] = isset($gebruiker) ? $gebruiker : '';
        $_SESSION['email'] = isset($email) ? $email : '';
        $_SESSION['gemaakt_op'] = isset($gemaakt_op) ? $gemaakt_op : '';

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

//telt de taken
function taken_tellen($user_id = null) {
    global $conn;
    if ($user_id === null || $user_id === '') {
        if (isset($_SESSION['user_id'])) {
            $user_id = (int) $_SESSION['user_id'];
        } else {
            return ['totaal' => 0, 'voltooid' => 0, 'onvoltooid' => 0];
        }
    }
    $user_id = (int) $user_id;
    $stmt = $conn->prepare(
        "SELECT 
            COUNT(*) AS totaal_taken,
            SUM(afgerond = 1) AS voltooide_taken,
            SUM(afgerond = 0) AS onvoltooide_taken,
            SUM(prioriteit = 1 AND afgerond = 0) AS hoog
        FROM taken
        WHERE user_id = ?"
    );
    if (!$stmt) return ['totaal' => 0, 'voltooid' => 0, 'onvoltooid' => 0, 'hoog' => 0];
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$data) return ['totaal' => 0, 'voltooid' => 0, 'onvoltooid' => 0, 'hoog' => 0];

    $totaal_taken = isset($data['totaal_taken']) ? (int)$data['totaal_taken'] : 0;
    $voltooide_taken = isset($data['voltooide_taken']) ? (int)$data['voltooide_taken'] : 0;
    $onvoltooide_taken = isset($data['onvoltooide_taken']) ? (int)$data['onvoltooide_taken'] : 0;
    $hoog = isset($data['hoog']) ? (int)$data['hoog'] : 0;
    return ['totaal' => $totaal_taken, 'voltooid' => $voltooide_taken, 'onvoltooid' => $onvoltooide_taken, 'hoog' => $hoog];
}

function prestatie() {
    $counts = taken_tellen();
    $voltooide_taken = isset($counts['voltooid']) ? (int)$counts['voltooid'] : 0;
    $open_taken = isset($counts['onvoltooid']) ? (int)$counts['onvoltooid'] : 0;
    $total = $voltooide_taken + $open_taken;
    $percentage = $total > 0 ? round(($voltooide_taken / $total) * 100, 2) : 0;
    if ($percentage > 80) {
        return 'Je bent geweldig bezig!';
    } else if ($percentage > 60) {
        return 'Je bent goed op weg!';
    } else if ($percentage > 40) {
        return 'Het kan beter gaan...';
    } else if ($percentage > 20) {
        return 'Je hebt nog veel te doen!';
    } else {
        return 'Je moet taken afronden!';
    }
}
?>