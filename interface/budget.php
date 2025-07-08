<?php // budget.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Budget</title>
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
    .main-content {
      margin-left: 16rem;
      padding: 2rem;
    }
    .modal-bg {
      background-color: rgba(0, 0, 0, 0.5);
    }
    .primary-btn-gradient {
      background-image: linear-gradient(to right, #8e2de2, #4a00e0);
    }
    .primary-btn-gradient:hover {
      filter: brightness(1.1);
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <main class="main-content">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Budget Overview</h1>
    <p class="text-gray-600 mb-8 text-lg">Manage your monthly budget and track your spending.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Monthly Budget Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col justify-between">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Monthly Budget</h2>
        <p id="budgetAmountDisplay" class="text-3xl font-bold text-blue-600 mb-4">RM 0.00</p>
        <p id="budgetStartDate" class="text-sm text-gray-500 mb-4">Start: --/--/----</p>
        <button onclick="toggleBudgetModal()" class="w-full py-3 px-4 rounded-xl shadow-lg text-white font-semibold primary-btn-gradient">
          Set Budget
        </button>
      </div>

      <!-- Total Expenses Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col justify-between">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Total Expenses</h2>
        <p id="totalExpensesAmount" class="text-3xl font-bold text-red-600 mb-4">RM 0.00</p>
        <button onclick="location.href='add_money_expenses.php'" class="w-full py-3 px-4 rounded-xl shadow-lg text-white font-semibold primary-btn-gradient">
          Add Expenses
        </button>
      </div>

      <!-- Balance Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col justify-between">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Balance</h2>
        <p id="balanceAmount" class="text-3xl font-bold text-purple-600 mb-4">RM 0.00</p>
        <div class="w-full py-3 px-4 rounded-xl"></div> <!-- Placeholder to maintain layout -->
      </div>
    </div>

   

  <!-- Budget Modal -->
  <div id="budgetModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-xl w-full relative">
      <button onclick="toggleBudgetModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Set Monthly Budget</h2>

      <div class="space-y-6">
        <div>
          <label for="budgetInput" class="block text-sm font-medium text-gray-700 mb-1">Budget Amount (RM)</label>
          <input type="number" id="budgetInput" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" placeholder="e.g., 2500.00">
        </div>
        <div>
          <label for="budgetDate" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
          <input type="date" id="budgetDate" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
        </div>
      </div>

      <div class="mt-8 flex justify-between space-x-4">
        <button onclick="toggleBudgetModal()" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-red-400 text-white font-semibold hover:bg-red-500">
          CANCEL
        </button>
        <button onclick="saveBudget()" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-green-600 text-white font-semibold hover:bg-green-700">
          SAVE
        </button>
      </div>
    </div>
  </div>

  <script>
    let monthlyBudget = parseFloat(localStorage.getItem('monthlyBudget')) || 0;
    let budgetStartDate = localStorage.getItem('budgetStartDate') || '';
    let totalExpenses = parseFloat(localStorage.getItem('totalExpenses')) || 0; // Ensure totalExpenses is initialized

    function updateUI() {
      document.getElementById('budgetAmountDisplay').textContent = `RM ${monthlyBudget.toFixed(2)}`;
      document.getElementById('totalExpensesAmount').textContent = `RM ${totalExpenses.toFixed(2)}`;
      document.getElementById('balanceAmount').textContent = `RM ${(monthlyBudget - totalExpenses).toFixed(2)}`;
      if (budgetStartDate) {
        const [y, m, d] = budgetStartDate.split("-");
        document.getElementById('budgetStartDate').textContent = `Start: ${d}/${m}/${y}`;
      } else {
        document.getElementById('budgetStartDate').textContent = "Start: --/--/----";
      }
    }

    function toggleBudgetModal() {
      document.getElementById("budgetModal").classList.toggle("hidden");
      if (!document.getElementById("budgetModal").classList.contains("hidden")) {
        document.getElementById("budgetInput").value = monthlyBudget;
        document.getElementById("budgetDate").value = budgetStartDate;
      }
    }

    function saveBudget() {
      const amount = parseFloat(document.getElementById("budgetInput").value);
      const date = document.getElementById("budgetDate").value;
      if (!amount || amount <= 0 || !date) {
        alert("Please enter valid budget and date.");
        return;
      }
      localStorage.setItem('monthlyBudget', amount);
      localStorage.setItem('budgetStartDate', date);
      monthlyBudget = amount;
      budgetStartDate = date;
      toggleBudgetModal();
      updateUI();
    }

    window.onload = updateUI;
  </script>
</body>
</html>
