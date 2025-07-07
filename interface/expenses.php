<?php
// Start session and include database connection if needed in the future
// session_start();
// include '../backend/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Expenses Chart</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f0f2f5;
    }
    .container { display: flex; min-height: 100vh; }
    .sidebar {
      background: linear-gradient(135deg, #fff 60%, #e0b0ff 100%);
      width: 17rem;
      padding: 2rem 1.5rem 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-top-right-radius: 2rem;
      border-bottom-right-radius: 2rem;
      box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
    }
    .user-info {
      display: flex;
      align-items: center;
      margin-bottom: 2.5rem;
    }
    .avatar {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 9999px;
      margin-right: 1rem;
      object-fit: cover;
      background-color: #cbd5e1;
      border: 2px solid #e0b0ff;
    }
    .menu-btn {
      background: linear-gradient(90deg, #8e2de2 0%, #4a00e0 100%);
      color: white;
      padding: 0.8rem 1.5rem;
      border-radius: 0.9rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      margin-bottom: 1.2rem;
      box-shadow: 0 2px 8px 0 rgba(138,43,226,0.10);
      transition: filter 0.2s;
    }
    .menu-btn:hover {
      filter: brightness(1.08);
    }
    .nav-links {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    .nav-links a {
      padding: 0.7rem 1.3rem;
      border-radius: 0.8rem;
      color: #4a00e0;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: background 0.2s, color 0.2s;
      font-size: 1.05rem;
      letter-spacing: 0.01em;
    }
    .nav-links a.active,
    .nav-links a:hover {
      background: linear-gradient(90deg, #e0b0ff 0%, #f3e8ff 100%);
      color: #4a00e0;
    }
    .logout {
      background: linear-gradient(90deg, #fbd38d 0%, #f6ad55 100%);
      color: #c05621;
      padding: 0.8rem 1.5rem;
      border-radius: 0.9rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      transition: filter 0.2s;
      box-shadow: 0 2px 8px 0 rgba(251,211,141,0.10);
    }
    .logout:hover {
      filter: brightness(1.08);
    }
    .main-content {
      flex: 1;
      padding: 3.5rem 2rem 2rem 2rem;
      background: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }
    .form-card {
      background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%);
      padding: 2.5rem 2.5rem 2rem 2.5rem;
      border-radius: 2rem;
      box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
      max-width: 32rem;
      width: 100%;
      position: relative;
      margin-top: 1.5rem;
    }
    .back-btn { position: absolute; top: 1.5rem; left: 1.5rem; background-color: #e2e8f0; color: #4a5568; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; }
    .back-btn:hover { background-color: #cbd5e1; }
    .input { width: 100%; padding: 0.75rem; margin-bottom: 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; background-color: #f8fafc; }
    .input:focus { border-color: #a78bfa; box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.3); outline: none; }
    .btn-group { display: flex; justify-content: space-between; gap: 1rem; margin-top: 1.5rem; }
    .btn-group button { flex: 1; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; }
    .btn-group button[type="reset"] { background-color: #fbd38d; color: #c05621; }
    .btn-group button[type="submit"] { background: linear-gradient(to right, #8e2de2, #4a00e0); color: white; }
    .btn-group button:hover { filter: brightness(1.1); }
  </style>
</head>
<body class="bg-gray-100 flex">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="flex flex-col justify-start h-full">
      <div class="flex items-center mb-8">
        <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" class="rounded-full mr-3" />
        <div>
          <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
          <p class="text-xs text-gray-500">Premium User</p>
        </div>
      </div>
      <button onclick="window.location.href='dashbord.php'" class="menu-btn w-full mb-4">
        ☰ Dashboard
      </button>

      <nav class="nav-links mb-auto">
        <a href="savings.php">⭐️ Savings</a>
        <a href="profile.php">👤 Profile</a>
        <a href="statistic.php">📈 Statistics</a>
        <a href="budget.php">⬇️ Budget</a>
        <a href="expenses.php" class="active">⬆️ Expenses</a>
      </nav>

      <button onclick="window.location.href='logout.php'" class="logout w-full mt-10">
        ⏻ Log Out
      </button>
    </div>
  </aside>

  <!-- Main Content -->
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

    <!-- Chart Section -->
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
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Expense Categories</h2>

      <div class="expense-category-header bg-purple-500 text-white shadow-md">Food</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-yellow-500 text-gray-800 shadow-md">Transport</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-teal-500 text-white shadow-md">Bill</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-green-500 text-white shadow-md">Top Up</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>

      <div class="expense-category-header bg-blue-500 text-white shadow-md">Entertainment</div>
      <div class="expense-category-list bg-white shadow-md rounded-b-lg">
        <div class="no-data-message">No expenses in this category yet.</div>
      </div>
    </section>

    <!-- Add Expenses Button -->
    <button onclick="window.location.href='add_money_expenses.php'" class="w-full py-4 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center space-x-2 add-expenses-btn">
      <i class="fas fa-plus-circle"></i>
      <span>Add New Expenses</span>
    </button>
  </main>
</body>
</html>
