<?php
require_once __DIR__ . '/includes/config.inc.php';
require_once __DIR__ . '/db-classes.php';

// Get the "symbol" value from the query string (e.g., ?symbol=aapl)
if (isset($_GET['symbol'])) {
    $symbol = trim($_GET['symbol']);
} else {
    $symbol = '';
}

try {
    // Connect to database
    $connection = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));

    // Create objects for your DB classes
    $companiesDB = new CompaniesDB($connection);
    $historyDB = new HistoryDB($connection);

    // Retrieve data using the new methods
    $company = $companiesDB->getCompanyBySymbol($symbol);
    $history = $historyDB->getHistoryBySymbol($symbol);

    // Compute summary statistics based on the history data.
    $summary = [
        'high' => null,
        'low' => null,
        'totalVolume' => 0,
        'avgVolume' => 0
    ];

    // Only calculate these values if the company has any price history.
    if ($history && count($history) > 0) {
        $highs = array_column($history, 'high');
        $lows = array_column($history, 'low');
        $volumes = array_column($history, 'volume');

        // Calculate summary statistics.
        $summary['high'] = max($highs);
        $summary['low'] = min($lows);
        $summary['totalVolume'] = array_sum($volumes);
        $summary['avgVolume'] = $summary['totalVolume'] / count($volumes);
    }

    // Close connection
    $connection = null;

} catch (Throwable $e) {
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo $company['name']; ?>
    (<?php echo $company['symbol']; ?>)
  </title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include '/includes/header.inc.php'; ?>
  <div class="container">
    <h1 class="page-title">
      <?php echo $company['name']; ?>
      (<?php echo $company['symbol']; ?>)
    </h1>

    <?php
    if (!empty($company['description'])) {
        echo '<p class="page-sub">' . $company['description'] . '</p>';
    }
    ?>

    <div class="stack">

      <!-- Details Card -->
      <section class="card">
        <h2>Details</h2>
        <hr class="hr">
        <div class="kv">
          <div>Sector</div><div><?php echo $company['sector']; ?></div>
          <div>Subindustry</div><div><?php echo $company['subindustry']; ?></div>
          <div>Exchange</div><div><?php echo $company['exchange']; ?></div>
          <div>Address</div><div><?php echo $company['address']; ?></div>
          <div>Website</div>
          <div>
            <?php
            if (!empty($company['website'])) {
                echo '<a class="link" href="' . $company['website'] .
                     '" target="_blank" rel="noopener">' .
                     $company['website'] . '</a>';
            }
            ?>
          </div>
          <div>Latitude</div><div><?php echo $company['latitude']; ?></div>
          <div>Longitude</div><div><?php echo $company['longitude']; ?></div>
        </div>
      </section>

      <!-- Financials Card -->
      <section class="card">
        <h2>Financials</h2>
        <hr class="hr">
        <?php
        if (!$fin || $finLen === 0) {
            echo '<p class="muted">No financials available.</p>';
        } else {
            echo '<table class="table">
                    <thead>
                      <tr>
                        <th>Year</th><th>Revenue</th><th>Earnings</th><th>Assets</th><th>Liabilities</th>
                      </tr>
                    </thead>
                    <tbody>';
            for ($i = 0; $i < $finLen; $i++) {
                echo '<tr>';
                echo '<td>' . $fin['years'][$i] . '</td>';
                echo '<td>' . number_format((float)$fin['revenue'][$i]) . '</td>';
                echo '<td>' . number_format((float)$fin['earnings'][$i]) . '</td>';
                echo '<td>' . number_format((float)$fin['assets'][$i]) . '</td>';
                echo '<td>' . number_format((float)$fin['liabilities'][$i]) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        ?>
      </section>

      <!-- Summary Stats Card -->
      <?php
      if ($history && count($history) > 0) {
          echo '<section class="card summary-card">
                  <h2>Summary Statistics</h2>
                  <hr class="hr">
                  <div class="summary-grid">
                    <div class="stat"><h3>Highest Price</h3><p class="value">$' .
                        number_format((float)$summary['high'], 2) . '</p></div>
                    <div class="stat"><h3>Lowest Price</h3><p class="value">$' .
                        number_format((float)$summary['low'], 2) . '</p></div>
                    <div class="stat"><h3>Total Volume</h3><p class="value">' .
                        number_format((float)$summary['totalVolume']) . '</p></div>
                    <div class="stat"><h3>Average Volume</h3><p class="value">' .
                        number_format((float)$summary['avgVolume']) . '</p></div>
                  </div>
                </section>';
      }
      ?>

      <!-- History Card -->
      <section class="card">
        <h2>Price History (Descending by date)</h2>
        <hr class="hr">
        <?php
        if (!$history) {
            echo '<p class="muted">No history rows found.</p>';
        } else {
            echo '<table class="table">
                    <thead>
                      <tr>
                        <th>Date</th><th>Open</th><th>Close</th><th>High</th><th>Low</th><th>Volume</th>
                      </tr>
                    </thead>
                    <tbody>';
            foreach ($history as $row) {
                echo '<tr>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . number_format((float)$row['open'], 2) . '</td>';
                echo '<td>' . number_format((float)$row['close'], 2) . '</td>';
                echo '<td>' . number_format((float)$row['high'], 2) . '</td>';
                echo '<td>' . number_format((float)$row['low'], 2) . '</td>';
                echo '<td>' . number_format((float)$row['volume']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        ?>
      </section>
    </div>
  </div>
</body>
</html>
