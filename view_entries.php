<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Entries</title>
    <!-- Link to Bootstrap CSS via CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Additional CSS or custom stylesheets -->
    <!-- Include any other necessary meta tags or CSS files here -->
</head>
<body>

<?php

// Include database connection
require_once 'config/db_connection.php';

// Check if entry ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Entry ID is missing.";
    exit();
}

// Retrieve form data for the specified entry ID
$entryId = $_GET['id'];
$sql = "SELECT form_data FROM forms WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $entryId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Entry not found.";
    exit();
}

$row = $result->fetch_assoc();
$formData = json_decode($row['form_data'], true);

// Generate presentable form using Bootstrap
echo "<div class='container'>";
echo "<h2>Submitted Form Data</h2>";
echo "<form>";

foreach ($formData['fields'] as $field) {
    echo "<div class='form-group'>";
    echo "<label class='col-form-label'>" . ucfirst($field['name']) . "</label>";

    if ($field['type'] === 'textarea') {
        echo "<textarea class='form-control' readonly>" . ($field['value'] ?? '') . "</textarea>";
    } elseif ($field['type'] === 'select') {
        echo "<select class='form-control' readonly>";
        foreach ($field['options'] as $option) {
            echo "<option" . ($option === ($field['value'] ?? '') ? " selected" : "") . ">" . $option . "</option>";
        }
        echo "</select>";
    } elseif ($field['type'] === 'radio') {
        // Display radio options
        foreach ($field['options'] as $option) {
            echo "<div class='form-check'>";
            echo "<input class='form-check-input' type='radio' name='" . $field['name'] . "' value='" . $option . "' ";
            echo ($option === ($field['value'] ?? '') ? "checked" : "") . ">";
            echo "<label class='form-check-label'>" . $option . "</label>";
            echo "</div>";
        }
    } else {
        echo "<input type='" . $field['type'] . "' class='form-control' value='" . ($field['value'] ?? '') . "' readonly>";
    }

    echo "</div>";
}


$stmt->close();
?>

</body>
</html>