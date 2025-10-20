<?php
require_once __DIR__ . '/includes/config.inc.php';
require_once __DIR__ . '/db-classes.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Tester</title>
    <link rel="stylesheet" href="css/styles.css">
    <?php
    include __DIR__ . '/includes/header.inc.php';
    ?>
</head>
<body>
<div class="container">

    <h1 class="page-title">API Tester Page</h1>
    <p class="page-sub">
        Use this page to verify that all API endpoints are working correctly. Each link below will open JSON data in a new tab.
    </p>

    <div class="card stack">
        <h2>Test API Routes</h2>
        <ul>
            <li><a href="api/companies.php" target="_blank" class="link">All Companies</a></li>
            <li><a href="api/companies.php?ref=AAPL" target="_blank" class="link">Company by Symbol (AAPL)</a></li>
            <li><a href="api/portfolio.php?ref=1" target="_blank" class="link">Portfolio by User (User 1)</a></li>
            <li><a href="api/history.php?ref=AAPL" target="_blank" class="link">History by Symbol (AAPL)</a></li>
        </ul>
    </div>


</div>
</body>
</html>
