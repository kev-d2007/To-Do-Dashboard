<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Home</title>
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
    <img src="img/logo.png" alt="Logo" class="logo" id="main-logo">
    <button onclick="get_page('dashboard.php')">Dashboard</button>
    <button onclick="get_page('stats.php')">Statistieken</button>
    <button onclick="get_page('settings.php')">Instellingen</button>
    <a>Categorieën</a>
    <button onclick="get_page('logout.php')">Uitloggen</button>
</body>
</html>