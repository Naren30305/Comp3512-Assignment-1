<?php
require_once 'includes/config.inc.php';
require_once 'db-classes.php';

// Connect to database
$connection = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));

$sql = "SELECT symbol, name, sector, subindustry
FROM companies
ORDER BY symbol";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Home Page</title>
</head>
<body>
    <?php include  'includes/header.inc.php'; ?>  
<main class="page container">
    <h1 class="page-title">Companies</h1>
    <p class="page-subtitle">Browse available companies and open their detail pages.</p>

    
</body>
</html>