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

    /**Retreive all companies sorted by name  */
    public function getAllCompanies(): array
    {
        $sql = "SELECT symbol, name, sector, subindustry, address, exchange,
                       website, description, latitude, longitude, financials
                FROM companies
                ORDER BY name ASC";
        return $this->connection->query($sql)->fetchAll();
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

    /** (API) History oldest -> newest (ASC) */
    public function getHistoryBySymbolAsc($symbol) {
        $sql = "
            SELECT date, open, close, high, low, volume
            FROM history
            WHERE symbol = :symbol COLLATE NOCASE
            ORDER BY date ASC
        ";
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':symbol', $symbol);
        $statement->execute();
        return $statement->fetchAll();
    }
}

Class UsersDB {
    private $connection;

    // Constructor receives an existing PDO connection
    public function __construct($connection) {
        $this->connection =$connection; 
    }

    // Retrieves all users sorted by last name
    public function getAllUsers() {
        $sql = "SELECT id, firstname, lastname, city, country, email 
        FROM users 
        ORDER BY lastname ASC ";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
        
    }


}

class PortfolioDB {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Get a user's portfolio with company details and latest closing price
    public function getPortfolioByUser($userid) {
        $sql = "
            SELECT p.symbol, p.amount, c.name, c.sector, h.close
            FROM portfolio p
            JOIN companies c ON p.symbol = c.symbol
            JOIN history h ON p.symbol = h.symbol
            WHERE p.userid = :userid
            AND h.date = (
                SELECT MAX(date) FROM history WHERE symbol = p.symbol
            )
            ORDER BY c.name ASC
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':userid', $userid);
        $statement->execute();
        return $statement->fetchAll();
    }

    // recordCount, totalShares, totalValue 
    public function getPortfolioSummaryByUser($userid) {
        $rows = $this->getPortfolioByUser($userid);

        $summary = [
            'recordCount' => 0,
            'totalShares' => 0,
            'totalValue'  => 0.0
        ];

        foreach ($rows as $r) {
            $summary['recordCount'] += 1;
            $summary['totalShares'] += (float)$r['amount'];
            $summary['totalValue']  += ((float)$r['amount']) * ((float)$r['close']);
        }

        // Round total value for stable display/API output
        $summary['totalValue'] = round($summary['totalValue'], 2);

        return $summary;
    }
}

?>