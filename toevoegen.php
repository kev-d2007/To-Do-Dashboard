<?php
    include 'database.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task = $_POST['taak'];
        $sql = "INSERT INTO info VALUES ('$task')";
        if (mysqli_query($conn, $sql)) {
            echo "Nieuwe taak toegevoegd!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
?>