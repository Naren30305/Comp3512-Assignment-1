<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/config.inc.php';
require_once __DIR__ . '/../db-classes.php';

try {
    $connection = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
    $companiesDB = new CompaniesDB($connection);

    //Check if the 'ref' (symbol) query parameter is provided in the URL
    if (isset($_GET['ref']) && trim($_GET['ref']) !== '') {
        $symbol = trim($_GET['ref']);
        $company = $companiesDB->getCompanyBySymbol($symbol);

        if ($company) {
            echo json_encode($company);
        } else {
            echo json_encode(["error" => "Company not found"]);
        }
    } else {
        $companies = $companiesDB->getAllCompanies();
        echo json_encode($companies);
    }

    $connection = null;
} catch (Throwable $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
