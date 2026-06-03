<?php include 'functions.php'; confirm_logged_in(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <aside class="sidebar">
        
    <img src="img/logo.png" alt="Logo" class="logo" id="main-logo">
    <h4>Welkom terug, <?= htmlspecialchars($_SESSION['username'] ?? '') ?>!</h4>
    <button type="button" class="menu-btn active" onclick="loadPage('dashboard.php', this)">
    <i class="fa-solid fa-table-columns"></i> Dashboard </button>

    <button type="button" class="menu-btn" onclick="loadPage('stats.php', this)">
    <i class="fa-solid fa-chart-simple"></i> Statistieken</button>

    <button type="button" class="menu-btn" onclick="loadPage('settings.php', this)">
    <i class="fa-solid fa-gear"></i> Instellingen</button>

    <a class="category-title">Categorieën</a>

    
    <button type="button" class="menu-btn logout-btn" onclick="logout()">
    <i class="fa-solid fa-right-from-bracket"></i> Uitloggen</button>

    </aside>

<main id="content">
    <?php include 'dashboard.php'; ?>
</main>

<script>
function loadPage(page, button) {

    document.querySelectorAll('.menu-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    button.classList.add('active');

    fetch(page)
        .then(response => {
            if (!response.ok) {
                throw new Error("Pagina niet gevonden.");
            }
            return response.text();
        })
        .then(html => {
            document.getElementById("content").innerHTML = html;
        })
        .catch(error => {
            document.getElementById("content").innerHTML = "<p>Fout bij laden van pagina.</p>";
            console.error(error);
        });
}
function logout() {
    if (confirm("Weet je zeker dat je wilt uitloggen?")) {
        window.location.href = "logout.php";
    }
}
</script>

</body>
</html>