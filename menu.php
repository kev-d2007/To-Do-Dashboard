<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <aside class="sidebar">
        
    <img src="img/logo.png" alt="Logo" class="logo" id="main-logo">
    <button type="button" class="menu-btn active" onclick="loadPage('dashboard.php')"> Dashboard</button>

    <button type="button" class="menu-btn" onclick="loadPage('stats.php')">Statistieken</button>

    <button type="button" class="menu-btn" onclick="loadPage('settings.php')">Instellingen</button>

    <a class="category-title">Categorieën</a>

    <button type="button" class="menu-btn" onclick="logout()">Uitloggen</button>

    </div>

<main id="content">
    <?php include 'dashboard.php'; ?>
</main>

<script>
function loadPage(page) {
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
            document.getElementById("content").innerHTML = "<p>Fout bij laden van pagina.</p><p>" + error.message + "</p><p>Als het probleem zich blijft voordoen, neem dan contact op met de beheerder.</p>";
            console.error(error);
        });
    console.log("Laad pagina: " + page);
}
function logout() {
    if (confirm("Weet je zeker dat je wilt uitloggen?")) {
        window.location.href = "login.php";
        session_destroy();
    }
}
</script>

</body>
</html>