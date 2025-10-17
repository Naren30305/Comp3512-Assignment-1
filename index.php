<?php
require_once 'includes/config.inc.php';
require_once 'db-classes.php';

try {
    // Connect to the database using your helper class
    $connection = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));

    // Create an instance of UsersDB class
    $usersDB = new UsersDB($connection);

    // Fetch all users sorted by last name
    $users = $usersDB->getAllUsers();

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
    <link rel="stylesheet" href="css/styles.css">
    <title>Home Page</title>
</head>
<body>
    <?php include  'includes/header.inc.php'; ?>  
    <div class="container">
    <h1 class="page-title">Customer List</h1>
    <p class="page-sub">Select a customer to view their portfolio.</p>

    <?php
    // Check if any users were found
    if ($users && count($users) > 0) {
        echo '<section class="card">';
        echo '<table class="table">';
        echo '<thead><tr>
                <th>Last Name</th>
                <th>First Name</th>
                <th>City</th>
                <th>Country</th>
                <th>Email</th>
                <th>Portfolio</th>
              </tr></thead>';
        echo '<tbody>';
    
        foreach ($users as $user) { 
            echo '<tr>';
            echo '<td>' . htmlspecialchars($user['lastname']) . '</td>';
            echo '<td>' . htmlspecialchars($user['firstname']) . '</td>';
            echo '<td>' . htmlspecialchars($user['city']) . '</td>';
            echo '<td>' . htmlspecialchars($user['country']) . '</td>';
            echo '<td>' . htmlspecialchars($user['email']) . '</td>';
    
            // 🔗 Add a Portfolio button that links to portfolio.php
            echo '<td>
                    <a class="portfolio-btn" href="portfolio.php?userid=' . urlencode($user['id']) . '">
                        View Portfolio
                    </a>
                  </td>';
    
            echo '</tr>';
        }
    
        echo '</tbody>';
        echo '</table>';
        echo '</section>';
    } else { 
        echo '<p>No customers found in database.</p>';
    }

    ?>

    </div>
</body>
</html>