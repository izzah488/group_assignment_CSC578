<?php
// expenses_api.php
// This unified API endpoint handles all CRUD operations for expenses.

// Temporary: Enable error display for debugging (REMOVE IN PRODUCTION)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$action = $_GET['action'] ?? null; // For GET, PUT, DELETE, action is in query param

// For POST and PUT, get data from the request body
$input = [];
if (in_array($method, ['POST', 'PUT'])) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = 'Invalid JSON input.';
        http_response_code(400);
        echo json_encode($response);
        exit();
    }
}

// CSRF Token Validation for non-GET requests
// For DELETE, the token might come in query params, for POST/PUT in body.
$csrf_token = $_SESSION['csrf_token'] ?? '';
$request_csrf_token = '';

if ($method === 'DELETE' || ($method === 'GET' && $action === 'get_category_id')) { // GET for get_category_id might have it
    $request_csrf_token = $_GET['csrf_token'] ?? '';
} else { // POST, PUT, and other GET actions (where token might be in body or not required)
    $request_csrf_token = $input['csrf_token'] ?? '';
}

// --- Debugging Logs for CSRF ---
error_log("API Call: Method=" . $method . ", Action=" . ($action ?? 'N/A'));
error_log("Session CSRF Token: " . ($csrf_token === '' ? 'NOT SET' : $csrf_token));
error_log("Request CSRF Token (from GET): " . ($_GET['csrf_token'] ?? 'NOT SET'));
error_log("Request CSRF Token (from Input): " . ($input['csrf_token'] ?? 'NOT SET'));
// --- End Debugging Logs ---


// Skip CSRF check for 'get_category_id', 'get_all_expenses', 'get_chart_data', 'get_budget' as they are read operations
if (!in_array($action, ['get_category_id', 'get_all_expenses', 'get_chart_data', 'get_budget'])) {
    if ($csrf_token === '' || $request_csrf_token === '' || $request_csrf_token !== $csrf_token) {
        // --- Debugging Log for Failed CSRF ---
        error_log("CSRF Validation Failed: Session Token=" . ($csrf_token === '' ? 'EMPTY' : $csrf_token) . ", Request Token=" . ($request_csrf_token === '' ? 'EMPTY' : $request_csrf_token));
        // --- End Debugging Log ---
        $response['message'] = 'CSRF token validation failed.';
        http_response_code(403); // Forbidden
        echo json_encode($response);
        exit();
    }
}


try {
    switch ($method) {
        case 'GET':
            // Explicitly check for missing action parameter for GET requests
            if (empty($action)) {
                $response['message'] = 'Missing action parameter for GET request.';
                http_response_code(400); // Bad Request
                break; // Exit switch case
            }

            if ($action === 'get_all_expenses') {
                // Fetch all expense transactions for the logged-in user
                // Ordered by date descending for "Recent Expenses" feel
                $sql = "SELECT expenseID AS id, expTitle AS title, expAmount AS amount, ecl.catName AS category, expDate AS transaction_date
                        FROM expenses exp
                        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
                        WHERE exp.userID = :userID
                        ORDER BY exp.expDate DESC, exp.expenseID DESC";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->execute();
                $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Format data: Convert amount to positive for display as expenses
                $formatted_expenses = [];
                foreach ($expenses as $expense) {
                    $expense['amount'] = abs($expense['amount']); // Convert to positive for display
                    $formatted_expenses[] = $expense;
                }

                $response['success'] = true;
                $response['data'] = $formatted_expenses;

            } elseif ($action === 'get_chart_data') {
                // Get current month's start and end dates for filtering
                $currentMonthStart = date('Y-m-01');
                $currentMonthEnd = date('Y-m-t'); // 't' gives the number of days in the given month

                // SQL to get spending by category for the logged-in user for the current month
                $stmt = $pdo->prepare("
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

                $response['success'] = true;
                $response['data'] = $data;

            } elseif ($action === 'get_category_id') {
                $categoryName = $_GET['categoryName'] ?? null;
                if (empty($categoryName)) {
                    $response['message'] = 'Category name is required for get_category_id action.';
                    http_response_code(400);
                    break;
                }

                $stmt = $pdo->prepare("SELECT catLookupID FROM expCatLookup WHERE catName = :categoryName");
                $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
                $stmt->execute();
                $catRow = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($catRow) {
                    $response['success'] = true;
                    $response['catLookupID'] = $catRow['catLookupID'];
                } else {
                    $response['message'] = 'Category not found.';
                    http_response_code(404);
                }
            } elseif ($action === 'get_expense_details') {
                $expenseID = $_GET['expenseID'] ?? null;
                if (empty($expenseID) || !is_numeric($expenseID)) {
                    $response['message'] = 'Invalid expense ID provided for details.';
                    http_response_code(400);
                    break;
                }

                $sql = "SELECT expenseID AS id, expTitle AS title, expAmount AS amount, ecl.catName AS category, expDate AS transaction_date, exp.catLookupID
                        FROM expenses exp
                        JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
                        WHERE exp.expenseID = :expenseID AND exp.userID = :userID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->execute();
                $expense = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($expense) {
                    $expense['amount'] = abs($expense['amount']); // Convert to positive for display
                    $response['success'] = true;
                    $response['data'] = $expense;
                } else {
                    $response['message'] = 'Expense details not found or not authorized.';
                    http_response_code(404);
                }
            } elseif ($action === 'get_budget') { // NEW: Action to fetch budget
                // Get the current month's first day for filtering
                $currentMonthStart = date('Y-m-01');

                $sql = "SELECT budgetID, userID, budgetDate, totBudget
                        FROM budget
                        WHERE userID = :userID AND budgetDate = :budgetDate
                        ORDER BY budgetDate DESC
                        LIMIT 1"; // Get the latest budget for the current month

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':budgetDate', $currentMonthStart, PDO::PARAM_STR);
                $stmt->execute();
                $budget = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($budget) {
                    $response['success'] = true;
                    $response['data'] = $budget;
                } else {
                    $response['message'] = 'No budget found for the current month.';
                    // It's okay if no budget is found, just return success: true with null data
                    $response['success'] = true;
                    $response['data'] = null;
                }
            } else {
                $response['message'] = 'Invalid GET action provided.'; // Fallback for unrecognized actions
                http_response_code(400);
            }
            break;

        case 'POST': // Add new expense
            $expTitle = $input['expTitle'] ?? null;
            $expAmount = $input['expAmount'] ?? null;
            $catLookupID = $input['catLookupID'] ?? null;
            $expDate = $input['expDate'] ?? null;

            // Validation
            if (empty($expTitle) || empty($expAmount) || !is_numeric($expAmount) || $expAmount <= 0 || empty($catLookupID) || !is_numeric($catLookupID) || empty($expDate)) {
                $response['message'] = 'Invalid input data for adding expense.';
                http_response_code(400);
                break;
            }

            // Amounts are stored as negative for expenses in the database
            $expAmount = -abs($expAmount);

            $stmt = $pdo->prepare("INSERT INTO expenses (userID, expTitle, expAmount, catLookupID, expDate) VALUES (:userID, :expTitle, :expAmount, :catLookupID, :expDate)");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':expTitle', $expTitle, PDO::PARAM_STR);
            $stmt->bindParam(':expAmount', $expAmount, PDO::PARAM_STR); // Use STR for DECIMAL
            $stmt->bindParam(':catLookupID', $catLookupID, PDO::PARAM_INT);
            $stmt->bindParam(':expDate', $expDate, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Expense added successfully.';
                http_response_code(201); // Created
            } else {
                $response['message'] = 'Failed to add expense.';
                http_response_code(500);
            }
            break;

        case 'PUT': // Update existing expense
            $expenseID = $input['expenseID'] ?? null;
            $expTitle = $input['expTitle'] ?? null;
            $expAmount = $input['expAmount'] ?? null;
            $catLookupID = $input['catLookupID'] ?? null;
            $expDate = $input['expDate'] ?? null;

            // Validation
            if (empty($expenseID) || !is_numeric($expenseID) || empty($expTitle) || empty($expAmount) || !is_numeric($expAmount) || $expAmount <= 0 || empty($catLookupID) || !is_numeric($catLookupID) || empty($expDate)) {
                $response['message'] = 'Invalid input data for updating expense.';
                http_response_code(400);
                break;
            }

            // Amounts are stored as negative for expenses in the database
            $expAmount = -abs($expAmount);

            $stmt = $pdo->prepare("
                UPDATE expenses
                SET
                    expTitle = :expTitle,
                    expAmount = :expAmount,
                    catLookupID = :catLookupID,
                    expDate = :expDate
                WHERE expenseID = :expenseID AND userID = :userID
            ");

            $stmt->bindParam(':expTitle', $expTitle, PDO::PARAM_STR);
            $stmt->bindParam(':expAmount', $expAmount, PDO::PARAM_STR); // Use STR for DECIMAL
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

        case 'DELETE':
            $expenseID = $_GET['expenseID'] ?? null; // For DELETE, ID comes from query param
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
} finally {
    // Ensure the connection is closed if necessary (though PDO usually handles this on script end)
    $pdo = null;
    echo json_encode($response);
}
