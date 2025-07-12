<?php
// get_expenses_data.php
// This API endpoint fetches aggregated spending data by category for the current user.

session_start(); // Start the session to access user ID

header('Content-Type: application/json'); // Indicate that the response will be JSON
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin (for development, restrict in production)
header('Access-Control-Allow-Methods: GET'); // Only GET requests are allowed for fetching data
header('Access-Control-Allow-Headers: Content-Type');

// Include your configuration and database connection files
require_once '../config.php';
require_once '../dbconnection.php'; // Your PDO database connection file

global $conn; // Access the global database handle

$response = ['data' => [], 'error' => null];

// Get userID from session. If not logged in, return an error or default to a test user.
if (!isset($_SESSION['userID'])) {
    $response['error'] = 'User not logged in.';
    echo json_encode($response);
    $conn = null; // Close connection
    exit();
} else {
    $userID = $_SESSION['userID'];
}

try {
    // Get current month's start and end dates for filtering
    $currentMonthStart = date('Y-m-01');
    $currentMonthEnd = date('Y-m-t'); // 't' gives the number of days in the given month

    // SQL to get spending by category for the logged-in user for the current month
    // Join with 'expCatLookup' to get the category name
    $stmt = $conn->prepare("
        SELECT 
            ecl.catName AS category, 
            SUM(exp.expAmount) AS value
        FROM expenses exp
        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
        WHERE exp.userID = :userID
          AND exp.expDate BETWEEN :monthStart AND :monthEnd
        GROUP BY ecl.catName
        ORDER BY value DESC
    ");

    // Bind parameters
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->bindParam(':monthStart', $currentMonthStart, PDO::PARAM_STR);
    $stmt->bindParam(':monthEnd', $currentMonthEnd, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Fetch all results as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ensure amounts are positive for the pie chart (as they are expenses)
    foreach ($data as &$row) {
        $row['value'] = abs($row['value']);
    }
    unset($row); // Break the reference

    $response['data'] = $data;

} catch (PDOException $e) {
    $response['error'] = 'Database query failed: ' . $e->getMessage();
    error_log("API Error: " . $e->getMessage(), 3, LOG_FILE_PATH);
}

echo json_encode($response);
$conn = null; // Close the database connection
exit();
?>