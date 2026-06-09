<?php
require_once 'functions.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : null;
    if ($username === '' || $email === '') {
        $message = 'Vul zowel gebruikersnaam als e-mailadres in.';
    } else {
        $result = reset_ww($username, $email, $new_password);
        if ($result === true) {
            $message = 'Wachtwoord succesvol gewijzigd.';
            ?><script>console.log('Wachtwoord succesvol gewijzigd.');</script><?php
        } elseif ($result === 'short') {
            $message = 'Het wachtwoord moet minimaal 8 karakters bevatten.';
        } elseif ($result === 'same') {
            $message = 'Het nieuwe wachtwoord mag niet hetzelfde zijn als het huidige wachtwoord.';
        } elseif ($result === 'no_user') {
            $message = 'Gebruikersnaam en e-mail komen niet overeen.';
        } else {
            $message = 'Er is iets misgegaan. Probeer het later opnieuw.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord resetten</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

        <div class="reset-page">
            <div class="top-header">
            <img src="img/logo.png" alt="Logo">
            <h2>To-Do Dashboard</h2>
        </div>

        <div class="reset-container">

            <?php if ($message !== ''): ?>
                <div class="notice <?php echo (isset($result) && $result === true) ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

                <?php if (isset($result) && $result === true): ?>
                    <script>
                        setTimeout(function(){
                            window.location.href = 'login.php';
                        }, 3000);
                    </script>
                <?php endif; ?>
            <?php endif; ?>

            <form action="ww_reset.php" method="post">

                <div class="reset-title">
                    <img src="img/logo.png" alt="Logo" class="logo_reset_container">
                    <h2>Wachtwoord resetten</h2>
                </div>

                <p class="reset-subtitle">
                    Vul je gegevens in om je wachtwoord te veranderen.
                </p>

                <label>Gebruikersnaam</label>
                <input type="text" name="username" placeholder="Gebruikersnaam" required>

                <label>E-mailadres</label>
                <input type="email" name="email" placeholder="E-mailadres" required>

                <label>Nieuw wachtwoord</label>
                <input type="password" name="new_password" placeholder="Minimaal 8 tekens" required>

                <button type="submit" onclick="return confirm('Weet je zeker dat je je wachtwoord wilt resetten?')">
                    Reset wachtwoord
                </button>

                <a href="login.php" class="back-login">Terug naar inloggen</a>

            </form>

        </div>

    </div>

</body>
</html>