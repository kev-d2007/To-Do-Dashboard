<?php
require_once 'functions.php';
require_once 'database.php';
confirm_logged_in();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$exclude = [];
if (!empty($input['exclude']) && is_array($input['exclude'])) {
    $exclude = array_map('intval', $input['exclude']);
}
$user_id = (int)($_SESSION['user_id'] ?? 0);
if (!$user_id) {
    echo json_encode(['success' => false]);
    exit;
}

if (count($exclude) > 0) {
    $notin = implode(',', $exclude);
    $sql = "SELECT id, titel, prioriteit FROM taken WHERE user_id = ? AND afgerond = 0 AND id NOT IN ($notin) ORDER BY prioriteit ASC, id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            echo json_encode(['success' => true, 'task' => $row]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    $stmt = $conn->prepare("SELECT id, titel, prioriteit FROM taken WHERE user_id = ? AND afgerond = 0 ORDER BY prioriteit ASC, id DESC LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            echo json_encode(['success' => true, 'task' => $row]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false]);
    }
}
