<?php
require_once 'database.php';
require_once 'functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if ($identifier === '' || $password === '') {
        $error = 'Vul gebruikersnaam/e-mailadres en wachtwoord in.';
    } else {
        global $conn;
        $stmt = $conn->prepare("SELECT id, gebruiker, wachtwoord FROM users WHERE gebruiker = ? OR email = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('ss', $identifier, $identifier);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                $error = 'Gebruiker niet gevonden.';
            } else {
                $id = null;
                $gebruiker = null;
                $hash = null;
                $stmt->bind_result($id, $gebruiker, $hash);
                $stmt->fetch();
                if ($hash !== null && password_verify($password, $hash)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $gebruiker;
                    header('Location: menu.php');
                    exit;
                } else {
                    $error = 'Ongeldige gegevens.';
                }
            }
            $stmt->close();
        } else {
            $error = 'Er is een fout opgetreden.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="achtergrond">
        <img src="img/achtergrond_inlogscherm.png" alt="Logo" class="achtergrond">
        <img src="img/logo.png" alt="Logo" class="logo_login_boven">
        <div class="login-container">
            <img src="img/logo.png" alt="Logo" class="logo_login_container">
            <h2>Welkom bij het To-Do Dashboard</h2>
            <?php if ($error !== ''): ?>
                <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <h4>Gebruikersnaam of e-mailadres:</h4>
                <input type="text" name="username" placeholder="Gebruikersnaam of e-mail" required>
                <h4>Wachtwoord:</h4>
                <input type="password" name="password" placeholder="Wachtwoord" required>
                <button type="submit">Inloggen</button>
            </form>
            <button type="button" onclick="window.location.href='ww_reset.php'">Wachtwoord vergeten?</button>
        </div>
    </div>
</body>
</html>