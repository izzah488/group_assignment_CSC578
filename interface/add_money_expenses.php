<?php
// File: public/add_money_expenses.php

session_start();

// Check if the user is logged in. If not, redirect.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include the Database connection files
require_once __DIR__ . '/../includes/config.php'; // Assuming config.php defines LOG_FILE_PATH etc.
require_once __DIR__ . '/../includes/dbconnection.php'; // Assuming dbconnection.php provides $dbh

// Get the PDO database connection instance (from dbconnection.php)
global $dbh; // Access the global database handle

$expense_error = '';
$expense_success = '';

// Handle form submission via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['id']; // Get userID from session

    $expTitle = trim($_POST['expTitle'] ?? '');
    $expAmount = floatval($_POST['expAmount'] ?? 0);
    $categoryName = trim($_POST['expenseCategory'] ?? '');
    $expDate = $_POST['expDate'] ?? '';

    // Validation
    if (!$expTitle || !$expAmount || !$categoryName || !$expDate) {
        $expense_error = 'Please fill in all fields.';
    } elseif ($expAmount <= 0) { // Amount should be positive from form, converted to negative for DB
        $expense_error = 'Amount must be positive.';
    } else {
        try {
            // First, get the category ID from the lookup table
            // Assuming expCatLookup table exists and has catName and catLookupID
            $catStmt = $dbh->prepare("SELECT catLookupID FROM expCatLookup WHERE catName = :categoryName");
            $catStmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
            $catStmt->execute();
            $catRow = $catStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($catRow) {
                $catLookupID = $catRow['catLookupID'];
                
                // Insert into 'expenses' table (as per your uploaded PHP files)
                // Note: The amount is stored as positive in the form, but for expenses,
                // it should ideally be stored as negative in the 'transactions' table
                // if you use a single 'transactions' table for both income and expenses.
                // If you have a dedicated 'expenses' table, you store it as positive.
                // Given your provided APIs use 'expAmount' and assume it's an expense,
                // I will store it as a positive value in the 'expenses' table,
                // but the D3.js chart will use abs() for visualization.
                // If you are using the 'transactions' table with negative amounts for expenses,
                // you would need to negate $expAmount here: $expAmount = -$expAmount;

                // For consistency with your provided APIs, I'll assume insertion into 'expenses' table
                // and that 'expAmount' is stored as a positive value.
                $stmt = $dbh->prepare("INSERT INTO expenses (userID, expTitle, expAmount, catLookupID, expDate) VALUES (:userID, :expTitle, :expAmount, :catLookupID, :expDate)");
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':expTitle', $expTitle, PDO::PARAM_STR);
                $stmt->bindParam(':expAmount', $expAmount, PDO::PARAM_STR); // Use STR for DECIMAL
                $stmt->bindParam(':catLookupID', $catLookupID, PDO::PARAM_INT);
                $stmt->bindParam(':expDate', $expDate, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $expense_success = 'Expense added successfully!';
                    // Clear form fields after successful submission
                    $_POST = array(); // Clear POST data to prevent re-submission on refresh
                } else {
                    $expense_error = 'Failed to add expense to the database.';
                }
            } else {
                $expense_error = 'Invalid category selected.';
            }
        } catch (PDOException $e) {
            error_log("Add Expense DB Error: " . $e->getMessage(), 3, LOG_FILE_PATH);
            $expense_error = 'A database error occurred. Please try again later.';
        }
    }
}

// Fetch categories for the dropdown
$categories = [];
try {
    $catStmt = $dbh->query("SELECT catName FROM expCatLookup ORDER BY catName");
    $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log("Error fetching categories: " . $e->getMessage(), 3, LOG_FILE_PATH);
    // Handle error, perhaps set a default category or show an error message
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Money Expenses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        .input {
            @apply w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 text-gray-800;
        }
        .btn-group {
            @apply flex justify-between space-x-4 mt-6;
        }
        .btn-group button {
            @apply flex-1 py-3 px-4 rounded-xl shadow-lg font-semibold text-lg transition-colors duration-200;
        }
        .btn-group button[type="reset"] {
            @apply bg-gray-300 text-gray-800 hover:bg-gray-400;
        }
        .btn-group button[type="submit"] {
            @apply bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700;
        }
        .back-btn {
            @apply absolute top-4 left-4 text-gray-600 hover:text-gray-900 text-3xl font-bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <button onclick="window.history.back()" class="back-btn">←</button>
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center tracking-tight">Add Money Expenses</h2>
        <?php if ($expense_error): ?>
          <div class="mb-4 text-red-600 text-center font-semibold"><?= htmlspecialchars($expense_error) ?></div>
        <?php elseif ($expense_success): ?>
          <div class="mb-4 text-green-600 text-center font-semibold"><?= $expense_success ?></div>
        <?php endif; ?>
        <form id="addExpenseForm" method="POST">
          <input type="text" id="expTitle" name="expTitle" placeholder="Title" class="input" value="<?= htmlspecialchars($_POST['expTitle'] ?? '') ?>" required>
          <input type="number" id="expAmount" name="expAmount" placeholder="RM" class="input" step="0.01" value="<?= htmlspecialchars($_POST['expAmount'] ?? '') ?>" required>
          <select id="expenseCategory" name="expenseCategory" class="input" required>
            <option value="" disabled selected>Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($_POST['expenseCategory']) && $_POST['expenseCategory'] == $cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
          </select>
          <input type="date" id="expDate" name="expDate" class="input" value="<?= htmlspecialchars($_POST['expDate'] ?? '') ?>" required>
          <div class="btn-group">
            <button type="reset">CANCEL</button>
            <button type="submit">ADD</button>
          </div>
        </form>
    </div>
</body>
</html>
