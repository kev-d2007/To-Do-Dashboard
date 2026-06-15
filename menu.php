<?php require_once 'functions.php'; confirm_logged_in(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body<?php echo (isset($_SESSION['theme']) && $_SESSION['theme'] == 2) ? ' class="dark-mode"' : ''; ?>>
    <div id="toast-container" aria-live="polite"></div>
    <aside class="sidebar">
        
    <div class="logo-box">
    <img src="img/logo.png" alt="Logo" id="main-logo">
    </div>
    <div class="welkom-box">
        <?php $display_name = isset($_SESSION['username']) && $_SESSION['username'] !== '' ? $_SESSION['username'] : 'Gebruiker'; ?>
        <p style="color: #d9dadc;">Welkom terug, <strong style="color: #fc2121; font: bold;"><?php echo htmlspecialchars($display_name); ?></strong></p>
    </div>
    <button type="button" class="menu-btn" data-page="dashboard.php" onclick="loadPage('dashboard.php', this)">
    <i class="fa-solid fa-table-columns"></i> Dashboard </button>

    <button type="button" class="menu-btn" data-page="stats.php" onclick="loadPage('stats.php', this)">
    <i class="fa-solid fa-chart-simple"></i> Statistieken</button>

    <button type="button" class="menu-btn" data-page="settings.php" onclick="loadPage('settings.php', this)">
    <i class="fa-solid fa-gear"></i> Instellingen</button>

    <a class="category-title">Categorieën</a>

    
    <div class="logout-section">
    <button class="menu-btn" onclick="logout()">
        <i class="fa-solid fa-right-from-bracket"></i>
        Afmelden
    </button>
    </div>
    

    </aside>

<?php
    $allowed_pages = ['dashboard.php','stats.php','settings.php','alle_taken.php'];
    $page = 'dashboard.php';
    if (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
        $page = $_GET['page'];
    }
?>
<main id="content">
    <?php include $page; ?>
</main>

<script>
function loadPage(page, button) {

    document.querySelectorAll('.menu-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    if (!button) {
        button = document.querySelector('.menu-btn[data-page="' + page + '"]');
    }
    if (button) button.classList.add('active');

    fetch(page)
        .then(response => {
            if (!response.ok) {
                throw new Error("Pagina niet gevonden.");
            }
            return response.text();
        })
        .then(html => {
            document.getElementById("content").innerHTML = html;
            try {
                history.pushState({page: page}, '', 'menu.php?page=' + encodeURIComponent(page));
            } catch (e) {}
        })
        .catch(error => {
            document.getElementById("content").innerHTML = "<p>Fout bij laden van pagina.</p>";
            console.error(error);
        });
}

window.addEventListener('popstate', function(e){
    var page = (e.state && e.state.page) ? e.state.page : (new URLSearchParams(location.search)).get('page') || 'dashboard.php';
    loadPage(page);
});

document.addEventListener('DOMContentLoaded', function(){
    var current = (new URLSearchParams(location.search)).get('page') || 'dashboard.php';
    var btn = document.querySelector('.menu-btn[data-page="' + current + '"]');
    if (btn) btn.classList.add('active');
});
function logout() {
    if (confirm("Weet je zeker dat je wilt uitloggen?")) {
        window.location.href = "logout.php";
    }
}
</script>

<script>
document.addEventListener('change', function(e){
    if(e.target.id === 'dark-theme'){
        document.body.classList.add('dark-mode');
    }

    if(e.target.id === 'light-theme'){
        document.body.classList.remove('dark-mode');
    }
});
</script>

<script>
function showToast(message){
    var container = document.getElementById('toast-container');
    if(!container) return;
    var toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(function(){
        toast.classList.add('hide');
        setTimeout(function(){ if (toast.parentNode) toast.parentNode.removeChild(toast); }, 300);
    }, 3000);
}

document.addEventListener('change', function(e){
    var target = e.target;
    if (!target) return;
    if (target.classList && target.classList.contains('task-complete')) {
        var checkbox = target;
        var id = checkbox.getAttribute('data-id');
        var val = checkbox.checked ? 1 : 0;
        fetch('taak_afronden.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, afgerond: val })
        }).then(function(res){ return res.json(); })
        .then(function(data){
            if(!data || !data.success){
                alert('Kon taak niet bijwerken.');
                checkbox.checked = !checkbox.checked;
            } else {
                if(val === 1){
                    showToast('Taak afgerond');
                    // na verdwijnen toast verwijderen we de voltooide taak en vullen we aan
                    setTimeout(function(){
                        // verwijder de taakkaart
                        var card = checkbox.closest('.task-card');
                        if (card && card.parentNode) card.parentNode.removeChild(card);

                        // bepaal huidige getoonde ids om uit te sluiten
                        var ids = Array.from(document.querySelectorAll('.task-card input[data-id]')).map(function(i){ return i.getAttribute('data-id'); });

                        // vraag één volgende onvoltooide taak op
                        fetch('volgende_taak.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ exclude: ids })
                        }).then(function(res){ return res.json(); })
                        .then(function(resp){
                            if(resp && resp.success && resp.task){
                                var t = resp.task;
                                var prioClass = t.prioriteit == 1 ? 'priority-high' : (t.prioriteit == 2 ? 'priority-medium' : 'priority-low');
                                var newCard = document.createElement('div');
                                newCard.className = 'task-card ' + prioClass;
                                newCard.innerHTML = '<input type="checkbox" class="task-complete" data-id="' + t.id + '" />'
                                    + '<span>' + escapeHtml(t.titel) + '</span>'
                                    + '<div class="badge ' + prioClass + '">' + t.prioriteit + '</div>';
                                var left = document.querySelector('.left-panel');
                                var allLink = left ? left.querySelector('.all-tasks-link') : null;
                                if (allLink) left.insertBefore(newCard, allLink);
                                else if (left) left.appendChild(newCard);
                            }
                        }).catch(function(){});
                    }, 3300);
                }
                else { showToast('Taak heropend'); }
            }
        }).catch(function(){
            alert('Netwerkfout bij bijwerken taak.');
            checkbox.checked = !checkbox.checked;
        });
    }
});

// delegated delete handler for task delete buttons
document.addEventListener('click', function(e){
    var t = e.target;
    if (!t) return;
    // support clicking inner <i> too
    if (t.classList && t.classList.contains('task-delete')) {
        var btn = t;
    } else if (t.closest && t.closest('.task-delete')) {
        var btn = t.closest('.task-delete');
    } else return;

    var id = btn.getAttribute('data-id');
    if (!id) return;
    if (!confirm('Weet je zeker dat je deze taak wilt verwijderen?')) return;

    fetch('delete_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    }).then(function(res){ return res.json(); })
    .then(function(data){
        if (data && data.success) {
            var card = btn.closest('.task-card');
            if (card && card.parentNode) card.parentNode.removeChild(card);
            showToast('Taak verwijderd');
            // notify pages to update counts/filters
            document.dispatchEvent(new CustomEvent('tasks-updated'));
        } else {
            alert('Kon taak niet verwijderen.');
        }
    }).catch(function(){
        alert('Netwerkfout bij verwijderen.');
    });
});

// helper voor veilige HTML-escape (klein)
function escapeHtml(str){
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}
</script>

</body>
</html>