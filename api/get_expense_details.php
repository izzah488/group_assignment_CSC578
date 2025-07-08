<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';
require_once '../dbconnection.php';

$response = ['data' => null, 'error' => null];

if (!isset($_SESSION['userID'])) {
    $userID = 1; // TEMPORARY
    // $response['error'] = 'User not logged in.';
    // echo json_encode($response); $dbh = null; exit();
} else {
    $userID = $_SESSION['userID'];
}

$expenseID = $_GET['expenseID'] ?? null;

if (empty($expenseID) || !is_numeric($expenseID)) {
    $response['error'] = 'Invalid expense ID.';
    echo json_encode($response);
    $dbh = null; exit();
}

try {
    $stmt = $dbh->prepare("
        SELECT 
            exp.expenseID, 
            exp.expTitle, 
            exp.expAmount, 
            exp.expDate, 
            ecl.catName AS category_name,
            ecl.catLookupID
        FROM expenses exp
        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
        WHERE exp.expenseID = :expenseID AND exp.userID = :userID
    ");
    $stmt->bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($expense) {
        $response['data'] = $expense;
    } else {
        $response['error'] = 'Expense not found or not authorized.';
    }

} catch (PDOException $e) {
    $response['error'] = 'Database query failed: ' . $e->getMessage();
    error_log("API Error: get_expense_details.php - " . $e->getMessage());
}

$dbh = null;
echo json_encode($response['data'] ?: ['error' => $response['error']]); // Return data or error
?>