<?php
// Use the same include paths as the rest of your project
require_once __DIR__ . '/includes/config.inc.php';
require_once __DIR__ . '/db-classes.php';  // optional if you want to reuse DatabaseHelper here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>About | COMP 3512 Assignment 1</title>
    <!-- Keep CSS path consistent with other pages -->
    <link rel="stylesheet" href="css/styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
<div class="container">

    <?php
    // Include header INSIDE the body so markup is valid
    include __DIR__ . '/includes/header.inc.php';
    ?>

    <h1 class="page-title">About This Project</h1>
    <p class="page-sub">COMP 3512 Assignment 1 | Mount Royal University</p>

    <div class="card stack">
        <h2>Overview</h2>
        <p>
            This project was created for COMP 3512 Assignment 1 at Mount Royal University.
            It demonstrates PHP-based data retrieval from a SQLite database named <strong>stocks.db</strong>.
            The goal is to build a multi-page website that displays stock information, user portfolios,
            and provides JSON API routes for testing.
        </p>
    </div>

    <div class="card stack">
        <h2>Technologies Used</h2>
        <ul>
            <li>PHP 8 with PDO (SQLite connection via <code>DatabaseHelper</code>)</li>
            <li>HTML5 and CSS3 for layout and styling</li>
            <li>JSON API endpoints for data access</li>
            <li>Git and GitHub for version control</li>
        </ul>
    </div>

    <div class="card stack">
        <h2>Team Information</h2>
        <p>Developed by: <strong>Kenny Tran & Naren Padmanabhaan</strong></p>
        <p>Institution: Mount Royal University</p>
    </div>

    <div class="card stack">
        <h2>GitHub Repository</h2>
        <p>
            View the source code on GitHub:
            <a href="https://github.com/Naren30305/Comp3512-Assignment-1" class="link" target="_blank" rel="noopener">
            https://github.com/Naren30305/Comp3512-Assignment-1
            </a>
        </p>
    </div>

    <?php
    // Live database info section (use the same connection approach as the rest of the site)
    echo "<div class='card stack'>";
    echo "<h2>Database Connection</h2>";

    try {
       
        $pdo = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));
        echo "<p>Connected successfully to <strong>stocks.db</strong>.</p>";

        // Count total companies (table name from your schema)
        $countCompanies = $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn();
        echo "<p>Companies in database: <strong>" . (int)$countCompanies . "</strong></p>";


        $pdo = null; // close connection
    } catch (Throwable $e) {
        echo "<p><strong>Database Error:</strong> " . $e->getMessage() . "</p>";
    }

    echo "</div>";
    ?>

</div>
</body>
</html>
