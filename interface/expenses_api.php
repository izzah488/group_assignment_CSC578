<?php
// expenses_api.php
// This unified API endpoint handles all CRUD operations for expenses.

session_start();
header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *'); // Allow all origins for development (restrict in production)
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allow all necessary methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow necessary headers

// Handle preflight OPTIONS request (for CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include configuration and database connection
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../dbconnection.php';

global $pdo; // Access the global PDO database handle

$response = ['success' => false, 'message' => ''];

// Check if user is logged in for all operations except preflight
if (!isset($_SESSION['userID'])) {
    $response['message'] = 'User not authenticated.';
    http_response_code(401); // Unauthorized
    echo json_encode($response);
    exit();
}

$userID = $_SESSION['userID']; // Get the logged-in user's ID

// Get the HTTP method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null; // For GET requests, action can be in query params

// For POST, PUT, DELETE, read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            if ($action === 'get_all_expenses') {
                // Fetch all expenses for the current user
                $sql = "SELECT expenseID, expTitle, expAmount, ecl.catName AS category_name, expDate
                        FROM expenses exp
                        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
                        WHERE exp.userID = :userID
                        ORDER BY expDate DESC, expenseID DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->execute();
                $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Ensure amounts are positive for display
                foreach ($expenses as &$exp) {
                    $exp['expAmount'] = abs($exp['expAmount']);
                }
                unset($exp);

                $response['success'] = true;
                $response['data'] = $expenses;
                $response['message'] = 'Expenses fetched successfully.';

            } elseif ($action === 'get_expense_details' && isset($_GET['expenseID'])) {
                // Fetch details for a specific expense
                $expenseID = $_GET['expenseID'];
                $sql = "SELECT expenseID, expTitle, expAmount, ecl.catName AS category_name, ecl.catLookupID, expDate
                        FROM expenses exp
                        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
                        WHERE exp.userID = :userID AND exp.expenseID = :expenseID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
                $stmt->execute();
                $expense = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($expense) {
                    $expense['expAmount'] = abs($expense['expAmount']); // Ensure positive for display
                    $response['success'] = true;
                    $response['data'] = $expense;
                    $response['message'] = 'Expense details fetched successfully.';
                } else {
                    $response['message'] = 'Expense not found or not authorized.';
                    http_response_code(404); // Not Found
                }

            } elseif ($action === 'get_chart_data') {
                // Fetch aggregated spending data by category for the current month
                $currentMonthStart = date('Y-m-01');
                $currentMonthEnd = date('Y-m-t');

                $sql = "SELECT ecl.catName AS category_name, SUM(exp.expAmount) AS total_amount
                        FROM expenses exp
                        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
                        WHERE exp.userID = :userID
                          AND exp.expDate BETWEEN :monthStart AND :monthEnd
                        GROUP BY ecl.catName
                        ORDER BY total_amount DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':monthStart', $currentMonthStart, PDO::PARAM_STR);
                $stmt->bindParam(':monthEnd', $currentMonthEnd, PDO::PARAM_STR);
                $stmt->execute();
                $chartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Ensure amounts are positive for pie chart display
                foreach ($chartData as &$row) {
                    $row['total_amount'] = abs($row['total_amount']);
                }
                unset($row);

                $response['success'] = true;
                $response['data'] = $chartData;
                $response['message'] = 'Chart data fetched successfully.';

            } elseif ($action === 'get_categories') {
                // Fetch all available expense categories
                $sql = "SELECT catLookupID, catName FROM expCatLookup ORDER BY catName";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $response['success'] = true;
                $response['data'] = $categories;
                $response['message'] = 'Categories fetched successfully.';

            } else {
                $response['message'] = 'Invalid GET action or missing parameters.';
                http_response_code(400); // Bad Request
            }
            break;

        case 'POST': // For adding new expenses
            $expTitle = trim($input['expTitle'] ?? '');
            $expAmount = $input['expAmount'] ?? null;
            $catLookupID = $input['catLookupID'] ?? null;
            $expDate = $input['expDate'] ?? null;

            if (empty($expTitle) || !is_numeric($expAmount) || $expAmount <= 0 || empty($catLookupID) || !is_numeric($catLookupID) || empty($expDate)) {
                $response['message'] = 'Invalid input data for adding expense.';
                http_response_code(400);
                break;
            }

            // You might want to ensure the catLookupID actually belongs to a valid category
            // $stmt_check_cat = $pdo->prepare("SELECT catLookupID FROM expCatLookup WHERE catLookupID = :catLookupID");
            // $stmt_check_cat->bindParam(':catLookupID', $catLookupID, PDO::PARAM_INT);
            // $stmt_check_cat->execute();
            // if (!$stmt_check_cat->fetch()) { /* error */ }

            $sql = "INSERT INTO expenses (userID, expTitle, expAmount, catLookupID, expDate)
                    VALUES (:userID, :expTitle, :expAmount, :catLookupID, :expDate)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':expTitle', $expTitle, PDO::PARAM_STR);
            $stmt->bindParam(':expAmount', $expAmount, PDO::PARAM_STR); // Store as positive, JS handles abs() for display
            $stmt->bindParam(':catLookupID', $catLookupID, PDO::PARAM_INT);
            $stmt->bindParam(':expDate', $expDate, PDO::PARAM_STR);
            $stmt->execute();

            $response['success'] = true;
            $response['message'] = 'Expense added successfully!';
            http_response_code(201); // Created
            break;

        case 'PUT': // For updating existing expenses
            $expenseID = $input['expenseID'] ?? null;
            $expTitle = trim($input['expTitle'] ?? '');
            $expAmount = $input['expAmount'] ?? null;
            $catLookupID = $input['catLookupID'] ?? null;
            $expDate = $input['expDate'] ?? null;

            if (empty($expenseID) || !is_numeric($expenseID) || empty($expTitle) || !is_numeric($expAmount) || $expAmount <= 0 || empty($catLookupID) || !is_numeric($catLookupID) || empty($expDate)) {
                $response['message'] = 'Invalid input data for updating expense.';
                http_response_code(400);
                break;
            }

            $sql = "UPDATE expenses
                    SET expTitle = :expTitle, expAmount = :expAmount, catLookupID = :catLookupID, expDate = :expDate
                    WHERE expenseID = :expenseID AND userID = :userID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':expTitle', $expTitle, PDO::PARAM_STR);
            $stmt->bindParam(':expAmount', $expAmount, PDO::PARAM_STR);
            $stmt->bindParam(':catLookupID', $catLookupID, PDO::PARAM_INT);
            $stmt->bindParam(':expDate', $expDate, PDO::PARAM_STR);
            $stmt->bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Expense updated successfully.';
                } else {
                    $response['message'] = 'Expense not found or not authorized to update.';
                    http_response_code(404);
                }
            } else {
                $response['message'] = 'Failed to update expense.';
                http_response_code(500);
            }
            break;

        case 'DELETE': // For deleting expenses
            $expenseID = $_GET['expenseID'] ?? null; // DELETE typically sends ID in query string
            if (empty($expenseID) || !is_numeric($expenseID)) {
                $response['message'] = 'Invalid expense ID for deletion.';
                http_response_code(400);
                break;
            }

            $sql = "DELETE FROM expenses WHERE expenseID = :expenseID AND userID = :userID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Expense deleted successfully.';
                } else {
                    $response['message'] = 'Expense not found or not authorized to delete.';
                    http_response_code(404);
                }
            } else {
                $response['message'] = 'Failed to delete expense.';
                http_response_code(500);
            }
            break;

        default:
            $response['message'] = 'Method not allowed.';
            http_response_code(405); // Method Not Allowed
            break;
    }

} catch (PDOException $e) {
    error_log("Expenses API DB Error ($method): " . $e->getMessage(), 3, LOG_FILE_PATH);
    $response['message'] = 'Database error: ' . $e->getMessage();
    http_response_code(500); // Internal Server Error
}

echo json_encode($response);
exit();
?>
