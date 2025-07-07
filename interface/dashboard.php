<?php
// TODO: Fetch user data, savings, expenses, and budget from the database
// Example: $user = ...; $totalSavings = ...; $totalExpenses = ...; $budgetAmount = ...;
// For now, use placeholder values
$user = [
  'first_name' => 'Rebecca',
  'last_name' => 'Louis',
  'email' => 'rebecca@gmail.com',
  'type' => 'Premium User',
];
$totalSavings = 0.00;
$totalExpenses = 0.00;
$budgetAmount = 0.00;
$recentExpenses = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Budget & Expenses Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%); }
    .sidebar { background: linear-gradient(135deg, #fff 60%, #e0b0ff 100%); width: 17rem; padding: 2rem 1.5rem 2rem 1.5rem; display: flex; flex-direction: column; justify-content: space-between; border-top-right-radius: 2rem; border-bottom-right-radius: 2rem; box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08); position: fixed; top: 0; left: 0; height: 100vh; z-index: 10; }
    .menu-btn { background: linear-gradient(90deg, #8e2de2 0%, #4a00e0 100%); color: white; padding: 0.8rem 1.5rem; border-radius: 0.9rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; margin-bottom: 1.2rem; box-shadow: 0 2px 8px 0 rgba(138,43,226,0.10); transition: filter 0.2s; }
    .menu-btn:hover { filter: brightness(1.08); }
    .nav-links { display: flex; flex-direction: column; gap: 0.5rem; }
    .nav-links a { padding: 0.7rem 1.3rem; border-radius: 0.8rem; color: #4a00e0; font-weight: 500; display: flex; align-items: center; gap: 0.75rem; transition: background 0.2s, color 0.2s; font-size: 1.05rem; letter-spacing: 0.01em; width: 100%; text-align: left; }
    .nav-links a.active, .nav-links a:hover { background: linear-gradient(90deg, #e0b0ff 0%, #f3e8ff 100%); color: #4a00e0; }
    .logout { background: linear-gradient(90deg, #fbd38d 0%, #f6ad55 100%); color: #c05621; font-weight: 600; padding: 0.8rem 1.5rem; border-radius: 0.9rem; width: 100%; transition: filter 0.2s; box-shadow: 0 2px 8px 0 rgba(251,211,141,0.10); display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .logout:hover { filter: brightness(1.08); }
    .main-content { margin-left: 17rem; padding: 3.5rem 2rem 2rem 2rem; }
    .card { background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%); padding: 2rem; border-radius: 2rem; box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08); margin-bottom: 2rem; }
    .primary-btn-gradient { background-image: linear-gradient(to right, #8e2de2, #4a00e0); }
    .primary-btn-gradient:hover { filter: brightness(1.1); }
    .chart-placeholder { width: 250px; height: 250px; background-color: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4a5568; font-size: 0.9rem; text-align: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.06); }
  </style>
</head>

<body class="bg-gray-100">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <div class="flex items-center mb-8">
        <img src="https://placehold.co/40x40/FF69B4/FFFFFF?text=R" alt="Profile Picture" class="rounded-full mr-3" />
        <div>
          <p class="text-sm font-medium text-gray-700">Hi, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
          <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user['type']); ?></p>
        </div>
      </div>
      <button onclick="window.location.href='dashboard.php'" class="w-full menu-btn mb-4">☰ Dashboard</button>
      <nav class="nav-links mb-auto">
        <a href="savings.php">⭐ Savings</a>
        <a href="profile.php">👤 Profile</a>
        <a href="statistic.php">📈 Statistics</a>
        <a href="budget.php">⬇ Budget</a>
        <a href="expenses.php">⬆ Expenses</a>
      </nav>
      <button onclick="window.location.href='index.php'" class="logout w-full mt-10">
        <i class="fas fa-sign-out-alt"></i> Log Out
      </button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <header class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-gray-700 font-medium"><?php echo date('F Y'); ?></span>
        <button class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-calendar-alt text-xl"></i>
        </button>
      </div>
    </header>

    <!-- Cards Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Total Savings -->
      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Total Savings</h2>
          <p id="totalSavings" class="text-3xl font-bold text-green-600">RM <?php echo number_format($totalSavings, 2); ?></p>
        </div>
        <button onclick="window.location.href='savings.php'" class="text-green-500 hover:text-green-700">
          <i class="fas fa-plus-circle text-4xl"></i>
        </button>
      </div>

      <!-- Total Expenses -->
      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Total Expenses</h2>
          <p id="totalExpenses" class="text-3xl font-bold text-red-600">RM <?php echo number_format($totalExpenses, 2); ?></p>
        </div>
        <i class="fas fa-money-bill-wave text-4xl text-red-400"></i>
      </div>

      <!-- Budget -->
      <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Set Monthly Budget</h2>
        <p id="budgetAmountDisplay" class="text-3xl font-bold text-blue-600 mb-4">RM <?php echo number_format($budgetAmount, 2); ?></p>
        <button onclick="window.location.href='budget.php'" class="w-full py-3 px-4 rounded-xl shadow-lg text-white font-semibold primary-btn-gradient">
          Set Budget
        </button>
      </div>
    </section>

    <!-- Recent Expenses -->
    <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Recent Expenses</h2>
      <ul id="recentExpensesList" class="space-y-3 text-gray-700 text-sm">
        <?php if (empty($recentExpenses)): ?>
          <p class="text-center text-gray-500 italic">No expenses yet.</p>
        <?php else: ?>
          <?php foreach (array_slice(array_reverse($recentExpenses), 0, 5) as $item): ?>
            <li class="flex justify-between border-b pb-1">
              <span><?php echo htmlspecialchars($item['title']); ?></span>
              <span class="text-red-500">RM <?php echo number_format($item['amount'], 2); ?></span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
      <button onclick="location.href='expenses.php'" class="mt-6 w-full py-3 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center space-x-2 primary-btn-gradient">
        <i class="fas fa-plus-circle"></i><span>Add New Expenses</span>
      </button>
    </section>

    <!-- Expenses Chart Placeholder -->
    <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Expenses Chart (<?php echo date('F'); ?>)</h2>
      <div class="chart-placeholder">No data to display.</div>
    </section>
  </main>
</body>
</html>
