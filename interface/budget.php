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

    .nav-links {
      display: flex;
      flex-direction: column;
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
      width: 100%;
      text-align: left;
    }

    .nav-links a.active,
    .nav-links a:hover {
      background-color: #e0b0ff;
    }

    .logout-btn {
      background-color: #f87171;
      color: white;
    }

    .main-content {
      margin-left: 16rem;
      padding: 2rem;
    }

    .progress-bar {
      height: 0.5rem;
      border-radius: 9999px;
      background-color: #e2e8f0;
    }

    .progress-fill {
      background-color: #ccc;
      width: 0%;
      height: 100%;
      border-radius: 9999px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="flex flex-col justify-start h-full">
      <!-- User Info -->
      <div class="flex items-center mb-8">
        <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" alt="Profile Picture" class="rounded-full mr-3" />
        <div>
          <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
          <p class="text-xs text-gray-500">Premium User</p>
        </div>
      </div>

      <!-- Dashboard Button -->
      <button onclick="location.href='dashboard.html'" class="w-full bg-gradient-to-r from-purple-500 to-purple-700 text-white font-semibold py-2 px-4 rounded-lg mb-4 flex items-center justify-center gap-2">
        ☰ Dashboard
      </button>

      <!-- Nav Links -->
      <nav class="nav-links mb-auto">
        <a href="savings.html">⭐ Savings</a>
        <a href="profile.html">👤 Profile</a>
        <a href="statistic.html">📈 Statistics</a>
        <a href="budget.html" class="active">⬇ Budget</a>
        <a href="expenses.html">⬆ Expenses</a>
      </nav>

      <!-- Logout Button -->
      <button onclick="location.href='home.html'" class="logout-btn font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
        <i class="fas fa-sign-out-alt"></i> Log Out
      </button>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Budget</h1>
        <p class="text-sm text-gray-500 mt-1 italic">You haven’t set any budget yet.</p>
      </div>
      <div class="flex items-center space-x-4 mt-4 md:mt-0">
        <span class="text-gray-700 font-medium">March 2025</span>
        <button class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-calendar-alt text-xl"></i>
        </button>
      </div>
    </header>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Total Budget</h2>
          <p class="text-3xl font-bold text-purple-600">RM 0</p>
          <p class="text-sm text-gray-500 mt-2">No budget set</p>
        </div>
        <button onclick="location.href='edit_money_budget.html'" class="text-purple-500 hover:text-purple-700 transition-colors duration-200">
          <i class="fas fa-edit text-xl"></i>
        </button>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Budget Used</h2>
          <p class="text-3xl font-bold text-red-600">RM 0</p>
          <p class="text-sm text-gray-500 mt-2">No spending data</p>
        </div>
        <i class="fas fa-chart-line text-4xl text-red-400"></i>
      </div>
    </section>

    <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Budget Categories</h2>
      <div class="space-y-6">
        <!-- Repeated categories -->
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-700">Food</h3>
            <p class="text-sm text-gray-500">RM 0 / RM 0</p>
            <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-2">
              <div class="progress-fill"></div>
            </div>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-700">Transport</h3>
            <p class="text-sm text-gray-500">RM 0 / RM 0</p>
            <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-2">
              <div class="progress-fill"></div>
            </div>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-700">Shopping</h3>
            <p class="text-sm text-gray-500">RM 0 / RM 0</p>
            <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-2">
              <div class="progress-fill"></div>
            </div>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-700">Utilities</h3>
            <p class="text-sm text-gray-500">RM 0 / RM 0</p>
            <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-2">
              <div class="progress-fill"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Goal</h2>
          <p class="text-lg font-bold text-gray-900">RM 0</p>
          <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
            <div class="progress-fill"></div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Balance</h2>
          <p class="text-lg font-bold text-gray-900">RM 0</p>
          <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
            <div class="progress-fill"></div>
          </div>
        </div>
      </div>
    </section>

    <button onclick="location.href='add_money_expenses.html'" class="w-full py-4 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center space-x-2 bg-gradient-to-r from-purple-500 to-purple-700 hover:brightness-110">
      <i class="fas fa-plus-circle"></i>
      <span>Add New Expenses</span>
    </button>

    <button onclick="location.href='budget_record.html'" class="mt-4 w-full py-4 px-4 rounded-xl shadow-lg text-purple-700 font-semibold text-lg flex items-center justify-center space-x-2 bg-white border border-purple-300 hover:bg-purple-50 transition-colors duration-200">
      <i class="fas fa-file-alt"></i>
      <span>View Full Records</span>
    </button>
  </div>

</body>
</html>
