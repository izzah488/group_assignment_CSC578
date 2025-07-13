<?php
// File: public/expenses.php

session_start();

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Include the Database connection files
// Adjust paths as needed based on your project structure
require_once __DIR__ . '/../config.php'; // Assuming config.php defines LOG_FILE_PATH etc.
require_once __DIR__ . '/../dbconnection.php'; // Assuming dbconnection.php provides $dbh

// Get the PDO database connection instance (from dbconnection.php)
global $dbh; // Access the global database handle

// Get user ID from session for fetching profile data in sidebar
$userId = $_SESSION['userID'];

// Define variables for user profile data with defaults for sidebar
$userName = "Guest";
$userLastName = "";
$userImage = "default.png"; // A default image to display if the user has no image

try {
    $sql = "SELECT fName, lName, proPic FROM users WHERE id = :userId";
    $query = $pdo->prepare($sql);
    $query->bindParam(':userId', $userId, PDO::PARAM_INT);
    $query->execute();
    $userProfile = $query->fetch(PDO::FETCH_ASSOC);

    if ($userProfile) {
        $userName = $userProfile['fName'];
        $userLastName = $userProfile['lName'];
        if (!empty($userProfile['proPic'])) {
            $userImage = $userProfile['proPic'];
        }
    }
} catch (PDOException $e) {
    error_log("Error fetching user profile for expenses page: " . $e->getMessage(), 3, LOG_FILE_PATH);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Money Mate - Expenses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- D3.js CDN for the chart -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            display: flex; /* Use flex for sidebar and main content */
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
        .sidebar .user-panel {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb; /* Light border */
        }
        .sidebar .user-panel .image {
            width: 48px; /* Tailwind w-12 */
            height: 48px; /* Tailwind h-12 */
            border-radius: 9999px; /* Tailwind rounded-full */
            overflow: hidden;
            margin-right: 0.75rem; /* Tailwind mr-3 */
            border: 2px solid #d1d5db; /* Light gray border for image */
            flex-shrink: 0;
        }
        .sidebar .user-panel .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .sidebar .user-panel .info {
            flex-grow: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar .user-panel .info p {
            color: #4b5563; /* Tailwind text-gray-700 */
            font-size: 0.875rem; /* Tailwind text-sm */
            font-weight: 500;
        }
        .sidebar .user-panel .info p:last-child {
            color: #6b7280; /* Tailwind text-gray-500 */
            font-size: 0.75rem; /* Tailwind text-xs */
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
            transition: background-color 0.2s ease-in-out;
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
            margin-left: 16rem; /* Offset for fixed sidebar */
            padding: 2rem;
            flex: 1;
            width: calc(100% - 16rem); /* Adjust width to fit remaining space */
        }

        /* Chart specific styles */
        .chart-container-d3 {
            position: relative;
            width: 100%;
            height: 300px; /* Adjusted height for this page */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .loading-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 1.125rem;
        }
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #a259ff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 0.5rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .tooltip {
            position: absolute;
            text-align: center;
            padding: 0.5rem 1rem;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 0.5rem;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            font-size: 0.875rem;
            z-index: 100;
        }
        .arc path {
            stroke: #fff;
            stroke-width: 1.5px;
            transition: opacity 0.2s, transform 0.2s;
        }
        .arc:hover path {
            opacity: 0.8;
            transform: scale(1.03);
        }
        .arc text {
            font-size: 0.85rem;
            fill: #333;
            font-weight: 500;
            pointer-events: none;
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
            color: #4b5563; /* Default text color for legend items */
        }

        .pie-chart-legend-color {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 0.25rem;
            margin-right: 0.5rem;
        }

        /* Specific colors for legend items (matching D3's schemeCategory10 as closely as possible) */
        .color-food { background-color: #1f77b4; }
        .color-transport { background-color: #ff7f0e; }
        .color-bill { background-color: #2ca02c; }
        .color-topup { background-color: #d62728; }
        .color-entertainment { background-color: #9467bd; }
        .color-shopping { background-color: #8c564b; }
        .color-utilities { background-color: #e377c2; }
        .color-health { background-color: #7f7f7f; }
        .color-education { background-color: #bcbd22; }
        .color-other { background-color: #17becf; }


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

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Expenses Chart</h1>
                <p class="text-gray-600">Visualize your monthly commitments at a glance.</p>
            </div>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <span class="text-gray-700 font-medium">Current Month</span>
                <button class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </button>
            </div>
        </header>

        <!-- Total Expenses Card -->
        <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Total Expenses</h2>
            <p id="totalExpensesDisplay" class="text-4xl font-bold text-red-600">RM 0.00</p>
            <p class="text-sm text-gray-500 mt-2">Total spending this month</p>
        </section>

        <!-- Expenses Chart Section -->
        <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Expenses Breakdown by Category</h2>
            <div class="flex flex-col md:flex-row items-center justify-around">
                <div id="expenses-chart-container" class="chart-container-d3 mb-6 md:mb-0">
                    <div class="loading-indicator">
                        <div class="loading-spinner"></div>
                        Loading chart data...
                    </div>
                </div>
                <div id="expenses-chart-legend" class="grid grid-cols-2 gap-4">
                    <!-- Legend items will be generated by D3.js -->
                </div>
            </div>
            <div class="tooltip" id="expenses-chart-tooltip"></div>
        </section>

        <!-- Expense Categories List Section -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Expense Categories Details</h2>

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
            
            <!-- Added other categories as needed from your form -->
            <div class="expense-category-header" style="background-color: #f97316; color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Shopping</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="shoppingExpensesList">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

            <div class="expense-category-header bg-pink-500 text-white shadow-md">Utilities</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="utilitiesExpensesList">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

            <div class="expense-category-header bg-red-500 text-white shadow-md">Health</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="healthExpensesList">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

            <div class="expense-category-header bg-indigo-500 text-white shadow-md">Education</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="educationExpensesList">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

        </section>

        <button onclick="window.location.href='add_money_expenses.html'" class="w-full py-4 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center space-x-2 add-expenses-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Add New Expenses</span>
        </button>
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
              <option value="Food">Food</option>
              <option value="Transport">Transport</option>
              <option value="Shopping">Shopping</option>
              <option value="Utilities">Utilities</option>
              <option value="Bill">Bill</option>
              <option value="Top Up">Top Up</option>
              <option value="Entertainment">Entertainment</option>
              <option value="Health">Health</option>
              <option value="Education">Education</option>
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
        <input type="hidden" id="editingExpenseId"> <!-- Changed from editingExpenseIndex to editingExpenseId -->
      </div>
    </div>

<script>
    // Global variable to store expenses fetched from DB, including their IDs
    let currentExpensesData = [];

    // --- D3.js Chart Drawing Function ---
    function drawPieChart(containerId, legendId, tooltipId, data, titlePrefix) {
        const chartContainer = d3.select(`#${containerId}`);
        const tooltip = d3.select(`#${tooltipId}`);
        const loadingIndicator = chartContainer.select(".loading-indicator");
        const legendContainer = d3.select(`#${legendId}`);

        // Clear previous chart and legend
        chartContainer.html('');
        legendContainer.html('');

        if (!data || data.length === 0) {
            chartContainer.append("p")
                .attr("class", "text-gray-500 text-lg")
                .text(`No ${titlePrefix.toLowerCase()} data available to display for this month.`);
            return;
        }

        const margin = { top: 20, right: 20, bottom: 20, left: 20 };
        const containerWidth = chartContainer.node().getBoundingClientRect().width;
        const containerHeight = chartContainer.node().getBoundingClientRect().height;

        const width = containerWidth - margin.left - margin.right;
        const height = containerHeight - margin.top - margin.bottom;
        const radius = Math.min(width, height) / 2;

        const svg = chartContainer.append("svg")
            .attr("width", containerWidth)
            .attr("height", containerHeight)
            .attr("viewBox", `0 0 ${containerWidth} ${containerHeight}`)
            .append("g")
            .attr("transform", `translate(${containerWidth / 2}, ${containerHeight / 2})`);

        const pie = d3.pie()
            .value(d => d.value)
            .sort(null);

        const arc = d3.arc()
            .innerRadius(radius * 0.6)
            .outerRadius(radius * 0.9);

        const outerArc = d3.arc()
            .innerRadius(radius * 0.95)
            .outerRadius(radius * 0.95);

        const color = d3.scaleOrdinal(d3.schemeCategory10); // D3's built-in color scheme

        const arcs = svg.selectAll(".arc")
            .data(pie(data))
            .enter().append("g")
            .attr("class", "arc");

        arcs.append("path")
            .attr("d", arc)
            .attr("fill", d => color(d.data.category))
            .on("mouseover", function(event, d) {
                d3.select(this).transition().duration(100).attr("transform", "scale(1.03)");
                tooltip.style("opacity", 1);
                const percentage = ((d.endAngle - d.startAngle) / (2 * Math.PI) * 100).toFixed(1);
                tooltip.html(`<strong>${d.data.category}</strong><br>RM${d.data.value.toFixed(2)} (${percentage}%)`)
                    .style("left", (event.pageX + 10) + "px")
                    .style("top", (event.pageY - 28) + "px");
            })
            .on("mouseout", function() {
                d3.select(this).transition().duration(100).attr("transform", "scale(1)");
                tooltip.style("opacity", 0);
            });

        arcs.append("text")
            .attr("transform", d => `translate(${outerArc.centroid(d)})`)
            .attr("dy", "0.35em")
            .text(d => d.data.category)
            .style("text-anchor", d => {
                const midAngle = (d.startAngle + d.endAngle) / 2;
                return (midAngle < Math.PI ? "start" : "end");
            })
            .style("fill", "#333");

        const totalValue = d3.sum(data, d => d.value);
        svg.append("text")
            .attr("text-anchor", "middle")
            .attr("dy", "0.35em")
            .attr("class", "total-label")
            .style("font-size", "1.5rem")
            .style("font-weight", "bold")
            .style("fill", "#333")
            .text(`${titlePrefix}: RM${totalValue.toFixed(2)}`);

        // Generate Legend
        data.forEach(d => {
            const legendItem = legendContainer.append("div")
                .attr("class", "pie-chart-legend-item");
            legendItem.append("span")
                .attr("class", "pie-chart-legend-color")
                .style("background-color", color(d.category));
            legendItem.append("span")
                .text(d.category);
        });
    }

    // --- Function to update Expenses Page Content (Total and Category Lists) ---
    function updateExpensesPageContent(allExpenses) {
        currentExpensesData = allExpenses; // Store fetched data globally
        const totalExpensesDisplay = document.getElementById('totalExpensesDisplay');
        const categoryLists = {
            'Food': document.getElementById('foodExpensesList'),
            'Transport': document.getElementById('transportExpensesList'),
            'Shopping': document.getElementById('shoppingExpensesList'),
            'Utilities': document.getElementById('utilitiesExpensesList'),
            'Bill': document.getElementById('billExpensesList'),
            'Top Up': document.getElementById('topupExpensesList'),
            'Entertainment': document.getElementById('entertainmentExpensesList'),
            'Health': document.getElementById('healthExpensesList'),
            'Education': document.getElementById('educationExpensesList')
        };

        let total = 0;
        const categoryTotalsForChart = {}; // To aggregate data for the pie chart

        // Clear existing list items and show "No data" messages initially for all categories
        for (const category in categoryLists) {
            if (categoryLists[category]) {
                categoryLists[category].innerHTML = '<div class="no-data-message">No expenses in this category yet.</div>';
            }
        }

        if (allExpenses.length === 0) {
            totalExpensesDisplay.textContent = 'RM 0.00';
            // No data messages already shown by default
        } else {
            allExpenses.forEach(expense => {
                total += expense.amount; // amount is already positive for display
                categoryTotalsForChart[expense.category] = (categoryTotalsForChart[expense.category] || 0) + expense.amount;

                // Populate category lists
                if (categoryLists[expense.category]) {
                    const noDataMessage = categoryLists[expense.category].querySelector('.no-data-message');
                    if (noDataMessage) noDataMessage.style.display = 'none'; // Hide "No data" message

                    const expenseItem = document.createElement('div');
                    expenseItem.className = 'flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0';
                    expenseItem.innerHTML = `
                        <p class="text-gray-700 font-medium">${new Date(expense.transaction_date).toLocaleDateString()}: ${expense.title || 'N/A'}</p>
                        <p class="text-gray-900 font-semibold">RM ${expense.amount.toFixed(2)}</p>
                        <div class="action-buttons">
                            <button onclick="openEditExpenseModal(${expense.id})" class="edit-btn">Edit</button>
                            <button onclick="deleteExpense(${expense.id})" class="delete-btn">Delete</button>
                        </div>
                    `;
                    categoryLists[expense.category].appendChild(expenseItem);
                }
            });

            totalExpensesDisplay.textContent = `RM ${total.toFixed(2)}`;

            // Prepare data for the pie chart
            const chartData = Object.keys(categoryTotalsForChart).map(category => ({
                category: category,
                value: categoryTotalsForChart[category]
            }));

            drawPieChart('expenses-chart-container', 'expenses-chart-legend', 'expenses-chart-tooltip', chartData, 'Expenses');
        }
    }

    // --- Main Data Fetching Function ---
    function fetchExpensesData() {
        // Show loading indicator for chart
        const chartContainer = d3.select("#expenses-chart-container");
        chartContainer.html(`
            <div class="loading-indicator">
                <div class="loading-spinner"></div>
                Loading expenses data...
            </div>
        `);
        d3.select("#expenses-chart-legend").html(''); // Clear legend

        fetch('api/get_detailed_expenses.php') // Call the new API endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(result => {
                if (result.error) {
                    console.error("API Error:", result.error);
                    document.getElementById('totalExpensesDisplay').textContent = 'Error loading data.';
                    d3.select("#expenses-chart-container").html(`<p class="text-red-500">Error: ${result.error}</p>`);
                } else {
                    updateExpensesPageContent(result.data);
                }
            })
            .catch(error => {
                console.error('Error fetching expenses data:', error);
                document.getElementById('totalExpensesDisplay').textContent = 'Error loading data.';
                d3.select("#expenses-chart-container").html('<p class="text-red-500">Failed to load expenses chart data.</p>');
            });
    }

    // --- Modal Functions (Updated to use DB IDs) ---
    function toggleEditExpenseModal() {
      document.getElementById('editExpenseModal').classList.toggle('hidden');
    }

    function openEditExpenseModal(expenseId) {
      // Find the expense in the globally stored data by ID
      const expense = currentExpensesData.find(exp => exp.id == expenseId);
      if (expense) {
        document.getElementById('editExpenseTitle').value = expense.title; // Changed from description to title
        document.getElementById('editExpenseAmount').value = expense.amount;
        document.getElementById('editExpenseCategory').value = expense.category;
        document.getElementById('editExpenseDate').value = expense.transaction_date; // Changed from date to transaction_date
        document.getElementById('editingExpenseId').value = expense.id; // Store ID for saving
        toggleEditExpenseModal();
      } else {
          console.error("Expense not found with ID:", expenseId);
          // You might want to show a user-friendly error message here
      }
    }

    function saveEditedExpense() {
        const expenseId = document.getElementById('editingExpenseId').value;
        const title = document.getElementById('editExpenseTitle').value;
        const amount = parseFloat(document.getElementById('editExpenseAmount').value);
        const category = document.getElementById('editExpenseCategory').value;
        const date = document.getElementById('editExpenseDate').value;

        if (isNaN(amount) || amount <= 0) {
            alert("Please enter a valid positive amount."); // Use custom modal instead of alert
            return;
        }
        if (!category) {
            alert("Please select a category."); // Use custom modal instead of alert
            return;
        }

        // Prepare data for AJAX request
        const formData = new FormData();
        formData.append('expenseID', expenseId); // Match API's expected parameter name
        formData.append('expTitle', title);       // Match API's expected parameter name
        formData.append('expAmount', -Math.abs(amount)); // Ensure amount is negative for expense in DB
        formData.append('catLookupID', getCategoryLookupId(category)); // Get lookup ID for category
        formData.append('expDate', date);         // Match API's expected parameter name

        fetch('api/update_expense.php', { // Call the user's provided update_expense.php
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Use custom modal instead of alert in production
                toggleEditExpenseModal();
                fetchExpensesData(); // Re-fetch and re-render all data after successful edit
            } else {
                alert("Error: " + data.message); // Use custom modal instead of alert in production
            }
        })
        .catch(error => {
            console.error('Error saving edited expense:', error);
            alert("An error occurred while saving the expense."); // Use custom modal
        });
    }

    function deleteExpense(expenseId) {
      if (confirm("Are you sure you want to delete this expense?")) { // Use custom modal in production
        const formData = new FormData();
        formData.append('expenseID', expenseId); // Match API's expected parameter name

        fetch('api/delete_expense.php', { // Call the user's provided delete_expense.php
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Use custom modal
                fetchExpensesData(); // Re-fetch and re-render all data after successful delete
            } else {
                alert("Error: " + data.message); // Use custom modal
            }
        })
        .catch(error => {
            console.error('Error deleting expense:', error);
            alert("An error occurred while deleting the expense."); // Use custom modal
        });
      }
    }

    // Helper function to map category name to a lookup ID (assuming a fixed mapping or fetching from DB)
    // This is crucial because your update_expense.php expects catLookupID
    // In a real app, you'd fetch these from the database or have a more robust mapping.
    function getCategoryLookupId(categoryName) {
        switch (categoryName) {
            case 'Food': return 1;
            case 'Transport': return 2;
            case 'Shopping': return 3;
            case 'Utilities': return 4;
            case 'Bill': return 5;
            case 'Top Up': return 6;
            case 'Entertainment': return 7;
            case 'Health': return 8;
            case 'Education': return 9;
            default: return 0; // Or handle unknown category
        }
    }

    // Initial load of expenses data when the page loads
    fetchExpensesData();
</script>
</body>
</html>
