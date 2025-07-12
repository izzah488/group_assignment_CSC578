<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';
require_once '../dbconnection.php';

global $conn; // Access the global database handle

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['userID'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response); $conn = null; exit();
} else {
    $userID = $_SESSION['userID'];
}

$data = $_POST; // Using $_POST directly as the form sends FormData
$expenseID = $data['expenseID'] ?? null;

if (empty($expenseID) || !is_numeric($expenseID)) {
    $response['message'] = 'Invalid expense ID.';
    echo json_encode($response);
    $conn = null; exit();
}

try {
    $stmt = $conn->prepare("DELETE FROM expenses WHERE expenseID = :expenseID AND userID = :userID");
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
    error_log("Delete Expense DB Error: " . $e->getMessage(), 3, LOG_FILE_PATH);
}

echo json_encode($response);
$conn = null; exit();
?>