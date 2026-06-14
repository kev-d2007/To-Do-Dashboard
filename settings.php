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
            <h5>Niet werkend, want het is niet nodig.</h5>
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

            <p>Taken aangemaakt <strong><?php 
            $counts = taken_tellen();
            echo htmlspecialchars($counts['totaal']); ?></strong></p>
            <p>Taken voltooid <strong><?php echo htmlspecialchars($counts['voltooid']); ?></strong></p>
            <?php
                $gemaakt_display = 'Onbekend';
                if (isset($_SESSION['user_id'])) {
                    $uid = (int) $_SESSION['user_id'];
                    $stmt_d = $conn->prepare("SELECT gemaakt_op FROM users WHERE id = ? LIMIT 1");
                    if ($stmt_d) {
                        $stmt_d->bind_param('i', $uid);
                        $stmt_d->execute();
                        $gemaakt_op = null;
                        $stmt_d->bind_result($gemaakt_op);
                        if ($stmt_d->fetch() && $gemaakt_op) {
                            $ts = strtotime($gemaakt_op);
                            if ($ts !== false) {
                                $gemaakt_display = date('j-m-Y h:i:s', $ts);
                            } else {
                                $gemaakt_display = $gemaakt_op;
                            }
                        }
                        $stmt_d->close();
                    }
                }
            ?>
            <p>Lid sinds <strong><?php echo htmlspecialchars($gemaakt_display); ?></strong></p>
        </div>

    </div>

</div>

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
