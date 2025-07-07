<?php
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = htmlspecialchars($_POST['category'] ?? '');
    $amount = htmlspecialchars($_POST['amount'] ?? '');
    $date = htmlspecialchars($_POST['date'] ?? '');
    // TODO: Save to database
    $success = true;
}
// TODO: Fetch user, budget, categories, and spending data from the database
$user = [
    'first_name' => 'Rebecca',
    'type' => 'Premium User',
];
$totalBudget = 2500;
$budgetUsed = 1800;
$categories = [
    ['name' => 'Food', 'used' => 300, 'limit' => 500, 'color' => '#8e2de2'],
    ['name' => 'Transport', 'used' => 100, 'limit' => 200, 'color' => '#fbbf24'],
    ['name' => 'Shopping', 'used' => 200, 'limit' => 400, 'color' => '#ec4899'],
    ['name' => 'Utilities', 'used' => 400, 'limit' => 500, 'color' => '#3b82f6'],
];
$moneyGoal = 2000;
$moneyBalance = 500;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget</title>
    <!-- Tailwind CSS CDN -->
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
        }
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 2.5rem;
        }
        .avatar {
            width: 2.7rem;
            height: 2.7rem;
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
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                flex-direction: row;
                width: 100%;
                border-radius: 0 0 2rem 2rem;
                margin-bottom: 2rem;
            }
            .main-content {
                padding: 2rem 1rem 1rem 1rem;
            }
            .card {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div>
                <div class="user-info">
                    <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" alt="Profile Picture" class="avatar">
                    <div>
                        <p class="text-base font-semibold text-gray-700">Hi, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user['type']); ?></p>
                    </div>
                </div>
                <button onclick="location.href='dashboard.php'" class="menu-btn">☰ Dashboard</button>
                <nav class="nav-links">
                    <a href="savings.php">⭐ Savings</a>
                    <a href="editprofile.php">👤 Profile</a>
                    <a href="statistic.php">📈 Statistics</a>
                    <a href="budget.php" class="active">⬇ Budget</a>
                    <a href="expenses.php">⬆ Expenses</a>
                </nav>
            </div>
            <button onclick="location.href='index.php'" class="logout">⏻ Log Out</button>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-1">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 w-full">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-1 tracking-tight">Budget</h1>
                    <p class="text-gray-600 text-lg">Plan and track your monthly budget.</p>
                </div>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <span class="text-gray-700 font-medium"><?php echo date('F Y'); ?></span>
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 w-full">
                <!-- Total Budget Card -->
                <div class="card flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Total Budget</h2>
                        <p class="text-3xl font-bold text-purple-600">RM <?php echo number_format($totalBudget, 0); ?></p>
                        <p class="text-sm text-gray-500 mt-2">Overall monthly limit</p>
                    </div>
                    <button onclick="location.href='edit_money_budget.php'" class="edit-btn">
                        Edit
                    </button>
                </div>

                <!-- Budget Used Card -->
                <div class="card flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Budget Used</h2>
                        <p class="text-3xl font-bold text-red-600">RM <?php echo number_format($budgetUsed, 0); ?></p>
                        <p class="text-sm text-gray-500 mt-2">Spent this month</p>
                    </div>
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2m-6 4h6a2 2 0 002-2v-5a2 2 0 00-2-2h-6a2 2 0 00-2 2v5a2 2 0 002 2z" />
                    </svg>
                </div>
            </section>

            <?php if ($success): ?>
              <div style="background:#d1fae5;color:#065f46;border-radius:0.7rem;padding:1rem;text-align:center;margin-bottom:1rem;">
                Budget record added successfully!
              </div>
            <?php endif; ?>
            <form method="POST" action="" style="background:#f9f9ff; border-radius:1.5rem; padding:2rem; box-shadow:0 4px 16px 0 rgba(138,43,226,0.08); max-width:400px; margin:2rem auto;">
              <select name="category" required style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
                <option disabled selected value="">Category</option>
                <?php foreach ($categories as $cat): ?>
                  <option><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
                <option>Other</option>
              </select>
              <input type="number" name="amount" placeholder="Amount (RM)" required style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
              <input type="date" name="date" required style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
              <button type="submit" style="background:linear-gradient(90deg,#a259ff 0%,#6a11cb 100%);color:#fff;font-weight:600;border-radius:1rem;padding:0.7rem 2rem;width:100%;">Add Record</button>
            </form>

            <section class="card w-full mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Budget Categories</h2>
                <div class="space-y-6">
                    <?php foreach ($categories as $cat):
                      $percent = $cat['limit'] > 0 ? min(100, ($cat['used']/$cat['limit'])*100) : 0;
                    ?>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700"><?php echo htmlspecialchars($cat['name']); ?></h3>
                            <p class="text-sm text-gray-500">RM <?php echo number_format($cat['used'], 0); ?> / RM <?php echo number_format($cat['limit'], 0); ?></p>
                            <div class="progress-bar-bg">
                                <div class="progress-bar" style="width: <?php echo $percent; ?>%; background: <?php echo $cat['color']; ?>;"></div>
                            </div>
                        </div>
                        <button class="edit-btn" style="background: #f3e8ff; color: <?php echo $cat['color']; ?>;">Add</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 w-full">
                <!-- Money Goal Card -->
                <div class="card flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Goal</h2>
                        <p class="text-lg font-bold text-gray-900">RM <?php echo number_format($moneyGoal, 0); ?></p>
                        <div class="progress-bar-bg">
                            <div class="progress-bar" style="width: 80%; background: #22c55e;"></div>
                        </div>
                    </div>
                    <button class="edit-btn" style="background: #f3e8ff; color: #22c55e;">Add</button>
                </div>

                <!-- Money Balance Card -->
                <div class="card flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Balance</h2>
                        <p class="text-lg font-bold text-gray-900">RM <?php echo number_format($moneyBalance, 0); ?></p>
                        <div class="progress-bar-bg">
                            <div class="progress-bar" style="width: 20%; background: #ec4899;"></div>
                        </div>
                    </div>
                </div>
            </section>

            <button onclick="location.href='add_money_expenses.php'" class="new-expenses-btn">
                + Add New Expenses
            </button>

            <button onclick="location.href='budget_record.php'" class="view-records-btn">
                View Full Records
            </button>
        </main>
    </div>
</body>
</html>


