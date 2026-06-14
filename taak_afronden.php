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

$id = isset($input['id']) ? (int)$input['id'] : 0;
$afgerond = isset($input['afgerond']) ? (int)$input['afgerond'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid id']);
    exit;
}

if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

global $conn;
$stmt = $conn->prepare("UPDATE taken SET afgerond = ? WHERE id = ? AND user_id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'DB prepare failed']);
    exit;
}
$stmt->bind_param('iii', $afgerond, $id, $user_id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'DB execute failed']);
}
