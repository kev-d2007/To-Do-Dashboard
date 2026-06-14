<?php require_once 'functions.php'; require_once 'database.php'; confirm_logged_in(); ?>
<h1>Mijn taken</h1>

<div id="toast-container" aria-live="polite"></div>

<div class="task-input">
    <form method="POST" action="toevoegen.php">
        <input type="text" id="task-input" name="taak" placeholder="Voeg een nieuwe taak toe...">
        <select name="prioriteit" id="priority-select">
            <option value="3" style="background-color: hsl(62, 85%, 51%);">Laag</option>
            <option value="2" style="background-color: hsl(31, 80%, 53%);">Gemiddeld</option>
            <option value="1" style="background-color: hsl(0, 50%, 50%);">Hoog</option>
        </select>
        <button type="submit">Taak toevoegen</button>
    </form>
</div>

<script>
    (function(){
        const sel = document.getElementById('priority-select');
        if (!sel) return;
        function applyColor(){
            const opt = sel.options[sel.selectedIndex];
            const color = opt.style.backgroundColor || window.getComputedStyle(opt).backgroundColor;
            sel.style.backgroundColor = color || '';
        }
        sel.addEventListener('change', applyColor);
        applyColor();
    })();
</script>

<h2>Onvoltooide taken</h2>

<div class="dashboard-grid">

    <div class="left-panel">
        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = (int) $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT id, titel, afgerond, prioriteit FROM taken WHERE user_id = ? ORDER BY prioriteit ASC, id DESC LIMIT 5");
            if ($stmt) {
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $prio = (int) $row['prioriteit'];
                    $class = $prio === 1 ? 'priority-high' : ($prio === 2 ? 'priority-medium' : 'priority-low');
                    $checked = $row['afgerond'] ? 'checked' : '';
                    echo '<div class="task-card '.htmlspecialchars($class).'">';
                    echo '<input type="checkbox" class="task-complete" data-id="'.htmlspecialchars($row['id']).'" '.($checked).' />';
                    echo '<span>'.htmlspecialchars($row['titel']).'</span>';
                    echo '<div class="badge '.htmlspecialchars($class).'">'.htmlspecialchars($prio).'</div>';
                    echo '</div>';
                }
                $stmt->close();
            } else {
                echo '<p>Kon taken niet laden.</p>';
            }
        } else {
            echo '<p>Geen gebruiker ingelogd.</p>';
        }
        ?>

        <a href="#" class="all-tasks-link">alle taken</a>

    </div>

    <div class="right-panel">

        <div class="stats-card">

            <h4>Taakoverzicht vandaag</h4>

            <div class="stats-row">

                <div class="completed-box">
                    <span class="number">7</span>
                    <span>Voltooid</span>
                </div>

                <div class="aangemaakt-box">
                    <span class="number">3</span>
                    <span>Aangemaakt</span>
                </div>

            </div>

            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>

            <p>70% van de dagtaken zijn voltooid</p>
            <p>30% van de dagtaken zijn aangemaakt</p>

        </div>

        <!-- Voortgang van de week hieronder werkt niet, dus wordt weggelaten -->

        <!-- <div class="week-card">

            <h4>Voortgang deze week</h4>

            <div class="week-days">
                <div>Ma</div>
                <div>Di</div>
                <div>Wo</div>
                <div>Do</div>
                <div>Vr</div>
                <div>Za</div>
                <div>Zo</div>
            </div>

            <div class="week-legend">
            <span>⬜ Weinig</span>
            <span>🟩 Veel</span>

            </div> -->

        <div class="week-footer">
        <span>Gemiddeld voltooid</span>
        <strong>82%</strong>
        </div>

        </div>

    </div>

        

</div>