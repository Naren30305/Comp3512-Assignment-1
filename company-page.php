<?php
require_once 'includes/config.inc.php';
require_once 'db-classes.php';

// Connect to database
$connection = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));

//Company details
$sql1 = "SELECT symbol, name, sector, subindustry, address, exchange, website, description,
       latitude, longitude, financials";

// Historical prices (oldest → newest)
$sql2 = "SELECT date, open, close, high, low, volume
FROM history
WHERE symbol = :symbol
ORDER BY date ASC";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Compnay page</title>
</head>
<body>
</body>
</html>