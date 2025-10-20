<?php
// /api/history.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/config.inc.php';
require_once __DIR__ . '/../db-classes.php';

try {
    $connection = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
    $historyDB = new HistoryDB($connection);
    $companiesDB = new CompaniesDB($connection);

    //Check if the 'ref' (symbol) query parameter is provided in the URL
    if (isset($_GET['ref']) && trim($_GET['ref']) !== '') {
        $symbol = trim($_GET['ref']);
        $company = $companiesDB->getCompanyBySymbol($symbol);

        //If the company exists, get its historical data (sorted oldest→newest)
        if ($company) {
            $history = $historyDB->getHistoryBySymbolAsc($symbol);
            //Output both company info and history data in JSON format
            echo json_encode([
                "company" => $company,
                "history" => $history
            ]);
        } else {
            echo json_encode(["error" => "Company not found"]);
        }
    } else {
        echo json_encode(["error" => "Missing ?ref parameter"]);
    }

    $connection = null;
} catch (Throwable $e) {
    echo json_encode(["error" => $e->getMessage()]);
}