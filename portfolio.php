<?php
require_once 'includes/config.inc.php';
require_once 'db-classes.php';


// Get user ID from the query string
if (isset($_GET['userid'])) {
    $userid = $_GET['userid']; 
} else {
    $userid = 0;
}

try {
    //Connect to the database
    $connection = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));

    // Create DB class instances
    $usersDB = new UsersDB($connection);
    $portfolioDB = new PortfolioDB($connection);

    // Fetch user info
    $sql = "SELECT firstname, lastname FROM users WHERE id = :id";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':id', $userid);
    $statement->execute();
    $user = $statement->fetch();

    // Fetch user's portfolio
    $portfolio = $portfolioDB->getPortfolioByUser($userid);

    // Calculate portfolio summary
    $recordCount = 0;
    $totalShares = 0;
    $totalValue = 0;

    if ($portfolio && count($portfolio) > 0) {
        $recordCount = count($portfolio);

        foreach ($portfolio as $item) {
            $totalShares += $item['amount'];
            $totalValue += $item['amount'] * $item['close'];
        }
    }

    $connection = null; // Close connection

} catch (Throwable $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css">
  <title>Portfolio</title>
</head>
<body>
  <?php include 'includes/header.inc.php'; ?>

  <div class="container">
    <?php
    // If the user exists
    if ($user) {
        // Display the user's full name as the page title
        echo '<h1 class="page-title">' . htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']) . '\'s Portfolio</h1>';

        // Portfolio Summary Section
        echo '<section class="card summary-card">';
        echo '<h2>Portfolio Summary</h2>';
        echo '<hr class="hr">';
        echo '<div class="summary-grid">';
        echo '<div class="stat"><h3>Companies</h3><p class="value">' . $recordCount . '</p></div>';
        echo '<div class="stat"><h3># Shares</h3><p class="value">' . number_format($totalShares) . '</p></div>';
        echo '<div class="stat"><h3>Total Value</h3><p class="value">$' . number_format($totalValue, 2) . '</p></div>';
        echo '</div>';
        echo '</section>';

        // Portfolio Details Section
        echo '<section class="card">';
        echo '<h2>Portfolio Details</h2>';
        echo '<hr class="hr">';

        // If the user owns any stocks, show them in a table
        if ($portfolio && count($portfolio) > 0) {
            echo '<table class="table">';
            echo '<thead><tr>
                    <th>Symbol</th>
                    <th>Company Name</th>
                    <th>Sector</th>
                    <th>Amount</th>
                    <th>Value</th>
                  </tr></thead>';
            echo '<tbody>';

            // Loop through each stock and display its info
            foreach ($portfolio as $item) {
                $value = $item['close'] * $item['amount']; // Calculate individual stock value

                echo '<tr>';
                // Symbol link to company page
                echo '<td><a href="company-page.php?symbol=' . urlencode($item['symbol']) . '" class="link">'
                     . htmlspecialchars($item['symbol']) . '</a></td>';
                // Company name link to company page
                echo '<td><a href="company-page.php?symbol=' . urlencode($item['symbol']) . '" class="link">'
                     . htmlspecialchars($item['name']) . '</a></td>';
                echo '<td>' . htmlspecialchars($item['sector']) . '</td>';
                echo '<td>' . number_format((float)$item['amount']) . '</td>';
                echo '<td>$' . number_format((float)$value, 2) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            // Message if user owns no stocks
            echo '<p class="muted">This user does not have any stocks in their portfolio.</p>';
        }

        echo '</section>';
    } else {
        // If the user ID was invalid or not found in the database        
        echo '<h1 class="page-title">Invalid User</h1>';
        echo '<p>No portfolio information available.</p>';
    }
    ?>
  </div>
</body>
</html>