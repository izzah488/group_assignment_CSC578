<?php
// File: public/expenses.php

session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Include your configuration and database connection files
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../dbconnection.php';

global $pdo; // Access the global database handle

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
        if ($userProfile['proPic']) {
            $userImage = htmlspecialchars($userProfile['proPic']);
        }
    }
} catch (PDOException $e) {
    error_log("Error fetching user profile: " . $e->getMessage(), 3, LOG_FILE_PATH);
    // Continue with default values
}

// Define a consistent color map for categories
// This ensures 'Food' always gets the same color, 'Transport' always gets the same color, etc.
// These colors mirror the palette in dashboard.php for consistency.
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
    // Add more categories and colors as needed to match your expCatLookup table
    'Others' => '#9E9E9E' // A default for categories not explicitly mapped
];

// --- PHP to fetch all categories for the Edit Modal dropdown and assign colors ---
$allCategories = [];
$jsCategoryColorsMap = []; // To store category name -> color mapping for JS
try {
    $catStmt = $pdo->query("SELECT catLookupID, catName FROM expCatLookup ORDER BY catName");
    $fetchedCategories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($fetchedCategories as $cat) {
        $allCategories[] = $cat; // Keep original structure for dropdowns
        // Assign color from the fixed map, default to 'Others' if category name not found in map
        $jsCategoryColorsMap[$cat['catName']] = $CATEGORY_COLOR_MAP[$cat['catName']] ?? $CATEGORY_COLOR_MAP['Others'];
    }
} catch (PDOException $e) {
    error_log("Error fetching categories for expenses page: " . $e->getMessage(), 3, LOG_FILE_PATH);
    // Handle error, leave categories empty or add a fallback
}
// ---------------------------------------------------------------

// Generate a CSRF token if not already set (for form submissions)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
  <!-- D3.js CDN for the chart -->
  <script src="https://d3js.org/d3.v7.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <style>
    /* Global Styles */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f0f2f5; /* Light grey background */
        display: flex; /* For sidebar and main content layout */
        min-height: 100vh; /* Ensure body takes full viewport height */
        margin: 0;
        overflow-x: hidden; /* Prevent horizontal scroll */
    }

    /* Sidebar styles */
    .sidebar {
        position: fixed; /* Keep sidebar fixed */
        top: 0;
        left: 0;
        height: 100vh;
        width: 16rem; /* Tailwind's w-64 */
        background-color: #ffffff;
        border-top-right-radius: 1.5rem; /* rounded-r-3xl */
        border-bottom-right-radius: 1.5rem; /* rounded-r-3xl */
        z-index: 20; /* Higher than main content */
        padding: 1.5rem; /* p-6 */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* shadow-md */
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease-in-out;
        transform: translateX(-100%); /* Hidden by default on mobile */
    }

    .sidebar.active {
        transform: translateX(0); /* Show sidebar */
    }

    /* Overlay for mobile sidebar */
    #sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 15; /* Between sidebar and content */
        display: none; /* Hidden by default */
    }

    /* Main content area */
    .main-content {
        flex-grow: 1; /* Take up remaining space */
        padding: 1.5rem; /* p-6 */
        margin-left: 0; /* No margin on mobile */
        transition: margin-left 0.3s ease-in-out;
        width: 100%; /* Default to full width */
        box-sizing: border-box; /* Include padding in width */
    }

    /* Hamburger menu button for mobile */
    #menu-toggle-btn {
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 30; /* Above sidebar and overlay */
        background-color: #a259ff;
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 1.25rem;
        cursor: pointer;
        display: block; /* Show on mobile */
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* Media query for desktop */
    @media (min-width: 768px) {
        .sidebar {
            transform: translateX(0); /* Always visible on desktop */
            position: relative; /* Take up space in flow */
            flex-shrink: 0; /* Don't shrink */
        }
        .main-content {
            margin-left: 16rem; /* Offset for sidebar width */
            width: auto; /* Let flexbox determine width */
        }
        #sidebar-overlay, #menu-toggle-btn {
            display: none; /* Hide on desktop */
        }
    }

    /* Chart specific styles */
    .chart-container-d3 {
        position: relative;
        width: 100%;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .loading-indicator {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        color: #6b7280; /* text-gray-500 */
    }
    .legend-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.75rem 1.5rem; /* row-gap, column-gap */
        margin-top: 1rem;
        padding: 0 1rem;
    }
    .legend-item {
        display: flex;
        align-items: center;
    }
    .legend-color-box {
        width: 1rem;
        height: 1rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
    }
    .legend-label {
        font-size: 0.875rem; /* text-sm */
        color: #374151; /* text-gray-700 */
    }

    /* Expenses List styles */
    .expense-category-header {
        /* Removed fixed background-color and text-color to allow inline styling */
        padding: 0.75rem 1.5rem; /* py-3 px-6 */
        font-weight: 600; /* font-semibold */
        border-radius: 0.5rem; /* rounded-lg */
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        /* color will be set dynamically via inline style */
    }
    .expense-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1.5rem; /* py-3 px-6 */
        background-color: #ffffff; /* bg-white */
        border-radius: 0.5rem; /* rounded-lg */
        margin-bottom: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* shadow-sm */
    }
    .expense-item:last-child {
        margin-bottom: 0;
    }
    .expense-title {
        font-weight: 500; /* font-medium */
        color: #1f2937; /* text-gray-900 */
    }
    .expense-amount {
        font-weight: 600; /* font-semibold */
        color: #ef4444; /* text-red-500 */
    }
    .expense-actions button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: #a259ff; /* text-purple-600 */
        margin-left: 0.75rem; /* ml-3 */
        font-size: 1rem;
        transition: color 0.2s ease-in-out;
    }
    .expense-actions button:hover {
        color: #7c3aed; /* hover:text-purple-700 */
    }
    .expense-actions button.text-red-500 {
        color: #ef4444;
    }
    .expense-actions button.text-red-500:hover {
        color: #dc2626;
    }

    /* Modal styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 100; /* On top of everything */
    }
    .modal-content {
        background-color: #fff;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 500px;
        position: relative;
    }
    .modal-content .close-button {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
    }
    .modal-content .close-button:hover {
        color: #1f2937;
    }
    .modal-input {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-sizing: border-box; /* Include padding and border in element's total width and height */
    }
    .modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .modal-actions button {
        flex: 1;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: background-color 0.2s ease-in-out;
    }
    .modal-actions .save-button {
        background-color: #a259ff;
        color: white;
    }
    .modal-actions .save-button:hover {
        background-color: #7c3aed;
    }
    .modal-actions .cancel-button {
        background-color: #e5e7eb;
        color: #374151;
    }
    .modal-actions .cancel-button:hover {
        background-color: #d1d5db;
    }

    /* Message box styles */
    .message-box {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        font-weight: bold;
        z-index: 1000;
        display: none; /* Hidden by default */
        animation: fadeOut 0.5s forwards 2.5s; /* Fade out after 2.5s delay */
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .message-box.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .message-box.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }
  </style>
</head>
<body>
  <div id="sidebar-overlay" class="hidden" onclick="toggleSidebar()"></div>
  <button id="menu-toggle-btn" class="md:hidden" onclick="toggleSidebar()">
      <i class="fas fa-bars"></i>
  </button>

  <?php
  // This loads your sidebar.php file
  require_once 'sidebar.php';
  ?>

  <div class="main-content">
    <header class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Expenses Overview</h1>
      <a href="add_money_expenses.php" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
        + Add New
      </a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Total Expenses Card -->
      <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center justify-center">
        <p class="text-lg text-gray-500">Total Expenses</p>
        <p id="totalExpensesDisplay" class="text-4xl font-bold text-red-500 mt-2">RM 0.00</p>
      </div>

      <!-- Monthly Overview Card with D3.js Pie Chart -->
      <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Monthly Spending by Category</h2>
        <div class="chart-container-d3" id="pie-chart-container">
          <div class="loading-indicator">Loading chart...</div>
        </div>
        <div class="legend-container" id="pie-chart-legend"></div>
      </div>
    </div>

    <!-- Recent Expenses List -->
    <section class="bg-white rounded-xl shadow-lg p-6">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Expenses</h2>
      <div id="expenses-list">
        <!-- Expenses will be dynamically loaded here -->
        <!-- Added individual category lists for structured display -->
        <?php foreach ($allCategories as $cat): ?>
            <div class="expense-category-header shadow-md" style="background-color: <?= htmlspecialchars($jsCategoryColorsMap[$cat['catName']]) ?>; color: #ffffff;">
                <?= htmlspecialchars($cat['catName']) ?>
            </div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg" id="<?= htmlspecialchars($cat['catName']) ?>ExpensesList">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>
        <?php endforeach; ?>
      </div>
    </section>
  </div>

  <!-- Message Box -->
  <div id="messageBox" class="message-box hidden"></div>

  <!-- Edit Expense Modal -->
  <div id="editExpenseModal" class="modal hidden">
    <div class="modal-content">
      <button onclick="toggleEditExpenseModal()" class="close-button">&times;</button>
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Expense</h2>
      <input type="hidden" id="editingExpenseId"> <!-- Hidden input to store expenseID -->
      <input type="text" id="editExpenseTitle" placeholder="Title" class="modal-input" required>
      <input type="number" id="editExpenseAmount" placeholder="Amount (RM)" class="modal-input" step="0.01" required>
      <select id="editExpenseCategory" class="modal-input" required>
        <option value="" disabled selected>Select Category</option>
        <?php foreach ($allCategories as $cat): ?>
            <option value="<?= htmlspecialchars($cat['catName']) ?>"><?= htmlspecialchars($cat['catName']) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="date" id="editExpenseDate" class="modal-input" required>
      <div class="modal-actions">
        <button type="button" onclick="toggleEditExpenseModal()" class="cancel-button">CANCEL</button>
        <button type="button" onclick="saveEditedExpense()" class="save-button">SAVE CHANGES</button>
      </div>
    </div>
  </div>

  <!-- Add Expense Modal (Moved here for consistency, though add_money_expenses.php is a separate page) -->
  <!-- This modal is typically used if you add expenses directly on this page, not via add_money_expenses.php -->
  <!-- If 'Add New' button redirects, this modal might not be strictly needed here, but kept for JS function reference -->
  <div id="addExpenseModal" class="modal hidden">
    <div class="modal-content">
      <button type="button" onclick="toggleAddExpenseModal()" class="close-button">&times;</button>
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add New Expense</h2>

      <div class="space-y-6">
        <div>
          <label for="addExpenseTitle" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
          <input type="text" id="addExpenseTitle" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" required>
        </div>
        <div>
          <label for="addExpenseAmount" class="block text-sm font-medium text-gray-700 mb-1">Amount (RM)</label>
          <input type="number" id="addExpenseAmount" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" step="0.01" required>
        </div>
        <div>
          <label for="addExpenseCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select id="addExpenseCategory" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" required>
            <option value="" disabled selected>Select Category</option>
            <?php foreach ($allCategories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['catName']) ?>"><?= htmlspecialchars($cat['catName']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="addExpenseDate" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
          <input type="date" id="addExpenseDate" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50" required>
        </div>
      </div>

      <div class="mt-8 flex justify-between space-x-4">
        <button type="button" onclick="toggleAddExpenseModal()" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-red-400 text-white font-semibold hover:bg-red-500">
          CANCEL
        </button>
        <button type="button" onclick="addNewExpense()" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-green-600 text-white font-semibold hover:bg-green-700">
          ADD EXPENSE
        </button>
      </div>
    </div>
  </div>


  <script>
    // --- Global Variables ---
    let currentExpensesData = []; // Stores all expenses fetched from the backend
    let categoriesList = <?= json_encode($allCategories) ?>; // PHP categories to JS array

    // IMPORTANT: Set this to the actual URL of your unified API endpoint
    const API_BASE_URL = 'expenses_api.php'; // Corrected path to API

    // Define category colors for consistency in D3 chart and legend
    // This object is now dynamically populated by PHP to match the dashboard's palette
    const categoryColors = <?= json_encode($jsCategoryColorsMap) ?>;

    // --- Sidebar Toggle JavaScript (for responsiveness) ---
    const sidebar = document.getElementById('main-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const menuToggleButton = document.getElementById('menu-toggle-btn');

    function toggleSidebar() {
      sidebar.classList.toggle('active');
      sidebarOverlay.classList.toggle('hidden');
    }

    // Event listener for the hamburger menu button
    if (menuToggleButton) {
        menuToggleButton.addEventListener('click', toggleSidebar);
    }

    // Close sidebar if window resized to desktop (and it was open)
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) { // Tailwind's 'md' breakpoint
            sidebar.classList.remove('active');
            sidebarOverlay.classList.add('hidden');
        }
    });
    // --- END Sidebar Toggle JavaScript ---


    // --- Utility Function for Messages (replaces alert() and confirm()) ---
    function showMessage(message, type = 'info') {
        const messageContainer = document.getElementById('messageBox'); // Changed to messageBox
        if (!messageContainer) {
            console.error("Message container not found. Please add <div id='messageBox'></div> to your HTML.");
            alert(message); // Fallback if messageBox is missing
            return;
        }

        messageContainer.textContent = message;
        messageContainer.className = 'message-box'; // Reset classes
        messageContainer.classList.add(type); // Add success or error class
        messageContainer.classList.remove('hidden'); // Show the box

        // Automatically hide after 3 seconds
        setTimeout(() => {
            messageContainer.classList.add('hidden');
        }, 3000);
    }

    // Helper for HTML escaping to prevent XSS when inserting dynamic content
    function htmlspecialchars(str) {
        if (typeof str !== 'string') return str; // Return non-strings as is
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return str.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // --- Main Initialization Function ---
    document.addEventListener('DOMContentLoaded', initExpensesPage);

    async function initExpensesPage() {
        // Update sidebar user info
        document.getElementById('sidebarName').textContent = "Hi, <?= $userName ?> <?= $userLastName ?>!";
        document.getElementById('sidebarProfilePic').src = "<?= $userImage ?>";

        await Promise.all([
            fetchAndDisplayExpenses(),        // Fetch and display all expenses (list)
            fetchAndDrawChart()               // Ensure chart is drawn with latest data and updates total expenses
        ]);
    }

    // Helper function to get category lookup ID from category name
    function getCategoryLookupIdByName(categoryName) {
        const category = categoriesList.find(cat => cat.catName === categoryName);
        return category ? category.catLookupID : null;
    }

    // --- Fetch and Display Expenses (List) ---
    async function fetchAndDisplayExpenses() {
        console.log("Fetching and displaying expenses...");
        const categoryLists = {
            'Food': document.getElementById('FoodExpensesList'),
            'Transport': document.getElementById('TransportExpensesList'),
            'Shopping': document.getElementById('ShoppingExpensesList'),
            'Utilities': document.getElementById('UtilitiesExpensesList'),
            'Bill': document.getElementById('BillExpensesList'),
            'Top Up': document.getElementById('TopUpExpensesList'),
            'Entertainment': document.getElementById('EntertainmentExpensesList'),
            'Health': document.getElementById('HealthExpensesList'),
            'Education': document.getElementById('EducationExpensesList')
        };

        // Clear existing expense list items and reset "No data" messages
        for (const category in categoryLists) {
            if (categoryLists[category]) {
                categoryLists[category].innerHTML = '<div class="no-data-message">No expenses in this category yet.</div>';
            }
        }

        try {
            const response = await fetch(`${API_BASE_URL}?action=get_all_expenses`);
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const result = await response.json();
            console.log("API response for get_all_expenses:", result); // Log the API response

            if (result.success && Array.isArray(result.data)) {
                currentExpensesData = result.data; // Store fetched expenses

                if (currentExpensesData.length === 0) {
                    console.log("No expenses data returned from API.");
                    return;
                }

                // Populate category lists with fetched data
                for (const categoryKey in categoryLists) {
                    // Filter expenses by the 'category' property returned by the API
                    const filteredExpenses = currentExpensesData.filter(exp => exp.category === categoryKey);
                    if (filteredExpenses.length > 0) {
                        const noDataMessage = categoryLists[categoryKey].querySelector('.no-data-message');
                        if (noDataMessage) noDataMessage.style.display = 'none'; // Hide no-data-message if there's data

                        categoryLists[categoryKey].innerHTML = ''; // Clear only if there's data to add

                        filteredExpenses.forEach(expense => {
                            const expenseItem = document.createElement('div');
                            expenseItem.className = 'flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0';
                            expenseItem.innerHTML = `
                                <p class="text-gray-700 font-medium">${htmlspecialchars(new Date(expense.transaction_date).toLocaleDateString())}: ${htmlspecialchars(expense.title)}</p>
                                <p class="text-gray-900 font-semibold">RM ${parseFloat(expense.amount).toFixed(2)}</p>
                                <div class="action-buttons">
                                    <button onclick="openEditExpenseModal(${expense.id})" class="edit-btn">Edit</button>
                                    <button onclick="deleteExpense(${expense.id})" class="delete-btn">Delete</button>
                                </div>
                            `;
                            categoryLists[categoryKey].appendChild(expenseItem);
                        });
                    }
                }
            } else {
                console.error("API Error fetching expenses list:", result.message || 'Unknown error');
                showMessage("Failed to load expenses list: " + (result.message || 'Unknown error'), 'error');
            }
        } catch (error) {
            console.error('Error fetching expenses list:', error);
            showMessage('An error occurred while loading expenses list.', 'error');
        }
    }

    // --- D3.js Chart Drawing Logic ---
    async function fetchAndDrawChart() {
        console.log("Fetching and drawing chart data...");
        const chartContainer = d3.select("#pie-chart-container"); // Corrected ID
        const legendContainer = d3.select("#pie-chart-legend"); // Corrected ID
        const totalExpensesDisplay = document.getElementById('totalExpensesDisplay');

        chartContainer.html(`<div class="loading-indicator">Loading chart data...</div>`);
        legendContainer.html(''); // Clear old legend
        totalExpensesDisplay.textContent = 'RM 0.00'; // Reset total

        try {
            const response = await fetch(`${API_BASE_URL}?action=get_chart_data`);
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const result = await response.json();
            console.log("API response for get_chart_data:", result); // Log the API response

            if (result.success && Array.isArray(result.data)) {
                const chartData = result.data.map(item => ({
                    category: item.category, // Use 'category' as returned by API
                    value: parseFloat(item.value)
                }));

                if (chartData.length === 0) {
                    console.log("No chart data returned from API.");
                    chartContainer.html('<p class="text-gray-500 text-lg text-center">No spending data available for this month.</p>');
                    totalExpensesDisplay.textContent = 'RM 0.00';
                    return;
                }

                // Calculate total for display
                const totalAmount = chartData.reduce((sum, item) => sum + item.value, 0);
                totalExpensesDisplay.textContent = `RM ${totalAmount.toFixed(2)}`;

                drawPieChart(chartData);

            } else {
                console.error("API Error fetching chart data:", result.message || 'Unknown error');
                chartContainer.html(`<p class="text-red-500 text-lg text-center">Error: ${result.message || 'Failed to load chart data.'}</p>`);
                totalExpensesDisplay.textContent = 'RM 0.00';
            }
        } catch (error) {
            console.error('Error fetching chart data:', error);
            chartContainer.html('<p class="text-red-500 text-lg text-center">Failed to load chart data.</p>');
            totalExpensesDisplay.textContent = 'RM 0.00';
        }
    }

    function drawPieChart(data) {
        const chartContainer = d3.select("#pie-chart-container");
        const legendContainer = d3.select("#pie-chart-legend"); // Corrected ID
        // const tooltip = d3.select("#tooltip"); // Tooltip not defined in this HTML, remove or add HTML for it

        chartContainer.html(''); // Clear loading indicator
        legendContainer.html(''); // Clear old legend

        const width = chartContainer.node().getBoundingClientRect().width;
        const height = chartContainer.node().getBoundingClientRect().height;
        const radius = Math.min(width, height) / 2 - 20;

        const svg = chartContainer.append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", `translate(${width / 2},${height / 2})`);

        // Use the defined categoryColors map
        const color = d3.scaleOrdinal()
            .domain(Object.keys(categoryColors))
            .range(Object.values(categoryColors));

        const pie = d3.pie()
            .value(d => d.value)
            .sort(null);

        const arc = d3.arc()
            .innerRadius(radius * 0.6)
            .outerRadius(radius);

        const outerArc = d3.arc()
            .innerRadius(radius * 0.9)
            .outerRadius(radius * 0.9);

        const arcs = svg.selectAll(".arc")
            .data(pie(data))
            .enter()
            .append("g")
            .attr("class", "arc");

        arcs.append("path")
            .attr("d", arc)
            .attr("fill", d => color(d.data.category))
            .attr("stroke", "white")
            .style("stroke-width", "2px")
            // Mouse events for tooltip (if tooltip HTML is added)
            // .on("mouseover", function(event, d) {
            //     tooltip.transition()
            //         .duration(200)
            //         .style("opacity", .9);
            //     tooltip.html(`<strong>${d.data.category}</strong><br>RM ${d.data.value.toFixed(2)}`)
            //         .style("left", (event.pageX + 10) + "px")
            //         .style("top", (event.pageY - 28) + "px");
            // })
            // .on("mouseout", function(d) {
            //     tooltip.transition()
            //         .duration(500)
            //         .style("opacity", 0);
            // });

        // Add labels
        arcs.append("text")
            .attr("transform", d => `translate(${arc.centroid(d)})`)
            .attr("text-anchor", "middle")
            .attr("fill", "white")
            .style("font-size", "12px")
            .style("font-weight", "bold")
            .text(d => d.data.value > 0 ? `${(d.data.value / d3.sum(data, e => e.value) * 100).toFixed(1)}%` : ""); // Only show percentage if value > 0


        // Add legend
        const legend = legendContainer.selectAll(".legend-item") // Changed class to match CSS
            .data(data)
            .enter()
            .append("div")
            .attr("class", "legend-item"); // Changed class to match CSS

        legend.append("div")
            .attr("class", "legend-color-box") // Changed class to match CSS
            .style("background-color", d => color(d.category));

        legend.append("span")
            .attr("class", "legend-label") // Changed class to match CSS
            .text(d => `${d.category}: RM ${d.value.toFixed(2)}`);
    }


    // --- Modal Functions ---
    function toggleAddExpenseModal() {
      const modal = document.getElementById('addExpenseModal');
      modal.classList.toggle('hidden');
      // Clear form when opening
      if (!modal.classList.contains('hidden')) {
        document.getElementById('addExpenseTitle').value = '';
        document.getElementById('addExpenseAmount').value = '';
        document.getElementById('addExpenseCategory').value = ''; // Reset select
        document.getElementById('addExpenseDate').value = new Date().toISOString().slice(0, 10); // Set today's date
      }
    }

    function toggleEditExpenseModal() {
      const modal = document.getElementById('editExpenseModal');
      modal.classList.toggle('hidden');
    }

    // --- Open Edit Expense Modal ---
    async function openEditExpenseModal(expenseID) {
        try {
            const response = await fetch(`${API_BASE_URL}?action=get_expense_details&expenseID=${expenseID}`);
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const result = await response.json();

            if (result.success && result.data) {
                const expense = result.data;
                document.getElementById('editingExpenseId').value = expense.id; // Use 'id' from API
                document.getElementById('editExpenseTitle').value = expense.title; // Use 'title' from API
                document.getElementById('editExpenseAmount').value = parseFloat(expense.amount).toFixed(2);

                // Find the category name using catLookupID from the fetched categoriesList
                const category = categoriesList.find(cat => cat.catLookupID === expense.catLookupID);
                if (category) {
                    document.getElementById('editExpenseCategory').value = category.catName;
                } else {
                    console.warn("Category not found in local list for ID:", expense.catLookupID);
                    document.getElementById('editExpenseCategory').value = ''; // Reset if not found
                }

                document.getElementById('editExpenseDate').value = expense.transaction_date; // Use 'transaction_date' from API
                toggleEditExpenseModal();
            } else {
                console.error("API Error fetching expense details:", result.message || 'Unknown error');
                showMessage("Failed to load expense details: " + (result.message || 'Unknown error'), 'error');
            }
        } catch (error) {
            console.error('Error opening edit modal:', error);
            showMessage('An error occurred while fetching expense details.', 'error');
        }
    }

    // --- Add New Expense ---
    async function addNewExpense() {
        const title = document.getElementById('addExpenseTitle').value.trim();
        const amount = parseFloat(document.getElementById('addExpenseAmount').value);
        const categoryName = document.getElementById('addExpenseCategory').value;
        const date = document.getElementById('addExpenseDate').value;
        const csrfToken = "<?= $_SESSION['csrf_token'] ?>"; // Get CSRF token from PHP session

        // Client-side validation
        if (!title || isNaN(amount) || amount <= 0 || !categoryName || !date) {
            showMessage("Please fill in all fields correctly. Amount must be positive.", 'error');
            return;
        }

        const catLookupID = getCategoryLookupIdByName(categoryName);
        if (catLookupID === null) {
            showMessage("Selected category ID not found. Please refresh the page.", 'error');
            return;
        }

        const expenseData = {
            expTitle: title,
            expAmount: amount, // Send positive, API will handle storage
            catLookupID: catLookupID,
            expDate: date,
            csrf_token: csrfToken // Include CSRF token here
        };

        try {
            const response = await fetch(API_BASE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(expenseData)
            });
            const result = await response.json();

            if (response.ok && result.success) {
                showMessage(result.message, 'success');
                toggleAddExpenseModal();
                initExpensesPage(); // Re-fetch all data to update UI
            } else {
                const errorMessage = result.message || 'Failed to add expense.';
                console.error('Error adding expense:', errorMessage);
                showMessage('An error occurred while adding expense: ' + errorMessage, 'error');
            }
        } catch (error) {
            console.error('Error adding expense:', error);
            showMessage('An error occurred while adding expense: ' + error.message, 'error');
        }
    }

    // --- Save Edited Expense ---
    async function saveEditedExpense() {
        const expenseID = document.getElementById('editingExpenseId').value;
        const title = document.getElementById('editExpenseTitle').value.trim();
        const amount = parseFloat(document.getElementById('editExpenseAmount').value);
        const categoryName = document.getElementById('editExpenseCategory').value;
        const date = document.getElementById('editExpenseDate').value;
        const csrfToken = "<?= $_SESSION['csrf_token'] ?>"; // Get CSRF token

        // Client-side validation
        if (!title || isNaN(amount) || amount <= 0 || !categoryName || !date) {
            showMessage("Please fill in all fields with valid data. Amount must be positive.", 'error');
            return;
        }

        const catLookupID = getCategoryLookupIdByName(categoryName);
        if (catLookupID === null) {
            showMessage("Selected category ID not found. Please refresh the page.", 'error');
            return;
        }

        const expenseData = {
            expenseID: expenseID,
            expTitle: title,
            expAmount: amount.toFixed(2), // Ensure amount is formatted to 2 decimal places
            catLookupID: catLookupID,
            expDate: date,
            csrf_token: csrfToken // Include CSRF token here
        };

        try {
            const response = await fetch(API_BASE_URL, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(expenseData)
            });
            const result = await response.json();

            if (response.ok && result.success) {
                showMessage(result.message, 'success');
                toggleEditExpenseModal();
                initExpensesPage(); // Re-fetch all data to update UI
            } else {
                const errorMessage = result.message || 'Failed to save changes.';
                console.error('Error saving edited expense:', errorMessage);
                showMessage('An error occurred while saving changes: ' + errorMessage, 'error');
            }
        } catch (error) {
            console.error('Error saving edited expense:', error);
            showMessage('An error occurred while saving changes: ' + error.message, 'error');
        }
    }

    // --- Delete Expense ---
    async function deleteExpense(expenseID) {
        // Replaced `confirm()` with a custom modal for better UX.
        // For this example, I'll use a placeholder for the custom confirm.
        // In a real app, you'd show a custom modal and await user's choice.
        const userConfirmed = await new Promise(resolve => {
            if (window.confirm("Are you sure you want to delete this expense? This action cannot be undone.")) {
                resolve(true);
            } else {
                resolve(false);
            }
        });

        if (!userConfirmed) {
            return; // User cancelled
        }

        const csrfToken = "<?= $_SESSION['csrf_token'] ?>"; // Get CSRF token

        try {
            const response = await fetch(`${API_BASE_URL}?action=delete_expense&expenseID=${expenseID}&csrf_token=${csrfToken}`, {
                method: 'DELETE',
            });
            const result = await response.json();

            if (response.ok && result.success) {
                showMessage(result.message, 'success');
                initExpensesPage(); // Re-fetch all data to update UI
            } else {
                const errorMessage = result.message || 'Failed to delete expense.';
                console.error('Error deleting expense:', errorMessage);
                showMessage('An error occurred while deleting the expense: ' + errorMessage, 'error');
            }
        } catch (error) {
            console.error('Error deleting expense:', error);
            showMessage('An error occurred while deleting the expense: ' + error.message, 'error');
        }
    }

  </script>
</body>
</html>
