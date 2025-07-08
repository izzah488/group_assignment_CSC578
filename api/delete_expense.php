<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';
require_once '../dbconnection.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['userID'])) {
    $userID = 1; // TEMPORARY
    // $response['message'] = 'User not logged in.';
    // echo json_encode($response); $dbh = null; exit();
} else {
    $userID = $_SESSION['userID'];
}

$data = json_decode(file_get_contents('php://input'), true);
$expenseID = $data['expenseID'] ?? null;

if (empty($expenseID) || !is_numeric($expenseID)) {
    $response['message'] = 'Invalid expense ID.';
    echo json_encode($response);
    $dbh = null; exit();
}

try {
    $stmt = $dbh->prepare("DELETE FROM expenses WHERE expenseID = :expenseID AND userID = :userID");
    $stmt->bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) { // Check if any row was actually deleted
            $response['success'] = true;
            $response['message'] = 'Expense deleted successfully.';
        } else {
            $response['message'] = 'Expense not found or not authorized to delete.';
        }
    } else {
        $response['message'] = 'Failed to delete expense.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    error_log("API Error: delete_expense.php - " . $e->getMessage());
}

$dbh = null;
echo json_encode($response);
?>