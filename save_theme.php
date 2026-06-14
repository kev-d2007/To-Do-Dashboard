<?php
require_once 'functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$thema = isset($input['thema']) ? (int)$input['thema'] : 1;
if ($thema !== 1 && $thema !== 2) $thema = 1;

if (!isset($_SESSION) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

global $conn;
// controleer of er al een row is
$stmt = $conn->prepare("SELECT user_id FROM settings WHERE user_id = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $up = $conn->prepare("UPDATE settings SET thema = ? WHERE user_id = ?");
        if (!$up) { echo json_encode(['success'=>false,'error'=>'DB error']); exit; }
        $up->bind_param('ii', $thema, $user_id);
        $ok = $up->execute();
        $up->close();
        if ($ok) {
            $_SESSION['theme'] = $thema;
            echo json_encode(['success'=>true]);
            exit;
        }
    } else {
        $stmt->close();
        $ins = $conn->prepare("INSERT INTO settings (user_id, thema) VALUES (?, ?)");
        if (!$ins) { echo json_encode(['success'=>false,'error'=>'DB error']); exit; }
        $ins->bind_param('ii', $user_id, $thema);
        $ok = $ins->execute();
        $ins->close();
        if ($ok) {
            $_SESSION['theme'] = $thema;
            echo json_encode(['success'=>true]);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'error' => 'DB failure']);
