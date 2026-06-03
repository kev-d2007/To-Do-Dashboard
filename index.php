<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="/favicon.php" />
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg" />
</head>
<body>
    <?php
        include 'functions.php';
        confirm_logged_in();
        if  (logged_in()) {
            include 'menu.php';
        }
    ?>
    <main id="content"></main>
</body>
</html>