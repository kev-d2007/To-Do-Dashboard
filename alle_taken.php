<?php
require_once 'functions.php';
require_once 'database.php';
confirm_logged_in();
?>

<h1>Alle taken</h1>

<?php $counts = taken_tellen(); ?>

<div class="tasks-top">
    <div class="filters">
        <label><input type="checkbox" id="filter-open" checked> Openstaande taken</label>
        <label style="margin-left:12px;"><input type="checkbox" id="filter-completed" checked> Afgeronde taken</label>
    </div>

    <div class="panel overview">
        <h3>Overzicht</h3>
        <p>Onvoltooid: <strong id="count-open"><?php echo htmlspecialchars($counts['onvoltooid'] ?? 0); ?></strong></p>
        <p>Voltooid: <strong id="count-completed"><?php echo htmlspecialchars($counts['voltooid'] ?? 0); ?></strong></p>
    </div>
</div>

<div class="tasks-page">
    <div class="tasks-left">

        <div class="task-list">
            <?php
            $user_id = (int) $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT id, titel, afgerond, prioriteit FROM taken WHERE user_id = ? ORDER BY prioriteit ASC, afgerond ASC, id DESC");
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
                    echo '<div class="task-main">';
                    echo '<span class="task-title">'.htmlspecialchars($row['titel']).'</span>';
                    echo '<div class="task-meta">';
                    echo '</div>';
                    echo '</div>';
                    echo '<button class="task-delete" data-id="'.htmlspecialchars($row['id']).'" title="Verwijder taak"><i class="fa-solid fa-trash"></i></button>';
                    echo '<span class="badge '.htmlspecialchars($class).'">'.htmlspecialchars($prio).'</span>';
                    echo '</div>';
                }
                $stmt->close();
            } else {
                echo '<p>Kon taken niet laden.</p>';
            }
            ?>
        </div>

    </div>

    <aside class="tasks-right">
    </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var filterOpen = document.getElementById('filter-open');
    var filterCompleted = document.getElementById('filter-completed');

    function updateCounts(){
        var cards = Array.from(document.querySelectorAll('.task-list .task-card'));
        var open = 0, completed = 0;
        cards.forEach(function(card){
            var cb = card.querySelector('.task-complete');
            if (cb && cb.checked) completed++; else open++;
        });
        var elOpen = document.getElementById('count-open');
        var elCompleted = document.getElementById('count-completed');
        if (elOpen) elOpen.textContent = open;
        if (elCompleted) elCompleted.textContent = completed;
    }

    function applyFilter(){
        var showOpen = filterOpen.checked;
        var showCompleted = filterCompleted.checked;
        var cards = Array.from(document.querySelectorAll('.task-list .task-card'));
        cards.forEach(function(card){
            var cb = card.querySelector('.task-complete');
            var isCompleted = cb && cb.checked;
            var show = (isCompleted && showCompleted) || (!isCompleted && showOpen);
            card.style.display = show ? '' : 'none';
        });
        updateCounts();
    }

    if (filterOpen) filterOpen.addEventListener('change', applyFilter);
    if (filterCompleted) filterCompleted.addEventListener('change', applyFilter);

    document.addEventListener('change', function(e){
        if (e.target && e.target.classList && e.target.classList.contains('task-complete')){
            setTimeout(function(){ applyFilter(); }, 100);
        }
    });

    document.addEventListener('tasks-updated', function(){ applyFilter(); });

    applyFilter();
});
</script>
