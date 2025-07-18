<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Budget</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .nav-links a, .nav-links button.logout-link {
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
        .nav-links a:hover,
        .nav-links button.logout-link:hover {
            background: linear-gradient(90deg, #e0b0ff 0%, #f3e8ff 100%);
            color: #4a00e0;
        }
        .logout-link {
            background: linear-gradient(90deg, #fbd38d 0%, #f6ad55 100%);
            color: #c05621;
            margin-top: 1rem;
        }
        .logout-link:hover {
            filter: brightness(1.08);
        }
        .main-content {
            flex: 1;
            padding: 3.5rem 2rem 2rem 2rem;
            margin-left: 16.5rem;
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
    </style>
</head>
<body class="flex min-h-screen">
  <?php require_once  'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <header class="flex items-center mb-8">
            <button onclick="window.history.back()" class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </button>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Money Budget</h1>
                <p class="text-gray-600">Edit your money budget.</p>
            </div>
        </header>

        <div class="form-card">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Money Budget</h2>

            <div class="space-y-6">
                <!-- Budget Limit Input -->
                <div>
                    <form method="POST" action="api/update_budget.php" id="budgetForm">
                    <label for="budgetLimit" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">RM</span>
                        <input type="number" id="budgetLimit" value="0"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-gray-800 font-medium text-lg"
                               placeholder="Enter amount">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">The money budget limit is RM 2,500.00.</p>
                </div>

                <!-- Month Dropdown -->
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select id="month"
                            class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-gray-800">
                        <option value="january">January</option>
                        <option value="february">February</option>
                        <option value="march" selected>March</option>
                        <option value="april">April</option>
                        <option value="may">May</option>
                        <option value="june">June</option>
                        <option value="july">July</option>
                        <option value="august">August</option>
                        <option value="september">September</option>
                        <option value="october">October</option>
                        <option value="november">November</option>
                        <option value="december">December</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">This budget is for March only.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-between space-x-4">
                <button onclick="window.location.href='budget.php'" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-red-400 text-white font-semibold hover:bg-red-500 transition-colors duration-200">
                    CANCEL
                </button>
                <button class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors duration-200">
                    SAVE
                </button>
            </div>
        </div>
    </main>

</body>
</html>
<!-- End of code snippet -->