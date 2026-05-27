<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

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