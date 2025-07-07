<?php
$success = false;
// TODO: Fetch user and savings data from database
$user = [
    'first_name' => 'Rebecca',
    'type' => 'Premium User',
];
$savings = []; // TODO: Fetch from database
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
  <title>Savings</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts - Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>

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
    .new-saving-btn {
      background-image: linear-gradient(to right, #8e2de2, #4a00e0);
    }
    .new-saving-btn:hover {
      filter: brightness(1.1);
    }
  </style>
</head>

<body class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <!-- User Info -->
      <div class="flex items-center mb-8">
        <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" alt="Profile" class="rounded-full mr-3" />
        <div>
          <p class="text-sm font-medium text-gray-700">Hi, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
          <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user['type']); ?></p>
        </div>
      </div>

      <!-- Menu -->
      <button onclick="window.location.href='dashboard.php'" class="menu-btn w-full mb-4">☰ Dashboard</button>

      <!-- Navigation -->
      <nav class="nav-links">
        <a href="savings.php" class="active">⭐ Savings</a>
        <a href="editprofile.php">👤 Profile</a>
        <a href="statistic.php">📈 Statistics</a>
        <a href="budget.php">⬇ Budget</a>
        <a href="expenses.php">⬆ Expenses</a>
      </nav>
    </div>

    <!-- Logout button at the bottom -->
    <div>
      <button onclick="window.location.href='login.php'" class="logout-link">⏻ Log Out</button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Savings</h1>
        <p class="text-gray-600">Track your savings goals.</p>
      </div>
      <div class="flex items-center space-x-4 mt-4 md:mt-0">
        <span class="text-gray-700 font-medium"><?php echo date('F Y'); ?></span>
        <button class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-calendar-alt text-xl"></i>
        </button>
      </div>
    </header>

    <?php if ($success): ?>
      <div style="background:#d1fae5;color:#065f46;border-radius:0.7rem;padding:1rem;text-align:center;margin-bottom:1rem;">
        Savings goal added successfully!
      </div>
    <?php endif; ?>

    <!-- Savings Cards -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <?php if (empty($savings)): ?>
        <div class="lg:col-span-3 flex justify-center items-center py-10">
          <p class="text-gray-500 text-lg italic">No savings goals added yet. Click below to create your first one!</p>
        </div>
      <?php else: ?>
        <?php foreach ($savings as $saving): ?>
          <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($saving['title']); ?></h3>
            <p class="text-3xl font-bold text-green-600 mb-2">RM <?php echo number_format($saving['amount'], 2); ?></p>
            <p class="text-sm text-gray-500">Target: <?php echo htmlspecialchars($saving['target_date']); ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

    <!-- New Savings Button -->
    <section class="flex justify-center mt-8">
      <button onclick="toggleNewSavingModal()" class="w-full max-w-md py-4 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center gap-2 new-saving-btn">
        <i class="fas fa-plus-circle"></i>
        <span>New Savings</span>
      </button>
    </section>
  </main>

  <!-- New Saving Modal -->
  <div id="newSavingModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-xl w-full relative">
      <button onclick="toggleNewSavingModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add Saving Goal</h2>

      <form method="POST" action="" class="space-y-6">
        <!-- Saving For -->
        <div>
          <label for="savingFor" class="block text-sm font-medium text-gray-700 mb-1">Saving For</label>
          <input type="text" id="savingFor" name="saving_for" placeholder="e.g., New Laptop" required
                 class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800">
        </div>

        <!-- Budget Amount -->
        <div>
          <label for="budgetAmount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">RM</span>
            <input type="number" id="budgetAmount" name="amount" placeholder="0.00" required
                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800 font-medium text-lg">
          </div>
        </div>

        <!-- Target Date -->
        <div>
          <label for="targetDate" class="block text-sm font-medium text-gray-700 mb-1">Target Date</label>
          <input type="date" id="targetDate" name="target_date" required
                 class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800">
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-between space-x-4">
          <button type="button" onclick="toggleNewSavingModal()" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-red-400 text-white font-semibold hover:bg-red-500 transition">
            CANCEL
          </button>
          <button type="submit" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
            SAVE
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Script -->
  <script>
    function toggleNewSavingModal() {
      const modal = document.getElementById("newSavingModal");
      modal.classList.toggle("hidden");
    }
  </script>
</body>
</html>
