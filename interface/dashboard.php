<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Include configuration and database connection
require_once __DIR__ . '/../config.php'; // Ensure this path is correct
require_once __DIR__ . '/../dbconnection.php'; // Ensure this path is correct

global $pdo; // Access the global PDO database handle

// Get user ID from session for fetching profile data in sidebar
$userId = $_SESSION['userID'];

// Define variables for user profile data with defaults for sidebar
$userName = "User"; // Default name
$userLastName = "";
$userImage = "https://placehold.co/40x40/cbd5e1/000000?text=P"; // Default image

try {
    $sql = "SELECT fName, lName, proPic FROM users WHERE userID = :userId";
    $query = $pdo->prepare($sql);
    $query->bindParam(':userId', $userId, PDO::PARAM_INT);
    $query->execute();
    $userProfile = $query->fetch(PDO::FETCH_ASSOC);

    if ($userProfile) {
        $userName = htmlspecialchars($userProfile['fName']);
        $userLastName = htmlspecialchars($userProfile['lName']);
        if (!empty($userProfile['proPic'])) {
            $userImage = htmlspecialchars($userProfile['proPic']);
        }
    }
} catch (PDOException $e) {
    error_log("Error fetching user profile for sidebar: " . $e->getMessage());
    // Fallback to default values
}

// Define a consistent color map for categories, matching expenses.php
$CATEGORY_COLOR_MAP = [
    'Food' => '#4CAF50',        // Green
    'Transport' => '#FFC107',   // Amber
    'Shopping' => '#2196F3',    // Blue
    'Utilities' => '#F44336',   // Red
    'Bill' => '#9C27B0',        // Purple
    'Top Up' => '#FF9800',      // Orange
    'Entertainment' => '#00BCD4',// Cyan
    'Health' => '#E91E63',      // Pink
    'Education' => '#673AB7',   // Deep Purple
    'Others' => '#9E9E9E' // A default for categories not explicitly mapped
];

// Include the common sidebar.php
require_once  'sidebar.php'; // Corrected path to sidebar.php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Budget & Expenses Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f0f2f5;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 16rem;
      background-color: #ffffff;
      border-top-right-radius: 1.5rem;
      border-bottom-right-radius: 1.5rem;
      z-index: 10;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
    }
    .content {
      margin-left: 17rem; /* Adjust content to the right of the sidebar */
      padding: 2rem;
      flex-grow: 1;
    }
    .main-content {
      flex-grow: 1;
      padding: 2rem;
      overflow-y: auto;
    }
    .category-color-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
        vertical-align: middle;
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; // Include the sidebar ?>

  <div class="content">
    <header class="flex justify-between items-center mb-4">
      <div class="flex-grow">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">
          A quick overview of your financial status.
        </p>
      </div>
      </header>

    <main class="main-content">
      <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
          <div class="bg-white p-6 rounded-xl shadow-lg text-center">
              <h3 class="text-lg font-medium text-gray-500">Monthly Budget</h3>
              <p id="budgetAmountDisplay" class="text-3xl font-bold text-indigo-600 mt-2">RM 0.00</p>
              <p id="budgetStartDate" class="text-sm text-gray-500 mt-1">Starts: --/--/----</p>
          </div>
          <div class="bg-white p-6 rounded-xl shadow-lg text-center">
              <h3 class="text-lg font-medium text-gray-500">Total Expenses</h3>
              <p id="totalExpensesAmount" class="text-3xl font-bold text-red-600 mt-2">RM 0.00</p>
          </div>
          <div class="bg-white p-6 rounded-xl shadow-lg text-center">
              <h3 class="text-lg font-medium text-gray-500">Balance Remaining</h3>
              <p id="balanceAmount" class="text-3xl font-bold text-green-600 mt-2">RM 0.00</p>
          </div>
      </section>

      <section class="mt-8 bg-white p-6 rounded-xl shadow-lg text-center">
        <h3 class="text-lg font-medium text-gray-500">Total Savings Goals</h3>
        <p id="totalSavingsGoalsCount" class="text-3xl font-bold text-purple-600 mt-2">0</p>
      </section>

      <section class="mt-8 bg-white p-6 rounded-xl shadow-lg">
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">Expenses by Category (This Month)</h2>
          <div class="flex justify-center items-center h-64">
              <canvas id="expensesCategoryChart"></canvas>
          </div>
      </section>

      <section class="mt-8 bg-white p-6 rounded-xl shadow-lg">
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Expenses</h2>
          <div id="recentExpensesList" class="space-y-4">
              </div>
      </section>

    </main>
  </div>

  <script>
    const API_BASE_URL = 'api.php'; // Adjust this if your api.php is in a different directory

    // Define a consistent color map for categories, matching expenses.php
    const CATEGORY_COLOR_MAP_JS = <?= json_encode($CATEGORY_COLOR_MAP) ?>;

    let monthlyBudget = 0;
    let totalExpenses = 0;
    let budgetStartDate = null;
    let recentExpenses = [];
    let expensesByCategory = {};
    let totalSavingsGoalsCount = 0;

    let myPieChart; // Declare chart variable globally to allow updating

    async function fetchDashboardData() {
      try {
        const responses = await Promise.all([
          fetch(`${API_BASE_URL}?action=get_monthly_budget`),
          fetch(`${API_BASE_URL}?action=get_total_expenses_current_month`),
          fetch(`${API_BASE_URL}?action=get_recent_expenses`),
          fetch(`${API_BASE_URL}?action=get_monthly_expenses_by_category`),
          fetch(`${API_BASE_URL}?action=get_total_savings`)
        ]);

        const [
          budgetResponse,
          totalExpensesResponse,
          recentExpensesResponse,
          categoryExpensesResponse,
          totalSavingsResponse
        ] = await Promise.all(responses.map(res => res.json()));

        if (budgetResponse.success) {
          monthlyBudget = parseFloat(budgetResponse.monthlyBudget) || 0;
          budgetStartDate = budgetResponse.budgetStartDate || null;
        } else {
          console.error('Failed to fetch monthly budget:', budgetResponse.message);
          monthlyBudget = 0; // Default to 0 on error
          budgetStartDate = null;
        }

        if (totalExpensesResponse.success) {
          totalExpenses = parseFloat(totalExpensesResponse.totalExpenses) || 0;
        } else {
          console.error('Failed to fetch total expenses:', totalExpensesResponse.message);
          totalExpenses = 0; // Default to 0 on error
        }

        if (recentExpensesResponse.success) {
          recentExpenses = recentExpensesResponse.recentExpenses || [];
        } else {
          console.error('Failed to fetch recent expenses:', recentExpensesResponse.message);
          recentExpenses = []; // Default to empty array
        }

        if (categoryExpensesResponse.success) {
          expensesByCategory = categoryExpensesResponse.expensesByCategory || {};
        } else {
          console.error('Failed to fetch expenses by category:', categoryExpensesResponse.message);
          expensesByCategory = {}; // Default to empty object
        }

        if (totalSavingsResponse.success) {
          totalSavingsGoalsCount = parseInt(totalSavingsResponse.totalGoalsCount) || 0;
        } else {
            console.error('Failed to fetch total savings goals count:', totalSavingsResponse.message);
            totalSavingsGoalsCount = 0; // Default to 0
        }

        // Update all UI elements after fetching all data
        updateDashboardValues();
        updateRecentExpenses();
        updatePieChart(); // Call chart update here
        updateDashboardSavingsCount();

      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    }

    function updateRecentExpenses() {
      const recentExpensesList = document.getElementById('recentExpensesList');
      recentExpensesList.innerHTML = ''; // Clear existing list

      if (recentExpenses.length === 0) {
        recentExpensesList.innerHTML = '<p class="text-gray-500 text-center">No recent expenses.</p>';
        return;
      }

      recentExpenses.forEach(expense => {
        const expenseItem = document.createElement('div');
        expenseItem.className = 'flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0';
        expenseItem.innerHTML = `
            <p class="text-gray-700 font-medium">${expense.date ? new Date(expense.date).toLocaleDateString() : 'N/A'}: ${expense.description || 'N/A'}</p>
            <p class="text-gray-900 font-semibold">RM ${parseFloat(expense.amount).toFixed(2)}</p>
        `;
        recentExpensesList.appendChild(expenseItem);
      });
    }

    function updatePieChart() {
      const ctx = document.getElementById('expensesCategoryChart').getContext('2d');
      const categories = Object.keys(expensesByCategory);
      const dataValues = Object.values(expensesByCategory);

      // Map categories to their specific colors from the CATEGORY_COLOR_MAP_JS
      const backgroundColors = categories.map(category => CATEGORY_COLOR_MAP_JS[category] || CATEGORY_COLOR_MAP_JS['Others']);

      // If the chart instance already exists, destroy it before creating a new one
      if (myPieChart) {
        myPieChart.destroy();
      }

      myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: categories,
          datasets: [{
            data: dataValues,
            backgroundColor: backgroundColors, // Use the dynamically generated colors
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Expenses by Category (This Month)'
            }
          }
        }
      });
    }

    function updateDashboardSavingsCount() {
      document.getElementById('totalSavingsGoalsCount').textContent = totalSavingsGoalsCount;
    }

    function updateDashboardValues() {
      document.getElementById('budgetAmountDisplay').textContent = `RM ${monthlyBudget.toFixed(2)}`;
      document.getElementById('totalExpensesAmount').textContent = `RM ${totalExpenses.toFixed(2)}`;
      const balance = monthlyBudget - totalExpenses;
      document.getElementById('balanceAmount').textContent = `RM ${balance.toFixed(2)}`;
      if (budgetStartDate) {
        const [year, month, day] = budgetStartDate.split('-');
        document.getElementById('budgetStartDate').textContent = `Starts: ${day}/${month}/${year}`;
      } else {
        document.getElementById('budgetStartDate').textContent = `Starts: --/--/----`;
      }
    }

    // --- Initial Page Load ---
    window.onload = () => {
      fetchDashboardData(); // Fetch all data and then update UI
    };

    // Update sidebar user info on load (if sidebar is handled by JS)
    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('sidebarName').textContent = "Hi, <?php echo htmlspecialchars($userName); ?> <?php echo htmlspecialchars($userLastName); ?>!";
      document.getElementById('sidebarProfilePic').src = "<?php echo htmlspecialchars($userImage); ?>";
    });
  </script>
</body>
</html>
