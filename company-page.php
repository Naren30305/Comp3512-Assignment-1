<?php
include('config.inc.php');

$pdo = database();

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
    <link rel="stylesheet" href="css/company-page.css">
    <title>Document Title</title>
</head>
<body>

</body>
</html>