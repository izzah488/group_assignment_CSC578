<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Include configuration and database connection
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../dbconnection.php';

// Access the global PDO database handle
global $pdo;

// Include the common sidebar.php
require_once  'sidebar.php'; // Corrected path to sidebar.php

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
    error_log("Error fetching user profile for budget page: " . $e->getMessage());
}

// Generate a CSRF token if not already set (for form submissions)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
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
      display: flex; /* Ensure body is a flex container for sidebar and main content */
      min-height: 100vh; /* Make body take full viewport height */
      overflow-x: hidden; /* Prevent horizontal scroll on mobile with sidebar */
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
      /* Added for responsiveness */
      transform: translateX(-100%); /* Hidden by default on small screens */
      transition: transform 0.3s ease-in-out;
    }
    .sidebar.active {
        transform: translateX(0); /* Visible when active */
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
      flex-grow: 1; /* Allow main content to take remaining space */
      padding: 2rem;
      margin-left: 0; /* Default for mobile (no sidebar margin) */
      transition: margin-left 0.3s ease-in-out;
    }
    /* Desktop styles */
    @media (min-width: 768px) { /* md breakpoint */
        .sidebar {
            transform: translateX(0); /* Always visible on desktop */
            position: relative; /* Takes up space in the flow */
            flex-shrink: 0; /* Prevent sidebar from shrinking */
        }
        .main-content {
            margin-left: 16rem; /* Space for sidebar on desktop */
        }
        .menu-toggle-btn {
            display: none; /* Hide hamburger on desktop */
        }
        .sidebar-overlay {
            display: none; /* Hide overlay on desktop */
        }
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
    .hidden {
        display: none; /* Force hidden state for modals */
    }
    /* Message box styles */
    #messageContainer > div { /* Targets the dynamically created message boxes */
        position: relative; /* Changed from fixed to relative within container */
        margin-bottom: 1rem; /* Space between messages if multiple */
        padding: 15px 25px;
        border-radius: 8px;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        animation: fadeIn 0.3s ease-out; /* Fade in */
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-gray-100 flex">
  <!-- Overlay for mobile sidebar -->
  <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-30 md:hidden hidden" onclick="toggleSidebar()"></div>

  <!-- Hamburger menu button for mobile -->
  <button id="menu-toggle-btn" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-purple-600 text-white rounded-md shadow-lg">
      <i class="fas fa-bars"></i>
  </button>

  <?php
  // This loads your sidebar.php file
  require_once 'sidebar.php';
  ?>

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
        <button onclick="window.location.href='expenses.php'" class="w-full py-3 px-4 rounded-xl shadow-lg text-white font-semibold primary-btn-gradient">
          View Expenses
        </button>
      </div>

      <!-- Balance Card -->
      <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col justify-between">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Balance</h2>
        <p id="balanceAmount" class="text-3xl font-bold text-purple-600 mb-4">RM 0.00</p>
        <div class="w-full py-3 px-4 rounded-xl"></div> <!-- Placeholder to maintain layout -->
      </div>
    </div>

  </main>

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

  <!-- Message Container (for success/error messages) -->
  <div id="messageContainer" class="fixed bottom-4 right-4 z-50 w-full max-w-xs"></div>

  <script>
    // --- Global Variables ---
    let monthlyBudget = 0; // Will be fetched from DB
    let budgetStartDate = ''; // Will be fetched from DB

    // IMPORTANT: Set this to the actual URL of your unified API endpoint
    // Corrected to point to save_budget.php for budget operations
    const API_BASE_URL_EXPENSES = 'expenses_api.php'; // For fetching expenses data
    const API_BASE_URL_BUDGET = 'save_budget.php'; // For saving budget data

    // --- Sidebar Toggle JavaScript (for responsiveness) ---
    const sidebar = document.getElementById('main-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const menuToggleButton = document.getElementById('menu-toggle-btn');

    function toggleSidebar() {
        sidebar.classList.toggle('active'); // Toggles 'active' class for slide-in/out
        sidebarOverlay.classList.toggle('hidden'); // Toggles the overlay visibility
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
        const messageContainer = document.getElementById('messageContainer');
        if (!messageContainer) {
            console.error("Message container not found. Please add <div id='messageContainer'></div> to your HTML.");
            alert(message); // Fallback
            return;
        }

        const alertDiv = document.createElement('div');
        alertDiv.className = `p-4 mb-2 rounded-lg text-sm shadow-md ${
            type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' :
            type === 'error' ? 'bg-red-100 text-red-700 border border-red-400' :
            'bg-blue-100 text-blue-700 border border-blue-400'
        }`;
        alertDiv.textContent = message;

        // Clear previous messages before adding new one for simplicity, or manage multiple
        messageContainer.innerHTML = '';
        messageContainer.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 5000); // Message disappears after 5 seconds
    }

    // --- Fetch Total Expenses from API ---
    async function fetchTotalExpenses() {
        try {
            // Use API_BASE_URL_EXPENSES for fetching expense data
            const response = await fetch(`${API_BASE_URL_EXPENSES}?action=get_chart_data`);
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const result = await response.json();

            if (result.success && Array.isArray(result.data)) {
                // Sum all 'value' from the chart data to get total expenses
                const total = result.data.reduce((sum, item) => sum + parseFloat(item.value), 0);
                return total;
            } else {
                console.error("API Error fetching total expenses:", result.message || 'Unknown error');
                showMessage("Failed to load total expenses: " + (result.message || 'Unknown error'), 'error');
                return 0; // Return 0 if data fetching fails
            }
        } catch (error) {
            console.error('Error fetching total expenses:', error);
            showMessage('An error occurred while loading total expenses.', 'error');
            return 0; // Return 0 on network/other errors
        }
    }

    // --- Update UI Function ---
    async function updateUI() {
      // Fetch budget from DB
      const budgetData = await fetchBudgetFromDB();
      if (budgetData) {
        monthlyBudget = parseFloat(budgetData.totBudget);
        budgetStartDate = budgetData.budgetDate;
      } else {
        // If no budget found in DB, reset to 0 and empty date
        monthlyBudget = 0;
        budgetStartDate = '';
      }

      document.getElementById('budgetAmountDisplay').textContent = `RM ${monthlyBudget.toFixed(2)}`;

      // Fetch and update total expenses
      const totalExpenses = await fetchTotalExpenses();
      document.getElementById('totalExpensesAmount').textContent = `RM ${totalExpenses.toFixed(2)}`;

      const balance = monthlyBudget - totalExpenses;
      document.getElementById('balanceAmount').textContent = `RM ${balance.toFixed(2)}`;

      if (budgetStartDate) {
        const [y, m, d] = budgetStartDate.split("-");
        document.getElementById('budgetStartDate').textContent = `Start: ${d}/${m}/${y}`;
      } else {
        document.getElementById('budgetStartDate').textContent = "Start: --/--/----";
      }
    }

    // --- Fetch Budget from DB ---
    async function fetchBudgetFromDB() {
        try {
            // Use API_BASE_URL_EXPENSES for fetching budget data if get_budget action is there
            // Otherwise, you might need a separate API for budget
            const response = await fetch(`${API_BASE_URL_EXPENSES}?action=get_budget`);
            if (!response.ok) {
                throw new Error('Network response for budget was not ok ' + response.statusText);
            }
            const result = await response.json();

            if (result.success && result.data) {
                return result.data; // Returns { budgetID, userID, budgetDate, totBudget }
            } else {
                console.warn("No budget found for current month or API error:", result.message || 'Unknown error');
                return null;
            }
        } catch (error) {
            console.error('Error fetching budget from DB:', error);
            showMessage('An error occurred while loading your budget.', 'error');
            return null;
        }
    }

    // --- Modal Toggle Functions ---
    function toggleBudgetModal() {
      document.getElementById("budgetModal").classList.toggle("hidden");
      if (!document.getElementById("budgetModal").classList.contains("hidden")) {
        // When opening the modal, populate with current values from state
        document.getElementById("budgetInput").value = monthlyBudget > 0 ? monthlyBudget : ''; // Only show if > 0
        document.getElementById("budgetDate").value = budgetStartDate;
      }
    }

    // --- Save Budget Function ---
    async function saveBudget() {
      const amount = parseFloat(document.getElementById("budgetInput").value);
      const date = document.getElementById("budgetDate").value;
      const csrfToken = "<?= $_SESSION['csrf_token'] ?>"; // Get CSRF token from PHP session

      if (!amount || amount <= 0 || !date) {
        showMessage("Please enter valid budget and date.", 'error');
        return;
      }

      // Prepare data to send to PHP API
      const budgetData = {
          amount: amount,
          date: date,
          csrf_token: csrfToken // Include CSRF token here
      };

      try {
          // *** IMPORTANT CHANGE HERE: Point to the correct save_budget.php API ***
          const response = await fetch(API_BASE_URL_BUDGET, { // Use the dedicated budget API endpoint
              method: 'POST', // Use POST for saving/updating budget
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify(budgetData),
          });

          const result = await response.json();

          if (response.ok && result.success) {
              showMessage(result.message, 'success');
              // Update UI after successful save
              updateUI(); // Re-fetch budget and expenses and update all values
              toggleBudgetModal(); // Close modal
          } else {
              const errorMessage = result.message || 'Failed to save budget.';
              console.error("Server error:", errorMessage);
              showMessage(errorMessage, 'error');
          }
      } catch (error) {
          console.error('Fetch error:', error);
          showMessage('An error occurred while saving your budget. Please try again.', 'error');
      }
    }

    // Initial page load
    window.onload = updateUI;

    // Update sidebar user info on load
    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('sidebarName').textContent = "Hi, <?php echo htmlspecialchars($userName); ?> <?php echo htmlspecialchars($userLastName); ?>!";
      document.getElementById('sidebarProfilePic').src = "<?php echo htmlspecialchars($userImage); ?>";
    });
  </script>
</body>
</html>
