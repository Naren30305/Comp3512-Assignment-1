<?php
// points to your SQLite database using an absolute path

// Determine the full path to your project root
define('APP_ROOT', dirname(__DIR__));

// Absolute path to the data/stocks.db file
define('DB_PATH', APP_ROOT . '/data/stocks.db');

// Absolute SQLite connection string so it works everywhere
define('DBCONNSTRING', 'sqlite:' . DB_PATH);

define('DBUSER', null);
define('DBPASS', null);
?>
