<?php
// get_spending_data.php
// This API endpoint fetches aggregated spending data by category for the current user.

session_start(); // Start the session to access user ID

header('Content-Type: application/json'); // Indicate that the response will be JSON
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin (for development, restrict in production)
header('Access-Control-Allow-Methods: GET'); // Only GET requests are allowed for fetching data
header('Access-Control-Allow-Headers: Content-Type');

// Include your configuration and database connection files
// Adjust paths based on your actual file structure.
// Assuming this file is in 'api/' and config/dbconnection are in the parent root.
require_once '../config.php';
require_once '../dbconnection.php'; // Your PDO database connection file

$response = ['data' => [], 'error' => null];

// --- IMPORTANT: User ID Management ---
// Get userID from session. If not logged in, return an error or default to a test user.
if (!isset($_SESSION['userID'])) {
    // For testing without full login, you can temporarily hardcode a userID here:
    $userID = 1; // TEMPORARY: Replace with actual session logic in production!
    // $response['error'] = 'User not logged in.';
    // echo json_encode($response);
    // $dbh = null; // Close connection
    // exit();
} else {
    $userID = $_SESSION['userID'];
}
// -------------------------------------

try {
    // Determine the current month for filtering expenses
    $currentMonthStart = date('Y-m-01'); // First day of current month
    $currentMonthEnd = date('Y-m-t');   // Last day of current month

    // Prepare the SQL query to sum expenses by category for the current user and month
    // Join 'expenses' with 'expCatLookup' to get the category name
    $stmt = $dbh->prepare("
        SELECT 
            ecl.catName AS category_name, 
            SUM(exp.expAmount) AS total_amount
        FROM expenses exp
        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
        WHERE exp.userID = :userID
          AND exp.expDate BETWEEN :monthStart AND :monthEnd
        GROUP BY ecl.catName
        ORDER BY total_amount DESC
    ");

    // Bind parameters
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->bindParam(':monthStart', $currentMonthStart, PDO::PARAM_STR);
    $stmt->bindParam(':monthEnd', $currentMonthEnd, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Fetch all results as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If no data, return an empty array or a specific message
    if (empty($data)) {
        $response['data'] = []; // No expenses for this month
    } else {
        $response['data'] = $data;
    }

} catch (PDOException $e) {
    $response['error'] = 'Database query failed: ' . $e->getMessage();
    // Log the error for debugging
    error_log("API Error: " . $e->getMessage());
}

// Close the database connection
$dbh = null;

echo json_encode($response['data']); // Only return the data array if no error, or empty array
// If you want to return error messages in the JSON, structure it like this:
// echo json_encode($response);
?>