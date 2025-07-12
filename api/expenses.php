farhana 3B2, [9/7/2025 9:48 AM]
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Expenses Chart</title>
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

    .main-content {
      margin-left: 16rem;
      padding: 2rem;
      flex: 1;
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

  <!--?include 'sidebar.php'; ?-->
    <aside class="sidebar">
    <div class="flex flex-col justify-start h-full">
      <div class="flex items-center mb-8">
        <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" class="rounded-full mr-3" />
        <div>
        <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
          <p class="text-xs text-gray-500">Premium User</p>
        </div>
      </div>
      <button onclick="window.location.href='dashboard.html'" class="menu-btn w-full mb-4">
        ☰ Dashboard
      </button>

      <nav class="nav-links mb-auto">
        <a href="savings.html">⭐️ Savings</a>
        <a href="profile.html">👤 Profile</a>
        <a href="budget.html">⬇️ Budget</a>
        <a href="expenses.html" class="active">⬆️ Expenses</a>
      </nav>

      <button onclick="window.location.href='home.html'" class="logout w-full mt-10">
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
        <div class="chart-placeholder mb-6 md:mb-0">No data to display.</div>
        <div class="grid grid-cols-2 gap-4">
          <div class="pie-chart-legend-item"><span class="pie-chart-legend-color color-food"></span>Food</div>
          <div class="pie-chart-legend-item"><span class="pie-chart-legend-color color-transport"></span>Transport</div>
          <div class="pie-chart-legend-item"><span class="pie-chart-legend-color color-bill"></span>Bill</div>
          <div class="pie-chart-legend-item"><span class="pie-chart-legend-color color-topup"></span>Top Up</div>
          <div class="pie-chart-legend-item"><span class="pie-chart-legend-color color-entertainment"></span>Entertainment</div>
          <div class="pie-chart-legend-item"><span class="pie-chart-legend-color color-shopping"></span>Shopping</div>
          <div class="pie-chart-legend-item"><span class="pie-chart-legend-color color-utilities"></span>Utilities</div>
        </div>
      </div>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Expense Categories</h2>

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
              <option value="Food">Food</option>
              <option value="Transport">Transport</option>
              <option value="Shopping">Shopping</option>
              <option value="Utilities">Utilities</option>
              <option value="Bill">Bill</option>
              <option value="Top Up">Top Up</option>
              <option value="Entertainment">Entertainment</option>
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
        <input type="hidden" id="editingExpenseIndex">
      </div>
    </div>

  <script>
    let expenses = JSON.parse(localStorage.getItem('expenses'))  [];

    function getCategoryColor(category) {
        switch (category) {
            case 'Food': return '#a78bfa';
            case 'Transport': return '#fcd34d';
            case 'Bill': return '#2dd4bf';
            case 'Top Up': return '#4ade80';
            case 'Entertainment': return '#60a5fa';
            case 'Shopping': return '#f97316';
            case 'Utilities': return '#ec4899';
            default: return '#cbd5e1';
        }
    }

    function updateExpensesPage() {
        const chartPlaceholder = document.querySelector('.chart-placeholder');
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
                // Remove all children except the one with 'no-data-message' class
                Array.from(categoryLists[category].children).forEach(child => {
                    if (!child.classList.contains('no-data-message')) {
                        child.remove();
                    }
                });
                const noDataMessage = categoryLists[category].querySelector('.no-data-message');
                if (noDataMessage) noDataMessage.style.display = 'block'; // Show no-data-message by default
            }
        }

        if (expenses.length === 0) {
            chartPlaceholder.textContent = 'No data to display. Add expenses to see your chart!';
        } else {
            const categoryTotals = expenses.reduce((acc, expense) => {
                acc[expense.category] = (acc[expense.category]  0) + expense.amount;
                return acc;
            }, {});

            let chartText = "Expense Breakdown:\n";
            for (const category in categoryTotals) {
                chartText += ${category}: RM ${categoryTotals[category].toFixed(2)}\n;
            }
            chartPlaceholder.textContent = chartText; // Update placeholder text for demonstration

            // Populate category lists
            for (const category in categoryLists) {
                const filteredExpenses = expenses.filter(exp => exp.category === category);
                if (filteredExpenses.length > 0) {
                    const noDataMessage = categoryLists[category].querySelector('.no-data-message');
                    if (noDataMessage) noDataMessage.style.display = 'none'; // Hide no-data-message if there's data

filteredExpenses.forEach((expense, index) => { // Added index to loop
                        const expenseItem = document.createElement('div');
                        expenseItem.className = 'flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0';
                        expenseItem.innerHTML = `
                            <p class="text-gray-700 font-medium">${expense.date ? new Date(expense.date).toLocaleDateString() : 'No Date'}: ${expense.description  'N/A'}</p>
                            <div class="action-buttons">
                                <button onclick="openEditExpenseModal(${expenses.indexOf(expense)})" class="edit-btn">Edit</button>
                                <button onclick="deleteExpense(${expenses.indexOf(expense)})" class="delete-btn">Delete</button>
                            </div>
                        `;
                        categoryLists[category].appendChild(expenseItem);
                    });
                }
            }
        }
        // Update total expenses in localStorage after any changes
        localStorage.setItem('totalExpenses', expenses.reduce((sum, exp) => sum + exp.amount, 0).toFixed(2));
    }

    function toggleEditExpenseModal() {
      document.getElementById('editExpenseModal').classList.toggle('hidden');
    }

    function openEditExpenseModal(index) {
      const expense = expenses[index];
      if (expense) {
        document.getElementById('editExpenseTitle').value = expense.description;
        document.getElementById('editExpenseAmount').value = expense.amount;
        document.getElementById('editExpenseCategory').value = expense.category;
        document.getElementById('editExpenseDate').value = expense.date;
        document.getElementById('editingExpenseIndex').value = index; // Store index for saving
        toggleEditExpenseModal();
      }
    }

    function saveEditedExpense() {
      const index = document.getElementById('editingExpenseIndex').value;
      const title = document.getElementById('editExpenseTitle').value;
      const amount = parseFloat(document.getElementById('editExpenseAmount').value);
      const category = document.getElementById('editExpenseCategory').value;
      const date = document.getElementById('editExpenseDate').value;

      if (isNaN(amount)  amount <= 0) {
        alert("Please enter a valid positive amount.");
        return;
      }
      if (!category) {
        alert("Please select a category.");
        return;
      }

      expenses[index] = {
        description: title,
        amount: amount,
        category: category,
        date: date
      };

      localStorage.setItem('expenses', JSON.stringify(expenses));
      updateExpensesPage();
      toggleEditExpenseModal();
    }

    function deleteExpense(index) {
      if (confirm("Are you sure you want to delete this expense?")) { // Using confirm for simplicity
        expenses.splice(index, 1); // Remove the expense at the given index
        localStorage.setItem('expenses', JSON.stringify(expenses));
        updateExpensesPage(); // Re-render the list
      }
    }

    window.onload = updateExpensesPage;
  </script>
</body>
</html>