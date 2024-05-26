<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Submitted Entries</title>
    <!-- Link to Bootstrap CSS via CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Additional CSS for custom styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <?php

    // Include database connection
    require_once 'config/db_connection.php';

    // Fetch form entries from the database
    $sql = "SELECT * FROM forms ORDER BY created_at DESC";
    $result = $db->query($sql);

    // Display the list of entries
    if ($result->num_rows > 0) {
        echo "<h2>List of Submitted Entries</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='view_entries.php?id=" . $row['id'] . "'>Entry ID: " . $row['id'] . "</a> - Created At: " . $row['created_at'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No entries found.";
    }
    ?>
</div>

</body>
</html>
