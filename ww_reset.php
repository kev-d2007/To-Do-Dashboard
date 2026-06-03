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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord resetten</title>
</head>
<body>
    <div class="achtergrond">
        <img src="img/achtergrond_inlogscherm.png" alt="Logo" class="achtergrond">
        <img src="img/logo.png" alt="Logo" class="logo_reset_boven">
        <div class="reset-container">
            <?php if ($message !== ''): ?>
                <div id="notice" style="padding:10px;margin-bottom:10px;border-radius:4px;<?php echo (isset($result) && $result===true) ? 'background:#e6ffed;color:#064a1f;border:1px solid #8ee0a6;' : 'background:#ffe6e6;color:#5a1a1a;border:1px solid #f0a6a6;'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                <?php if (isset($result) && $result === true): ?>
                    <script>setTimeout(function(){ window.location.href='login.php'; }, 3000);</script>
                <?php endif; ?>
            <?php endif; ?>

            <form action="ww_reset.php" method="post">
                <img src="img/logo.png" alt="Logo" class="logo_reset_container">
                <h2>Wachtwoord resetten</h2>
                <h4>Vul je gebruikersnaam en e-mailadres in om je wachtwoord te veranderen</h4>
                <h4>Gebruikersnaam:</h4>
                <input type="text" name="username" placeholder="Gebruikersnaam" required>
                <h4>E-mailadres:</h4>
                <input type="email" name="email" placeholder="E-mailadres" required>
                <h4>Nieuw wachtwoord:</h4>
                <input type="password" name="new_password" placeholder="Nieuw wachtwoord (min. 8 tekens)" required>
                <button type="submit" onclick="return confirm('Weet je zeker dat je je wachtwoord wilt resetten?')">Reset Wachtwoord</button>
            </form>
        </div>
    </div>
</body>
</html>