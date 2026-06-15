<?php
    include 'database.php';
    require_once 'functions.php';
    confirm_logged_in();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task = isset($_POST['taak']) ? trim($_POST['taak']) : '';
        $priority = isset($_POST['prioriteit']) ? (int) $_POST['prioriteit'] : 3;
        $user_id = (int) $_SESSION['user_id'];

        $referer = isset($_SERVER['HTTP_REFERER']) ? strtok($_SERVER['HTTP_REFERER'], '#') : 'index.php';

        if ($task === '') {
            header('Location: ' . $referer . (strpos($referer, '?') === false ? '?' : '&') . 'error=empty');
            exit;
        }

        $stmt_check = $conn->prepare("SELECT id FROM taken WHERE user_id = ? AND titel = ? LIMIT 1");
        if ($stmt_check) {
            $stmt_check->bind_param('is', $user_id, $task);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $stmt_check->close();
                header('Location: ' . $referer . (strpos($referer, '?') === false ? '?' : '&') . 'error=exists');
                exit;
            }
            $stmt_check->close();
        }

        $stmt = $conn->prepare("INSERT INTO taken (titel, prioriteit, user_id) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sii', $task, $priority, $user_id);
            if ($stmt->execute()) {
                header('Location: ' . $referer);
                exit;
            } else {
                echo 'DB fout: ' . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            echo 'DB fout: ' . htmlspecialchars($conn->error);
        }
    }
?>