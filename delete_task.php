<?php
require_once 'functions.php';
require_once 'database.php';
header('Content-Type: application/json');
confirm_logged_in();
$input = json_decode(file_get_contents('php://input'), true);
$id = 0;
if (!empty($input['id'])) {
    $id = (int)$input['id'];
} elseif (!empty($_POST['id'])) {
    $id = (int)$_POST['id'];
}
$user_id = (int)($_SESSION['user_id'] ?? 0);
if (!$user_id || !$id) {
    echo json_encode(['success' => false, 'error' => 'missing']);
    exit;
}
$stmt = $conn->prepare("DELETE FROM taken WHERE id = ? AND user_id = ? LIMIT 1");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'db']);
    exit;
}
$stmt->bind_param('ii', $id, $user_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'exec']);
}
$stmt->close();
