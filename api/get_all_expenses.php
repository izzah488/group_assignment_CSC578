<?php
session_start(); // Start the session to access user ID

// Include your configuration and database connection files
// Assuming this file is in 'interface/' and config/dbconnection are in the parent root.
require_once '../config.php';
require_once '../dbconnection.php';

// --- IMPORTANT: User ID Management ---
// Redirect if user is not logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
$userID = $_SESSION['userID']; // Get the logged-in user's ID
// -------------------------------------

// You might fetch some initial data here if needed for PHP rendering,
// but the chart data will be fetched by JavaScript via AJAX.

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Expenses Chart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <!-- Include Google Charts library -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <style>
    /* Your existing CSS styles */
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

    .main-content {
      margin-left: 16rem;
      padding: 2rem;
      flex: 1;
    }

    /* Chart specific styles */
    .chart-container-d3 { /* Renamed from .chart-placeholder to avoid conflict and better describe */
        width: 100%; /* Make it responsive */
        height: 400px; /* Fixed height for the chart area */
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        background-color: #cbd5e1; /* Placeholder background */
        border-radius: 50%; /* Make it round */
        color: #4a5568;
        font-size: 0.9rem;
        text-align: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
    }
    @media (min-width: 768px) { /* Adjust for larger screens */
        .chart-container-d3 {
            width: 350px; /* Fixed width for the chart area on larger screens */
            height: 350px; /* Fixed height for the chart area on larger screens */
            flex-shrink: 0; /* Prevent shrinking */
        }
    }


    .add-expenses-btn {
      background-image: linear-gradient(to right, #8e2de2, #4a00e0);
    }

    .add-expenses-btn:hover {
      filter: brightness(1.1);
    }

    .pie-chart-legend-item {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .pie-chart-legend-color {
      width: 1.25rem;
      height: 1.25rem;
      border-radius: 0.25rem;
      margin-right: 0.5rem;
    }

    .color-food { background-color: #a78bfa; }
    .color-transport { background-color: #fcd34d; }
    .color-bill { background-color: #2dd4bf; }
    .color-topup { background-color: #4ade80; }
    .color-entertainment { background-color: #60a5fa; }
    .color-shopping { background-color: #f97316; } /* Added Shopping color */
    .color-utilities { background-color: #ec4899; } /* Added Utilities color */


    .expense-category-header {
      padding: 1rem 1.5rem;
      font-weight: 700;
      font-size: 1.125rem;
      border-top-left-radius: 0.5rem;
      border-top-right-radius: 0.5rem;
      margin-top: 1.5rem;
    }

    .expense-category-list {
      padding: 1rem 1.5rem;
      border-bottom-left-radius: 0.5rem;
      border-bottom-right-radius: 0.5rem;
      margin-bottom: 1rem;
    }

    .no-data-message {
      text-align: center;
      color: #6b7280;
      font-style: italic;
      padding: 1rem;
    }
    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }
    .action-buttons button {
      padding: 0.3rem 0.6rem;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      transition: background-color 0.2s;
    }
    .action-buttons .edit-btn {
      background-color: #3b82f6; /* blue-500 */
      color: white;
    }
    .action-buttons .edit-btn:hover {
      background-color: #2563eb; /* blue-600 */
    }
    .action-buttons .delete-btn {
      background-color: #ef4444; /* red-500 */
      color: white;
    }
    .action-buttons .delete-btn:hover {
      background-color: #dc2626; /* red-600 */
    }
  </style>
</head>
<body class="bg-gray-100 flex">

  <!-- Sidebar (PHP include for dynamic content if needed, otherwise keep as static HTML) -->
  <?php // include 'sidebar.php'; ?>
    <aside class="sidebar">
    <div class="flex flex-col justify-start h-full">
      <div class="flex items-center mb-8">
        <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" class="rounded-full mr-3" />
        <div>
        <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
          <p class="text-xs text-gray-500">Premium User</p>
        </div>
      </div>
      <button onclick="window.location.href='dashboard.php'" class="menu-btn w-full mb-4">
        ☰ Dashboard
      </button>

      <nav class="nav-links mb-auto">
        <a href="savings.php">⭐️ Savings</a>
        <a href="profile.php">👤 Profile</a>
        <a href="budget.php">⬇️ Budget</a>
        <a href="expenses.php" class="active">⬆️ Expenses</a>
      </nav>

      <button onclick="window.location.href='home.php'" class="logout w-full mt-10">
        <i class="fa-solid fa-power-off"></i>
        Log Out
      </button>
    </div>
    </aside>

    <main class="main-content">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Expenses Chart</h1>
        <p class="text-gray-600">Visualize your monthly commitments at a glance.</p>
      </div>
      <div class="flex items-center space-x-4 mt-4 md:mt-0">
        <span class="text-gray-700 font-medium">March 2025</span>
        <button class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-calendar-alt text-xl"></i>
        </button>
      </div>
    </header>

    <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Expenses Chart (March)</h2>
      <div class="flex flex-col md:flex-row items-center justify-around">
        <!-- Google Chart will be rendered here -->
        <div id="piechart" class="chart-container-d3">
            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
                Loading chart data...
            </div>
        </div>
        <div id="chart-legend" class="grid grid-cols-2 gap-4">
          <!-- Legend items will be dynamically generated here by JS -->
        </div>
      </div>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Expense Categories</h2>

      <!-- Expense Categories List (will be populated by JS) -->
      <div class="expense-category-header bg-purple-500 text-white shadow-md">Food</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="foodExpensesList">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-yellow-500 text-gray-800 shadow-md">Transport</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="transportExpensesList">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-teal-500 text-white shadow-md">Bill</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="billExpensesList">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-green-500 text-white shadow-md">Top Up</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="topupExpensesList">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-blue-500 text-white shadow-md">Entertainment</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="entertainmentExpensesList">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header" style="background-color: #f97316; color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Shopping</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="shoppingExpensesList">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-pink-500 text-white shadow-md">Utilities</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="utilitiesExpensesList">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>
    </section>
    </main>

    <!-- Edit Expense Modal -->
    <div id="editExpenseModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
      <div class="bg-white p-6 rounded-2xl shadow-xl max-w-xl w-full relative">
        <button onclick="toggleEditExpenseModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Expense</h2>

        <div class="space-y-6">
          <div>
            <label for="editExpenseTitle" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" id="editExpenseTitle" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" required>
          </div>
          <div>
            <label for="editExpenseAmount" class="block text-sm font-medium text-gray-700 mb-1">Amount (RM)</label>
            <input type="number" id="editExpenseAmount" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" step="0.01" required>
          </div>
          <div>
            <label for="editExpenseCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select id="editExpenseCategory" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" required>
              <!-- Options will be dynamically loaded from database -->
            </select>
          </div>
          <div>
            <label for="editExpenseDate" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" id="editExpenseDate" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" required>
          </div>
        </div>

        <div class="mt-8 flex justify-between space-x-4">
          <button onclick="toggleEditExpenseModal()" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-red-400 text-white font-semibold hover:bg-red-500">
            CANCEL
          </button>
          <button onclick="saveEditedExpense()" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-green-600 text-white font-semibold hover:bg-green-700">
            SAVE CHANGES
          </button>
        </div>
        <input type="hidden" id="editingExpenseId"> <!-- Changed from index to ID for DB operations -->
      </div>
    </div>

  <script>
    // No longer using localStorage for expenses, data will come from DB
    // let expenses = JSON.parse(localStorage.getItem('expenses')) || [];

    const categoryColors = {
        'Food': '#a78bfa',
        'Transport': '#fcd34d',
        'Shopping': '#f97316',
        'Utilities': '#ec4899',
        'Bill': '#2dd4bf',
        'Top Up': '#4ade80',
        'Entertainment': '#60a5fa'
    };

    // --- Google Charts Setup ---
    google.charts.load('current', {'packages':['corechart']});
    // Call the main update function after Google Charts is loaded
    google.charts.setOnLoadCallback(initExpensesPage);

    function initExpensesPage() {
        fetchAndDrawChart(); // Fetch and draw the pie chart
        fetchAndDisplayExpenses(); // Fetch and display the categorized expenses list
        fetchExpenseCategoriesForModal(); // Fetch categories for the edit/add modal dropdown
    }

    // Function to draw the Google Pie Chart
    function drawChart(data) {
        const chartDiv = document.getElementById('piechart');
        const legendDiv = document.getElementById('chart-legend');

        if (!data || data.length === 0) {
            chartDiv.innerHTML = '<p class="text-gray-500 text-lg text-center">No spending data available for this month.</p>';
            legendDiv.innerHTML = '';
            return;
        }

        // Prepare data for Google Pie Chart
        const chartData = [['Category', 'Amount']];
        data.forEach(item => {
            chartData.push([item.category_name, parseFloat(item.total_amount)]);
        });

        const googleChartData = google.visualization.arrayToDataTable(chartData);

        const options = {
            title: 'Spending by Category',
            pieSliceText: 'percentage',
            chartArea: {left: 15, top: 15, right: 15, bottom: 15, width: '90%', height: '90%'},
            colors: data.map(item => categoryColors[item.category_name] || '#cccccc'), // Use defined colors or a default
            legend: 'none' // We'll create a custom legend below
        };

        const chart = new google.visualization.PieChart(chartDiv);
        chart.draw(googleChartData, options);

        // Generate custom legend
        legendDiv.innerHTML = ''; // Clear existing legend
        data.forEach(item => {
            const legendItem = document.createElement('div');
            legendItem.className = 'pie-chart-legend-item';
            legendItem.innerHTML = `
                <span class="pie-chart-legend-color" style="background-color: ${categoryColors[item.category_name] || '#cccccc'};"></span>
                ${item.category_name} (RM ${parseFloat(item.total_amount).toFixed(2)})
            `;
            legendDiv.appendChild(legendItem);
        });
    }

    // Function to fetch and draw chart data from backend API
    function fetchAndDrawChart() {
        const chartDiv = document.getElementById('piechart');
        const legendDiv = document.getElementById('chart-legend');

        chartDiv.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
                Loading chart data...
            </div>
        `; // Show loading indicator
        legendDiv.innerHTML = ''; // Clear old legend

        // Fetch data from your PHP API endpoint
        // Adjust the fetch URL if your file structure is different.
        // Assuming 'expenses.php' is in 'interface/' and 'get_spending_data.php' is in 'api/'
        fetch('../api/get_spending_data.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error("API Error:", data.error);
                    chartDiv.innerHTML = `<p class="text-red-500 text-lg text-center">Error: ${data.error}</p>`;
                } else {
                    drawChart(data);
                }
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
                chartDiv.innerHTML = '<p class="text-red-500 text-lg text-center">Failed to load chart data.</p>';
            });
    }

    // Function to fetch and display categorized expenses list
    function fetchAndDisplayExpenses() {
        const categoryLists = {
            'Food': document.getElementById('foodExpensesList'),
            'Transport': document.getElementById('transportExpensesList'),
            'Shopping': document.getElementById('shoppingExpensesList'),
            'Utilities': document.getElementById('utilitiesExpensesList'),
            'Bill': document.getElementById('billExpensesList'),
            'Top Up': document.getElementById('topupExpensesList'),
            'Entertainment': document.getElementById('entertainmentExpensesList')
        };

        // Clear existing expense list items and reset "No data" messages
        for (const category in categoryLists) {
            if (categoryLists[category]) {
                Array.from(categoryLists[category].children).forEach(child => {
                    if (!child.classList.contains('no-data-message')) {
                        child.remove();
                    }
                });
                const noDataMessage = categoryLists[category].querySelector('.no-data-message');
                if (noDataMessage) noDataMessage.style.display = 'block'; // Show no-data-message by default
            }
        }

        // Fetch all expenses for the current month/user
        fetch('../api/get_all_expenses.php') // You'll need to create this API endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(expensesData => {
                if (expensesData.error) {
                    console.error("API Error:", expensesData.error);
                    // Display error message to user
                    return;
                }

                if (expensesData.length === 0) {
                    // All lists will show "No expenses in this category yet." by default
                    return;
                }

                // Populate category lists with fetched data
                for (const category in categoryLists) {
                    const filteredExpenses = expensesData.filter(exp => exp.category_name === category);
                    if (filteredExpenses.length > 0) {
                        const noDataMessage = categoryLists[category].querySelector('.no-data-message');
                        if (noDataMessage) noDataMessage.style.display = 'none'; // Hide no-data-message if there's data

                        filteredExpenses.forEach(expense => {
                            const expenseItem = document.createElement('div');
                            expenseItem.className = 'flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0';
                            expenseItem.innerHTML = `
                                <p class="text-gray-700 font-medium">${new Date(expense.expDate).toLocaleDateString()}: ${expense.expTitle}</p>
                                <div class="action-buttons">
                                    <button onclick="openEditExpenseModal(${expense.expenseID})" class="edit-btn">Edit</button>
                                    <button onclick="deleteExpense(${expense.expenseID})" class="delete-btn">Delete</button>
                                </div>
                            `;
                            categoryLists[category].appendChild(expenseItem);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching expenses list:', error);
                // Display error message to user
            });
    }

    // Function to fetch categories for the edit/add modal dropdown
    function fetchExpenseCategoriesForModal() {
        fetch('../api/get_expense_categories.php') // You'll need to create this API endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(categories => {
                const selectElement = document.getElementById('editExpenseCategory');
                selectElement.innerHTML = ''; // Clear existing options

                if (categories.error) {
                    console.error("API Error fetching categories:", categories.error);
                    // Add a default disabled option if categories can't be loaded
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Failed to load categories';
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    selectElement.appendChild(defaultOption);
                    return;
                }

                categories.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.catName; // Use category name for display/value
                    option.setAttribute('data-cat-id', cat.catLookupID); // Store ID for backend
                    option.textContent = cat.catName;
                    selectElement.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching expense categories:', error);
            });
    }


    function toggleEditExpenseModal() {
      document.getElementById('editExpenseModal').classList.toggle('hidden');
    }

    // Modified to fetch expense details from DB
    async function openEditExpenseModal(expenseID) {
        try {
            const response = await fetch(`../api/get_expense_details.php?expenseID=${expenseID}`); // New API endpoint
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const expense = await response.json();

            if (expense.error) {
                console.error("API Error fetching expense details:", expense.error);
                alert("Failed to load expense details: " + expense.error);
                return;
            }

            document.getElementById('editExpenseTitle').value = expense.expTitle;
            document.getElementById('editExpenseAmount').value = expense.expAmount;
            document.getElementById('editExpenseCategory').value = expense.category_name; // Set selected category
            document.getElementById('editExpenseDate').value = expense.expDate;
            document.getElementById('editingExpenseId').value = expense.expenseID; // Store expense ID for saving/deleting

            toggleEditExpenseModal();
        } catch (error) {
            console.error('Error opening edit modal:', error);
            alert('An error occurred while fetching expense details.');
        }
    }

    // Modified to save changes to DB
    async function saveEditedExpense() {
        const expenseID = document.getElementById('editingExpenseId').value;
        const title = document.getElementById('editExpenseTitle').value;
        const amount = parseFloat(document.getElementById('editExpenseAmount').value);
        const categoryName = document.getElementById('editExpenseCategory').value;
        const date = document.getElementById('editExpenseDate').value;

        if (isNaN(amount) || amount <= 0) {
            alert("Please enter a valid positive amount.");
            return;
        }
        if (!categoryName) {
            alert("Please select a category.");
            return;
        }

        // Get the catLookupID from the selected option's data-cat-id attribute
        const selectedOption = document.getElementById('editExpenseCategory').querySelector(`option[value="${categoryName}"]`);
        const catLookupID = selectedOption ? selectedOption.getAttribute('data-cat-id') : null;

        if (!catLookupID) {
            alert("Selected category ID not found. Please refresh the page.");
            return;
        }

        const expenseData = {
            expenseID: expenseID,
            expTitle: title,
            expAmount: amount,
            catLookupID: catLookupID, // Send the ID to the backend
            expDate: date
        };

        try {
            const response = await fetch('../api/update_expense.php', { // New API endpoint
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(expenseData)
            });
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const result = await response.json();

            if (result.success) {
                alert(result.message);
                initExpensesPage(); // Re-fetch all data to update UI
                toggleEditExpenseModal();
            } else {
                alert('Failed to save changes: ' + result.message);
            }
        } catch (error) {
            console.error('Error saving edited expense:', error);
            alert('An error occurred while saving changes.');
        }
    }

    // Modified to delete from DB
    async function deleteExpense(expenseID) {
        if (!confirm("Are you sure you want to delete this expense?")) {
            return; // User cancelled
        }

        try {
            const response = await fetch('../api/delete_expense.php', { // New API endpoint
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ expenseID: expenseID })
            });
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const result = await response.json();

            if (result.success) {
                alert(result.message);
                initExpensesPage(); // Re-fetch all data to update UI
            } else {
                alert('Failed to delete expense: ' + result.message);
            }
        } catch (error) {
            console.error('Error deleting expense:', error);
            alert('An error occurred while deleting the expense.');
        }
    }
  </script>
</body>
</html>