<?php
define('DBCONNSTRING', 'sqlite:data/stocks.db');  // points to your SQLite file
define('DBUSER', null);
define('DBPASS', null);

//Function used to connection to database
function database(): PDO {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}
?>

