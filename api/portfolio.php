<?php
// /api/portfolio.php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/config.inc.php';
require_once __DIR__ . '/../db-classes.php';

try {
    $connection = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
    $usersDB = new UsersDB($connection);
    $portfolioDB = new PortfolioDB($connection);

    //Check if the 'ref' (symbol) query parameter is provided in the URL
    if (isset($_GET['ref']) && trim($_GET['ref']) !== '') {
        $userid = $_GET['ref'];
        $user = $usersDB->getUserById($userid);

        if ($user) {
            $portfolio = $portfolioDB->getPortfolioByUser($userid);
            $summary = $portfolioDB->getPortfolioSummaryByUser($userid);

            echo json_encode([
                "user" => $user,
                "summary" => $summary,
                "portfolio" => $portfolio
            ]);
        } else {
            echo json_encode(["error" => "User not found"]);
        }
    } else {
        echo json_encode(["error" => "Missing ?ref parameter"]);
    }

    $connection = null;
} catch (Throwable $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
