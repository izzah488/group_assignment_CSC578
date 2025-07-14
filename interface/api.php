<?php
// api.php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['userID'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
    exit();
}

// Include your database connection and configuration
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../dbconnection.php'; // This should make $pdo available

// Set content type to JSON
header('Content-Type: application/json');

$userID = $_SESSION['userID']; // Get userID from session

// Determine the action based on GET parameter
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_monthly_budget':
        try {
            $currentMonth = date('Y-m-01'); // First day of the current month
            $stmt = $pdo->prepare("SELECT totBudget, budgetDate FROM budget WHERE userID = :userID AND budgetDate = :currentMonth");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':currentMonth', $currentMonth, PDO::PARAM_STR);
            $stmt->execute();
            $budgetData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($budgetData) {
                echo json_encode([
                    'success' => true,
                    'monthlyBudget' => $budgetData['totBudget'],
                    'budgetStartDate' => $budgetData['budgetDate']
                ]);
            } else {
                echo json_encode(['success' => true, 'monthlyBudget' => 0, 'budgetStartDate' => null, 'message' => 'No budget set for this month.']);
            }
        } catch (PDOException $e) {
            error_log("Error fetching monthly budget: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error fetching monthly budget.']);
        }
        break;

    case 'get_total_expenses_current_month':
        try {
            $currentMonthStart = date('Y-m-01');
            $currentMonthEnd = date('Y-m-t'); // Last day of the current month

            $stmt = $pdo->prepare("SELECT SUM(expAmount) AS total_expenses FROM expenses WHERE userID = :userID AND expDate BETWEEN :monthStart AND :monthEnd");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':monthStart', $currentMonthStart, PDO::PARAM_STR);
            $stmt->bindParam(':monthEnd', $currentMonthEnd, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalExpenses = $result['total_expenses'] ?? 0; // Default to 0 if NULL

            echo json_encode(['success' => true, 'totalExpenses' => $totalExpenses]);
        } catch (PDOException $e) {
            error_log("Error fetching total expenses: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error fetching total expenses.']);
        }
        break;

    case 'get_recent_expenses':
        try {
            // Fetch the last 2 expenses as requested
            $stmt = $pdo->prepare("SELECT expTitle AS description, expAmount AS amount, expDate AS date FROM expenses WHERE userID = :userID ORDER BY expCreatdAt DESC LIMIT 2");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $recentExpenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'recentExpenses' => $recentExpenses]);
        } catch (PDOException $e) {
            error_log("Error fetching recent expenses: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error fetching recent expenses.']);
        }
        break;

    case 'get_monthly_expenses_by_category':
        try {
            // Get current month and year
            $currentMonthYear = date('Y-m'); // e.g., '2025-07'

            // For debugging: Log the current month/year being queried
            error_log("DEBUG: Querying expenses for month/year: " . $currentMonthYear);

            $stmt = $pdo->prepare("
                SELECT
                    ec.catName AS category_name,
                    SUM(e.expAmount) AS total_amount
                FROM
                    expenses e
                JOIN
                    expCatLookup ec ON e.catLookupID = ec.catLookupID
                WHERE
                    e.userID = :userID AND DATE_FORMAT(e.expDate, '%Y-%m') = :currentMonthYear
                GROUP BY
                    ec.catName
            ");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':currentMonthYear', $currentMonthYear, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // For debugging: Log the raw results fetched from the database
            error_log("DEBUG: Raw results for expenses by category: " . print_r($results, true));

            $expensesByCategory = [];
            foreach ($results as $row) {
                $expensesByCategory[$row['category_name']] = floatval($row['total_amount']);
            }

            // For debugging: Log the final structured data before sending
            error_log("DEBUG: Final expenses by category data: " . print_r($expensesByCategory, true));


            echo json_encode(['success' => true, 'expensesByCategory' => $expensesByCategory]);
        } catch (PDOException $e) {
            error_log("Error fetching expenses by category: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error fetching expenses by category.']);
        }
        break;

    case 'get_total_savings':
        try {
            // SQL to get the count of all saving goals for the logged-in user
            $stmt = $pdo->prepare("
                SELECT
                    COUNT(savingID) AS totalGoalsCount
                FROM
                    savingGoals
                WHERE
                    userID = :userID
            ");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalGoalsCount = $result['totalGoalsCount'] ?? 0; // Default to 0 if NULL

            echo json_encode(['success' => true, 'totalGoalsCount' => $totalGoalsCount]);
        } catch (PDOException $e) {
            error_log("Error fetching total savings goals count: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error fetching total savings goals count.']);
        }
        break;

    // Add other API actions here as needed (e.g., for adding/editing data)
    // ...

    default:
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid API action.']);
        break;
}
?>