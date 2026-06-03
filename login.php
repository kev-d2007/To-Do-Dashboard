<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
</head>
<body>
    <div class="achtergrond">
        <img src="img/achtergrond_inlogscherm.png" alt="Logo" class="achtergrond">
        <img src="img/logo.png" alt="Logo" class="logo_login_boven">
        <div class="login-container">
            <img src="img/logo.png" alt="Logo" class="logo_login_container">
            <h2>Welkom bij het To-Do Dashboard</h2>
            <h2>Log in om verder te gaan</h2>
            <form method="post">
                <h4>Gebruikersnaam:</h4>
                <input type="text" name="username" placeholder="Gebruikersnaam" required>
                <h4>Wachtwoord:</h4>
                <input type="password" name="password" placeholder="Wachtwoord" required>
                <button type="submit" onclick="return check_login();">Inloggen</button>
            </form>
            <button type="button" onclick="window.location.href='ww_reset.php'">Wachtwoord vergeten?</button>
        </div>
    </div>
    <?php
    include 'functions.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        if ($username === '' || $password === '') {
            $message = 'Vul zowel gebruikersnaam als wachtwoord in.';
            echo htmlspecialchars($message);
        } else {
            if (login($username, $password)) {
                header('Location: index.php');
                echo '<script>console.log("Inloggen succesvol.");</script>';
                exit;
            } else {
                $message = 'Ongeldige gebruikersnaam of wachtwoord.';
                echo htmlspecialchars($message);
            }
        }
    }
    ?>
</body>
</html>