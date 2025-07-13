<?php
// File: public/add_money_expenses.php

session_start();

// Check if the user is logged in. If not, redirect.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include the Database connection files
require_once __DIR__ . '/../config.php'; // Assuming config.php defines LOG_FILE_PATH etc.
require_once __DIR__ . '/../dbconnection.php'; // Assuming dbconnection.php provides $conn

// Get the PDO database connection instance (from dbconnection.php)
global $conn; // Access the global database handle

$expense_error = '';
$expense_success = '';

// Handle form submission via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['userID']; // Get userID from session

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
$catStmt = $pdo->prepare("SELECT catLookupID FROM expCatLookup WHERE catName = :categoryName");
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
$stmt = $pdo->prepare("INSERT INTO expenses (userID, expTitle, expAmount, catLookupID, expDate) VALUES (:userID, :expTitle, :expAmount, :catLookupID, :expDate)");
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
    $catStmt = $pdo->query("SELECT catName FROM expCatLookup ORDER BY catName");
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
            background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%); /* Matching the example's body background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden; /* Added from example */
        }
        .form-card { /* Renamed from .container to .form-card as per example */
            background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%); /* Matching the example's form-card background */
            padding: 2.5rem;
            border-radius: 2rem; /* Adjusted for larger border-radius */
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08); /* Matching example's shadow */
            width: 100%;
            max-width: 400px; /* Adjusted max-width */
            position: relative;
        }
        .input {
            width: 100%;
            padding: 0.9rem 1.2rem; /* Adjusted padding */
            border-radius: 0.75rem; /* Adjusted border-radius */
            border: 1px solid #e2e8f0; /* Adjusted border */
            background-color: #f8fafc; /* Adjusted background */
            margin-bottom: 1rem;
            font-size: 1rem;
            color: #334155;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input:focus {
            outline: none;
            border-color: #a259ff; /* Focus border color from example */
            box-shadow: 0 0 0 2px rgba(162, 89, 255, 0.2); /* Focus shadow from example */
        }
        .btn-group {
            display: flex; /* Changed from @apply flex */
            justify-content: space-between;
            gap: 1rem; /* Replaced space-x-4 with gap for modern flexbox */
            margin-top: 1.5rem; /* Adjusted margin-top */
        }
        .btn-group button {
            flex: 1; /* Changed from @apply flex-1 */
            padding: 1rem 0; /* Adjusted padding for buttons */
            border-radius: 1.2rem; /* Adjusted border-radius for buttons */
            font-weight: 700; /* Adjusted font-weight */
            font-size: 1.15rem; /* Adjusted font-size */
            box-shadow: 0 2px 12px 0 rgba(138,43,226,0.13); /* Adjusted shadow for buttons */
            transition: filter 0.2s, transform 0.2s;
        }
        .btn-group button[type="reset"] {
            background: #f3e8ff; /* Matching example's secondary button */
            color: #a259ff; /* Matching example's secondary button */
            border: none; /* Ensure no border */
        }
        .btn-group button[type="reset"]:hover {
            filter: brightness(1.08);
            transform: scale(1.02);
        }
        .btn-group button[type="submit"] {
            background: linear-gradient(to right, #a259ff, #6a11cb); /* Matching example's primary button */
            color: white;
            border: none; /* Ensure no border */
        }
        .btn-group button[type="submit"]:hover {
            filter: brightness(1.08);
            transform: scale(1.02);
        }
        .back-btn {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            background: #f3e8ff; /* Matching example's back button */
            color: #a259ff; /* Matching example's back button */
            border: none;
            border-radius: 9999px;
            padding: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem; /* Adjusted font-size for better appearance */
            transition: background 0.2s, color 0.2s;
        }
        .back-btn:hover {
            background: #e0b0ff; /* Matching example's back button hover */
            color: #6a11cb; /* Matching example's back button hover */
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; // Include the navbar from your existing code ?>
    <div class="form-card"> <button onclick="window.history.back()" class="back-btn">←</button>
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