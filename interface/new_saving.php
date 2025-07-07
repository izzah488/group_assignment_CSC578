<?php
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saving_for = htmlspecialchars($_POST['saving_for'] ?? '');
    $amount = htmlspecialchars($_POST['amount'] ?? '');
    $target_date = htmlspecialchars($_POST['target_date'] ?? '');
    // TODO: Save to database
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>New Saving</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    .logout-link {
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
      margin-top: 1rem;
    }
    .logout-link:hover {
      filter: brightness(1.08);
    }
    .main-content {
      margin-left: 17rem;
      padding: 3.5rem 2rem 2rem 2rem;
      flex: 1;
    }
    .form-card {
      background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%);
      padding: 2.5rem 2.5rem 2rem 2.5rem;
      border-radius: 2rem;
      box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
      max-width: 32rem;
      margin: 0 auto;
    }
  </style>
</head>
<body class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <div class="flex items-center mb-8">
        <img src="https://img.icons8.com/color/48/000000/combo-chart--v2.png" alt="Profile" class="rounded-full mr-3">
        <div>
          <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
          <p class="text-xs text-gray-500">Premium User</p>
        </div>
      </div>
      <button onclick="window.location.href='dashboard.php'" class="menu-btn w-full mb-4 bg-gradient-to-r from-purple-500 to-purple-700 text-white rounded-lg py-2 font-semibold">
        ☰ Dashboard
      </button>
      <nav class="nav-links">
        <a href="savings.php" class="bg-purple-100 text-purple-800 rounded-lg py-2 px-4 font-medium">⭐ Savings</a>
        <a href="editprofile.php" class="py-2 px-4 rounded-lg text-purple-800">👤 Profile</a>
        <a href="statistic.php" class="py-2 px-4 rounded-lg text-purple-800">📈 Statistics</a>
        <a href="budget.php" class="py-2 px-4 rounded-lg text-purple-800">⬇ Budget</a>
        <a href="expenses.php" class="py-2 px-4 rounded-lg text-purple-800">⬆ Expenses</a>
        <button onclick="window.location.href='login.php'" class="logout-link bg-yellow-100 text-yellow-800 rounded-lg py-2 px-4 mt-4">⏻ Log Out</button>
      </nav>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <header class="flex items-center mb-8">
      <button onclick="window.history.back()" class="text-gray-500 hover:text-gray-700 mr-4">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold text-gray-900">New Saving</h1>
        <p class="text-gray-600">Create a new saving goal.</p>
      </div>
    </header>

    <div class="form-card">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Add Saving Goal</h2>

      <?php if ($success): ?>
        <div style="background:#d1fae5;color:#065f46;border-radius:0.7rem;padding:1rem;text-align:center;margin-bottom:1rem;">
          Saving goal added successfully!
        </div>
      <?php endif; ?>

      <form method="POST" action="" class="space-y-6">
        <!-- Saving For -->
        <div>
          <label for="savingFor" class="block text-sm font-medium text-gray-700 mb-1">Saving For</label>
          <input type="text" id="savingFor" name="saving_for" placeholder="e.g., New Laptop" required class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800">
        </div>

        <!-- Budget Amount -->
        <div>
          <label for="budgetAmount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">RM</span>
            <input type="number" id="budgetAmount" name="amount" placeholder="0.00" required class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800 font-medium text-lg">
          </div>
        </div>

        <!-- Target Date -->
        <div>
          <label for="targetDate" class="block text-sm font-medium text-gray-700 mb-1">Target Date</label>
          <input type="date" id="targetDate" name="target_date" required class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800">
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-between space-x-4">
          <button type="button" onclick="window.location.href='savings.php'" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-red-400 text-white font-semibold hover:bg-red-500 transition">
            CANCEL
          </button>
          <button type="submit" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
            SAVE
          </button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
