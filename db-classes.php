<?php

Class DatabaseHelper {

    public static function createConnection ($values = array()) {
        $connString = $values[0];
        $user = $values[1];
        $password = $values[2];
        $pdo = new PDO($connString, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
 
 }

Class CompaniesDB {
    private $connection;

    // Constructor receives an existing PDO connection
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Retrieves a single company record based on symbol
    public function getCompanyBySymbol($symbol) {
        $sql = "
            SELECT symbol, name, sector, subindustry, address, exchange,
                   website, description, latitude, longitude, financials
            FROM companies
            WHERE symbol = :symbol
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':symbol', $symbol);
        $statement->execute();
        // Fetch a single row (one company)
        return $statement->fetch();
    }
}



class HistoryDB {
    private $connection;

    // Constructor receives an existing PDO connection
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Retrieves all history records for a given company symbol
    public function getHistoryBySymbol($symbol) {
        $sql = "
            SELECT date, open, close, high, low, volume
            FROM history
            WHERE symbol = :symbol
            ORDER BY date DESC
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':symbol', $symbol);
        $statement->execute();

        // Fetch all rows as an array
        return $statement->fetchAll();
    }
}

?>