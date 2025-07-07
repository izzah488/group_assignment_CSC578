<?php
// TODO: Fetch user and statistics data from database
$user = [
    'first_name' => 'Rebecca',
    'type' => 'Premium User',
];
$budget = 0;
$expenses = 0;
$balance = 0;
$top_expenses = [
    ['category' => 'Food', 'amount' => 0],
    ['category' => 'Bills', 'amount' => 0],
    ['category' => 'Transport', 'amount' => 0],
    ['category' => 'Top Up', 'amount' => 0],
    ['category' => 'Entertainment', 'amount' => 0],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Statistics</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts - Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }
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
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 10;
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
      width: 100%;
      text-align: left;
    }
    .nav-links a.active,
    .nav-links a:hover {
      background: linear-gradient(90deg, #e0b0ff 0%, #f3e8ff 100%);
      color: #4a00e0;
    }
    .main-content {
      margin-left: 17rem;
      padding: 3.5rem 2rem 2rem 2rem;
    }
    .card {
      background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%);
      padding: 2rem;
      border-radius: 2rem;
      box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
      margin-bottom: 2rem;
    }

    .progress-bar {
      height: 0.5rem;
      border-radius: 9999px;
      background-color: #e2e8f0;
    }

    .progress-fill-purple {
      background-color: #a78bfa;
    }

    .progress-fill-green {
      background-color: #4ade80;
    }

    .progress-fill-pink {
      background-color: #f472b6;
    }

    .chart-placeholder {
      width: 100%;
      height: 200px;
      background-color: #cbd5e1;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #4a5568;
      font-size: 0.9rem;
      text-align: center;
      margin-top: 1rem;
    }

    .top-expenses-list {
      list-style: none;
      padding: 0;
    }

    .top-expenses-item {
      display: flex;
      justify-content: space-between;
      padding: 0.5rem 0;
      border-bottom: 1px solid #e2e8f0;
    }

    .top-expenses-item:last-child {
      border-bottom: none;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <aside class="sidebar">
  <!-- Top: user info + nav -->
  <div class="flex flex-col justify-start h-full">
    <!-- User Info -->
    <div class="flex items-center mb-8">
      <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" alt="Profile Picture" class="rounded-full mr-3" />
      <div>
        <p class="text-sm font-medium text-gray-700">Hi, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user['type']); ?></p>
      </div>
    </div>

    <!-- Dashboard Button -->
    <button onclick="window.location.href='dashboard.php'" class="w-full bg-gradient-to-r from-purple-500 to-purple-700 text-white font-semibold py-2 px-4 rounded-lg mb-4 flex items-center justify-center gap-2">
      ☰ Dashboard
    </button>

    <!-- Nav Links -->
    <nav class="nav-links mb-auto">
      <a href="savings.php">⭐ Savings</a>
      <a href="profile.php">👤 Profile</a>
      <a href="statistic.php" class="active">📈 Statistics</a>
      <a href="budget.php">⬇ Budget</a>
      <a href="expenses.php">⬆ Expenses</a>
    </nav>

    <!-- Logout Button at the very bottom -->
    <button onclick="window.location.href='index.php'" class="bg-red-500 hover:bg-red-600 text-white mt-10 font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
      <i class="fas fa-sign-out-alt"></i> Log Out
    </button>
  </div>
</aside>


  <!-- Main Content -->
  <div class="main-content">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Statistics</h1>
        <p class="text-gray-600">Track your monthly budget, expenses and balance.</p>
      </div>
      <div class="flex items-center space-x-4 mt-4 md:mt-0">
        <span class="text-gray-700 font-medium"><?php echo date('F Y'); ?></span>
        <button class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-calendar-alt text-xl"></i>
        </button>
      </div>
    </header>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Overview Cards -->
      <div class="space-y-6">
        <div class="card">
          <div class="flex justify-between items-center mb-2">
            <h2 class="text-xl font-semibold text-gray-800">Money Budget</h2>
            <i class="fas fa-edit text-gray-500 cursor-pointer"></i>
          </div>
          <p class="text-2xl font-bold text-gray-900">RM <?php echo number_format($budget, 2); ?></p>
          <div class="progress-bar mt-3">
            <div class="progress-fill-purple h-full" style="width: <?php echo ($budget > 0 ? 100 : 0); ?>%;"></div>
          </div>
        </div>

        <div class="card">
          <div class="flex justify-between items-center mb-2">
            <h2 class="text-xl font-semibold text-gray-800">Money Expenses</h2>
            <i class="fas fa-plus-circle text-gray-500 cursor-pointer"></i>
          </div>
          <p class="text-2xl font-bold text-gray-900">RM <?php echo number_format($expenses, 2); ?></p>
          <div class="progress-bar mt-3">
            <div class="progress-fill-green h-full" style="width: <?php echo ($budget > 0 ? min(100, ($expenses/$budget)*100) : 0); ?>%;"></div>
          </div>
        </div>

        <div class="card">
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Balance</h2>
          <p class="text-2xl font-bold text-gray-900">RM <?php echo number_format($balance, 2); ?></p>
          <div class="progress-bar mt-3">
            <div class="progress-fill-pink h-full" style="width: <?php echo ($budget > 0 ? min(100, ($balance/$budget)*100) : 0); ?>%;"></div>
          </div>
        </div>
      </div>

      <!-- Charts and Top Expenses -->
      <div class="space-y-6">
        <div class="card">
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Monthly Spends</h2>
          <div class="chart-placeholder">No data to display for monthly spends.</div>
        </div>

        <div class="card">
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Top Money Expenses</h2>
          <ul class="top-expenses-list">
            <?php foreach ($top_expenses as $expense): ?>
              <li class="top-expenses-item">
                <span><?php echo htmlspecialchars($expense['category']); ?></span>
                <span>RM <?php echo number_format($expense['amount'], 2); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </section>
  </div>
</body>
</html>