<?php require_once 'functions.php';
require_once 'database.php';

// bepaal huidig thema voor deze gebruiker (1 = licht, 2 = donker)
$theme = 1;
if (isset($_SESSION['user_id'])) {
    $uid = (int) $_SESSION['user_id'];
    if ($uid > 0) {
        $stmt = $conn->prepare("SELECT thema FROM settings WHERE user_id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $t = null;
            $stmt->bind_result($t);
            if ($stmt->fetch() && ($t === 1 || $t === 2 || $t === '1' || $t === '2')) {
                $theme = (int)$t;
            }
            $stmt->close();
        }
    }
}
?>
<h1>Instellingen</h1>

<div class="settings-grid">

    <div class="settings-card">
        <h4>ACCOUNT</h4>

        <div class="setting-row">
            <h3>Thema</h3>

            <input type="radio" name="theme" id="light-theme" value="1" <?php if ($theme === 1) echo 'checked'; ?>>
        <label for="light-theme">Light</label>

        <input type="radio" name="theme" id="dark-theme" value="2" <?php if ($theme === 2) echo 'checked'; ?>>
        <label for="dark-theme">Donker</label>
        </div>

        <hr>

        <div class="setting-row">
            <h3>Wachtwoord wijzigen</h3>
            <a href="ww_reset.php">Wijzigen →</a>
        </div>

        <hr>

        <div class="setting-row delete-account">
            <h3>Account verwijderen</h3>
            <button class="delete-btn">Verwijderen</button>
        </div>
    </div>

    <div class="settings-right">

        <div class="settings-card">
            <h4>MIJN PROFIEL</h4>

            <div class="profile-box">
                <div class="avatar">
                    <?php 
                            $gebruikersnaam = isset($_SESSION['username']) ? $_SESSION['username'] : '';
                            $letter = $gebruikersnaam !== '' ? $gebruikersnaam[0] : '?';
                            echo htmlspecialchars($letter);
                        ?>
                </div>

                <div>
                        <strong><?php echo htmlspecialchars($gebruikersnaam !== '' ? $gebruikersnaam : 'Gebruiker'); ?></strong><br>
                        <?php echo htmlspecialchars(isset($_SESSION['email']) ? $_SESSION['email'] : ''); ?>
                </div>
            </div>
        </div>

        <div class="settings-card">
            <h4>ACTIVITEIT</h4>

            <p>Taken aangemaakt <strong>222</strong></p>
            <p>Taken voltooid <strong>172</strong></p>
            <p>Lid sinds <strong>Juni 2026</strong></p>
        </div>

    </div>

</div>

<button class="save-btn">
    Opslaan
</button>

<script>
(function(){
    try{
        var theme = <?php echo json_encode($theme); ?>;
        if (theme === 2) document.body.classList.add('dark-mode');
        else document.body.classList.remove('dark-mode');
    }catch(e){}
})();

document.addEventListener('change', function(e){
    if (e.target && e.target.name === 'theme') {
        var val = e.target.value;
        fetch('save_theme.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ thema: parseInt(val,10) })
        }).then(function(res){ return res.json(); })
        .then(function(data){
            if (data && data.success) {
                if (parseInt(val,10) === 2) document.body.classList.add('dark-mode');
                else document.body.classList.remove('dark-mode');
            } else {
                alert('Kon thema niet opslaan.');
            }
        }).catch(function(){ alert('Netwerkfout bij opslaan thema.'); });
    }
});
</script>
