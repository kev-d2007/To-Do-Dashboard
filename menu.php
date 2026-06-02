<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <script>
        function get_page(page) {
            <?php
            $page = $_GET['page'] ?? 'dashboard.php';
            include_once $page;
            ?>
            console.log(page + " is aan het laden...");
        }
    </script>

    <div class="sidebar">

        
    <img src="img/logo.png" alt="Logo" class="logo" id="main-logo">
    <button class="menu-btn active" onclick="get_page('dashboard.php')"> Dashboard</button>

    <button class="menu-btn" onclick="get_page('stats.php')">Statistieken</button>

    <button class="menu-btn" onclick="get_page('settings.php')">Instellingen</button>

    <a class="category-title">Categorieën</a>

    <button class="menu-btn" onclick="get_page('logout.php')">Uitloggen</button>

    </div>
    

<aside class="sidebar">
    <img src="img/logo.png" alt="Logo" class="logo">

    <button type="button" onclick="loadPage('dashboard.php')">Dashboard</button>
    <button type="button" onclick="loadPage('stats.php')">Statistieken</button>
    <button type="button" onclick="loadPage('settings.php')">Instellingen</button>
    <button type="button" onclick="loadPage('logout.php')">Uitloggen</button>
</aside>

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
}
</script>

</body>
</html>