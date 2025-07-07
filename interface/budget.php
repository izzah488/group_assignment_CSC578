<?php
// TODO: Fetch user, budget, categories, and spending data from the database
// Example: $user = ...; $totalBudget = ...; $budgetUsed = ...; $categories = ...;
// For now, use placeholder values
$user = [
  'first_name' => 'User',
  'type' => 'User',
];
$totalBudget = 0;
$budgetUsed = 0;
$categories = [
  ['name' => 'Food', 'used' => 0, 'limit' => 0],
  ['name' => 'Transport', 'used' => 0, 'limit' => 0],
  ['name' => 'Shopping', 'used' => 0, 'limit' => 0],
  ['name' => 'Utilities', 'used' => 0, 'limit' => 0],
];
$moneyGoal = 0;
$moneyBalance = 0;
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
    .logout-btn {
      background: linear-gradient(90deg, #fbd38d 0%, #f6ad55 100%);
      color: #c05621;
      font-weight: 600;
      padding: 0.8rem 1.5rem;
      border-radius: 0.9rem;
      width: 100%;
      transition: filter 0.2s;
      box-shadow: 0 2px 8px 0 rgba(251,211,141,0.10);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .logout-btn:hover {
      filter: brightness(1.08);
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
    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .edit-btn {
      color: #8e2de2;
      background: #f3e8ff;
      border-radius: 0.7rem;
      padding: 0.5rem 1rem;
      font-size: 1.2rem;
      border: none;
      transition: background 0.2s, color 0.2s;
    }
    .edit-btn:hover {
      background: #e0b0ff;
      color: #fff;
    }
    .progress-bar-bg {
      background: #e5e7eb;
      border-radius: 9999px;
      height: 0.6rem;
      width: 12rem;
      margin-top: 0.5rem;
    }
    .progress-bar {
      height: 0.6rem;
      border-radius: 9999px;
    }
    .new-expenses-btn {
      background: linear-gradient(90deg, #8e2de2 0%, #4a00e0 100%);
      color: #fff;
      font-weight: 600;
      font-size: 1.1rem;
      border-radius: 1rem;
      padding: 1rem 0;
      width: 100%;
      margin-bottom: 1rem;
      box-shadow: 0 2px 8px 0 rgba(138,43,226,0.10);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.7rem;
      transition: filter 0.2s;
      border: none;
    }
    .new-expenses-btn:hover {
      filter: brightness(1.08);
    }
    .view-records-btn {
      background: #fff;
      color: #8e2de2;
      font-weight: 600;
      font-size: 1.1rem;
      border-radius: 1rem;
      padding: 1rem 0;
      width: 100%;
      border: 1.5px solid #e0b0ff;
      box-shadow: 0 2px 8px 0 rgba(138,43,226,0.10);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.7rem;
      transition: background 0.2s, color 0.2s;
    }
    .view-records-btn:hover {
      background: #f3e8ff;
      color: #4a00e0;
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
          <p class="text-sm font-medium text-gray-700">Hi, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
          <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user['type']); ?></p>
        </div>
      </div>

      <!-- Dashboard Button -->
      <button onclick="location.href='dashboard.php'" class="w-full bg-gradient-to-r from-purple-500 to-purple-700 text-white font-semibold py-2 px-4 rounded-lg mb-4 flex items-center justify-center gap-2">
        ☰ Dashboard
      </button>

      <!-- Nav Links -->
      <nav class="nav-links mb-auto">
        <a href="savings.php">⭐ Savings</a>
        <a href="profile.php">👤 Profile</a>
        <a href="statistic.php">📈 Statistics</a>
        <a href="budget.php" class="active">⬇ Budget</a>
        <a href="expenses.php">⬆ Expenses</a>
      </nav>

      <!-- Logout Button -->
      <button onclick="location.href='login.php'" class="logout-btn font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
        <i class="fas fa-sign-out-alt"></i> Log Out
      </button>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Budget</h1>
        <p class="text-sm text-gray-500 mt-1 italic"><?php echo $totalBudget == 0 ? 'You haven’t set any budget yet.' : 'Your current budget for this month.'; ?></p>
      </div>
      <div class="flex items-center space-x-4 mt-4 md:mt-0">
        <span class="text-gray-700 font-medium"><?php echo date('F Y'); ?></span>
        <button class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-calendar-alt text-xl"></i>
        </button>
      </div>
    </header>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Total Budget</h2>
          <p class="text-3xl font-bold text-purple-600">RM <?php echo number_format($totalBudget, 2); ?></p>
          <p class="text-sm text-gray-500 mt-2"><?php echo $totalBudget == 0 ? 'No budget set' : 'Budget set for this month'; ?></p>
        </div>
        <button onclick="location.href='edit_money_budget.php'" class="text-purple-500 hover:text-purple-700 transition-colors duration-200">
          <i class="fas fa-edit text-xl"></i>
        </button>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Budget Used</h2>
          <p class="text-3xl font-bold text-red-600">RM <?php echo number_format($budgetUsed, 2); ?></p>
          <p class="text-sm text-gray-500 mt-2"><?php echo $budgetUsed == 0 ? 'No spending data' : 'Spent so far this month'; ?></p>
        </div>
        <i class="fas fa-chart-line text-4xl text-red-400"></i>
      </div>
    </section>

    <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Budget Categories</h2>
      <div class="space-y-6">
        <?php foreach ($categories as $cat): ?>
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-700"><?php echo htmlspecialchars($cat['name']); ?></h3>
            <p class="text-sm text-gray-500">RM <?php echo number_format($cat['used'], 2); ?> / RM <?php echo number_format($cat['limit'], 2); ?></p>
            <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-2">
              <div class="progress-fill" style="background:linear-gradient(90deg,#a259ff 0%,#6a11cb 100%);width:<?php echo ($cat['limit'] > 0 ? min(100, ($cat['used']/$cat['limit'])*100) : 0); ?>%;height:100%;border-radius:9999px;"></div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Goal</h2>
          <p class="text-lg font-bold text-gray-900">RM <?php echo number_format($moneyGoal, 2); ?></p>
          <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
            <div class="progress-fill" style="background:linear-gradient(90deg,#a259ff 0%,#6a11cb 100%);width:0%;height:100%;border-radius:9999px;"></div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Balance</h2>
          <p class="text-lg font-bold text-gray-900">RM <?php echo number_format($moneyBalance, 2); ?></p>
          <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
            <div class="progress-fill" style="background:linear-gradient(90deg,#a259ff 0%,#6a11cb 100%);width:0%;height:100%;border-radius:9999px;"></div>
          </div>
        </div>
      </div>
    </section>

    <button onclick="location.href='add_money_expenses.php'" class="w-full py-4 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center space-x-2 bg-gradient-to-r from-purple-500 to-purple-700 hover:brightness-110">
      <i class="fas fa-plus-circle"></i>
      <span>Add New Expenses</span>
    </button>

    <button onclick="location.href='budget_record.php'" class="mt-4 w-full py-4 px-4 rounded-xl shadow-lg text-purple-700 font-semibold text-lg flex items-center justify-center space-x-2 bg-white border border-purple-300 hover:bg-purple-50 transition-colors duration-200">
      <i class="fas fa-file-alt"></i>
      <span>View Full Records</span>
    </button>
  </div>

</body>
</html>
