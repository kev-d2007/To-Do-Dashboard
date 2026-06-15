<?php
require_once 'functions.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $wachtwoord = isset($_POST['wachtwoord']) ? trim($_POST['wachtwoord']) : null;
    $result = registreren($username, $email, $wachtwoord);
    if ($result === true) {
        $message = 'Account succesvol aangemaakt.';
        ?><script>console.log('Account succesvol aangemaakt.');</script><?php
    } elseif ($result === 'short') {
        $message = 'Het wachtwoord moet minimaal 8 karakters bevatten.';
    } elseif ($result === 'exists') {
        $message = 'Gebruikersnaam of e-mailadres is al in gebruik.';
    } else {
        $message = 'Er is iets misgegaan. Probeer het later opnieuw.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
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

            <form action="registreren.php" method="post">

                <div class="reset-title">
                    <img src="img/logo.png" alt="Logo" class="logo_reset_container">
                    <h2>Registreren</h2>
                </div>

                <p class="reset-subtitle">
                    Vul je gegevens in om je account te registreren.
                </p>

                <label>Gebruikersnaam (verplicht)</label>
                <input type="text" name="username" placeholder="Gebruikersnaam" required>

                <label>E-mailadres</label>
                <input type="email" name="email" placeholder="E-mailadres">

                <label>wachtwoord (verplicht)</label>
                <input type="password" name="wachtwoord" placeholder="Minimaal 8 tekens" required>

                <button type="submit" onclick="return">
                    Registreer nu!
                </button>

                <a href="login.php" class="back-login">Terug naar inloggen</a>

            </form>

        </div>

    </div>

</body>
</html>