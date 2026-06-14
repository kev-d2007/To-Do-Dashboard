<?php
    include 'database.php';
    require_once 'functions.php';
    confirm_logged_in();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task = $_POST['taak'];
        $priority = $_POST['prioriteit'];
        $user_id = (int) $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO taken (titel, prioriteit, user_id) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sii', $task, $priority, $user_id);
            if ($stmt->execute()) {
                header('Location: index.php');
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