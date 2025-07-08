<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}
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
      justify-content: space-between;
    }
    .menu-btn {
      background: linear-gradient(to right, #8e2de2, #4a00e0);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .nav-links a {
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      color: #4a00e0;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: background-color 0.2s ease-in-out;
    }
    .nav-links a.active,
    .nav-links a:hover {
      background-color: #e0b0ff;
    }
    .logout {
      background-color: #ef4444;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      transition: background-color 0.2s ease-in-out;
    }
    .logout:hover {
      background-color: #dc2626;
    }
    .primary-btn-gradient {
      background-image: linear-gradient(to right, #8e2de2, #4a00e0);
    }
    .primary-btn-gradient:hover {
      filter: brightness(1.1);
    }
    .no-data-message {
      text-align: center;
      color: #6b7280;
      font-style: italic;
      padding: 1rem;
    }
    .chart-placeholder {
      width: 250px;
      height: 250px;
      background-color: #cbd5e1;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #4a5568;
      font-size: 0.9rem;
      text-align: center;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
    }
  </style>
</head>

<body class="bg-gray-100">
  <?php include 'sidebar.php'; ?>

  <main class="ml-64 p-8">
    <header class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome back, Rebecca!</p>
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-gray-700 font-medium">March 2025</span>
        <button class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-calendar-alt text-xl"></i>
        </button>
      </div>
    </header>

    <!-- Summary Cards -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Savings Count -->
      <a href="savings.php" class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between hover:shadow-xl transition-all duration-300">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Savings Count</h2>
          <p id="totalSavingsCount" class="text-3xl font-bold text-green-600">0</p>
        </div>
      </a>

      <!-- Monthly Budget -->
      <a href="budget.php" class="bg-white p-6 rounded-2xl shadow-lg flex flex-col justify-between hover:shadow-xl transition-all duration-300">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Monthly Budget</h2>
          <p id="budgetAmountDisplay" class="text-3xl font-bold text-blue-600 mb-4">RM 0.00</p>
          <p id="budgetStartDate" class="text-sm text-gray-500">Starts: --/--/----</p>
        </div>
      </a>

      <!-- Total Expenses -->
      <a href="budget.php" class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between hover:shadow-xl transition-all duration-300">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Total Expenses</h2>
          <p id="totalExpensesAmount" class="text-3xl font-bold text-red-600">RM 0.00</p>
        </div>
        <i class="fas fa-money-bill-wave text-4xl text-red-400"></i>
      </a>

      <!-- Balance -->
      <a href="budget.php" class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between hover:shadow-xl transition-all duration-300">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Balance</h2>
          <p id="balanceAmount" class="text-3xl font-bold text-purple-600">RM 0.00</p>
        </div>
        <i class="fas fa-balance-scale text-4xl text-purple-400"></i>
      </a>
    </section>

    <!-- Recent Expenses -->
    <section class="grid grid-cols-1 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Recent Expenses</h2>
        <div id="recentExpensesList">
          <div class="no-data-message">No expenses recorded yet. Start adding your expenses!</div>
        </div>
        <button class="mt-6 w-full py-3 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center space-x-2 primary-btn-gradient" onclick="window.location.href='add_money_expenses.php'">
          <i class="fas fa-plus-circle"></i>
          <span>Add New Expenses</span>
        </button>
      </div>
    </section>

    <!-- Expenses Chart -->
    <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Expenses Chart (March)</h2>
      <div class="flex flex-col md:flex-row items-center justify-around">
        <div class="chart-placeholder">No data to display.</div>
        <div class="grid grid-cols-2 gap-4">
          <div class="flex items-center"><span class="w-4 h-4 bg-purple-700 rounded-full mr-2"></span>Food</div>
          <div class="flex items-center"><span class="w-4 h-4 bg-green-500 rounded-full mr-2"></span>Topup</div>
          <div class="flex items-center"><span class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></span>Transport</div>
          <div class="flex items-center"><span class="w-4 h-4 bg-blue-500 rounded-full mr-2"></span>Entertainment</div>
          <div class="flex items-center"><span class="w-4 h-4 bg-cyan-500 rounded-full mr-2"></span>Bill</div>
        </div>
      </div>
    </section>
  </main>

  <script>
    let monthlyBudget = parseFloat(localStorage.getItem('monthlyBudget')) || 0;
    let totalExpenses = parseFloat(localStorage.getItem('totalExpenses')) || 0;
    let budgetStartDate = localStorage.getItem('budgetStartDate') || '';

    function updateDashboardSavingsCount() {
      const savings = JSON.parse(localStorage.getItem('savings')) || [];
      document.getElementById('totalSavingsCount').textContent = savings.length;
    }

    function updateRecentExpenses() {
        const expenses = JSON.parse(localStorage.getItem('expenses')) || [];
        const recentExpensesList = document.getElementById('recentExpensesList');
        
        // Clear existing list items, but keep the 'no-data-message' if it exists
        Array.from(recentExpensesList.children).forEach(child => {
            if (!child.classList.contains('no-data-message')) {
                child.remove();
            }
        });

        const noDataMessage = recentExpensesList.querySelector('.no-data-message');

        if (expenses.length === 0) {
            if (noDataMessage) noDataMessage.style.display = 'block';
        } else {
            if (noDataMessage) noDataMessage.style.display = 'none';

            // Sort expenses by date in descending order (most recent first)
            const sortedExpenses = expenses.sort((a, b) => new Date(b.date) - new Date(a.date));
            // Display only the latest 5 expenses
            const latestExpenses = sortedExpenses.slice(0, 5);

            latestExpenses.forEach(expense => {
                const expenseItem = document.createElement('div');
                expenseItem.className = 'flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0';
                expenseItem.innerHTML = `
                    <p class="text-gray-700 font-medium">${expense.date ? new Date(expense.date).toLocaleDateString() : 'N/A'}: ${expense.description || 'N/A'}</p>
                    <p class="text-gray-900 font-semibold">RM ${expense.amount.toFixed(2)}</p>
                `;
                recentExpensesList.appendChild(expenseItem);
            });
        }
    }

    function updateDashboardValues() {
      updateDashboardSavingsCount();
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
      updateRecentExpenses(); // Call this function to update recent expenses
    }

    window.onload = () => {
      updateDashboardValues();
    };
  </script>
</body>
</html>