<?php include 'functions.php'; ?>
<h1>Instellingen</h1>

<div class="settings-grid">

    <div class="settings-card">
        <h4>ACCOUNT</h4>

        <div class="setting-row">
            <h3>Thema</h3>

         <input type="radio" name="theme" id="light-theme">
    <label for="light-theme">Light</label>

    <input type="radio" name="theme" id="dark-theme">
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
                <div class="avatar">RC</div>

                <div>
                    <strong>Ron Cruz</strong><br>
                    ron@test.nl
                </div>
            </div>

            <button class="profile-btn">
                Profiel bewerken
            </button>
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
