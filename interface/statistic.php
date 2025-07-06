<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Statistics</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="profile.css">
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white p-6 flex flex-col justify-between rounded-r-2xl shadow-lg">
        <div>
            <!-- User Info -->
            <div class="flex items-center mb-8">
                <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" alt="Profile Picture" class="rounded-full mr-3">
                <div>
                    <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav>
                <ul>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <i class="fas fa-th-large mr-3 text-lg"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <i class="fas fa-star mr-3 text-lg"></i>
                            Savings
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <i class="fas fa-user mr-3 text-lg"></i>
                            Profile
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-3 bg-black text-white rounded-lg shadow-md">
                            <i class="fas fa-chart-line mr-3 text-lg"></i>
                            Statistics
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <i class="fas fa-wallet mr-3 text-lg"></i>
                            Budget
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <i class="fas fa-arrow-up mr-3 text-lg"></i>
                            Expenses
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Log Out Button -->
        <div class="mt-auto">
            <button class="w-full bg-red-500 text-white py-3 px-4 rounded-xl shadow-lg hover:bg-red-600 transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2 text-lg"></i>
                Log Out
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-8">
        <h1 class="text-3xl font-semibold text-gray-800 mb-2">Statistics</h1>
        <p class="text-gray-600 mb-8">Track your monthly budget, expenses and balance.</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Money Overview Cards -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Money Budget Card -->
                <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Budget</h2>
                        <p class="text-2xl font-bold text-gray-900">RM 2500</p>
                        <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
                            <div class="bg-purple-500 h-2.5 rounded-full" style="width: 70%;"></div>
                        </div>
                    </div>
                    <!-- Removed edit icon -->
                </div>

                <!-- Money Expenses Card -->
                <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Expenses</h2>
                        <p class="text-2xl font-bold text-gray-900">RM 2000</p>
                        <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: 80%;"></div>
                        </div>
                    </div>
                    <!-- Removed add icon -->
                </div>

                <!-- Money Balance Card -->
                <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Money Balance</h2>
                        <p class="text-2xl font-bold text-gray-900">RM 500</p>
                        <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
                            <div class="bg-pink-500 h-2.5 rounded-full" style="width: 20%;"></div>
                        </div>
                    </div>
                    <!-- No icon for balance in the image -->
                </div>
            </div>

            <!-- Right Column: Monthly Spends Chart & Top Expenses -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Monthly Spends Chart -->
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Monthly Spends</h2>
                    <!-- Placeholder for the chart -->
                    <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
                        <img src="https://placehold.co/250x150/e0e0e0/000000?text=Chart+Placeholder" alt="Monthly Spends Chart" class="max-w-full h-auto rounded-lg">
                    </div>
                </div>

                <!-- Top Money Expenses -->
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Money Expenses</h2>
                    <ul class="space-y-3">
                        <li class="flex justify-between items-center text-gray-700">
                            <span>Food</span>
                            <span class="font-medium">RM 150</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700">
                            <span>Bills</span>
                            <span class="font-medium">RM 90</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700">
                            <span>Transport</span>
                            <span class="font-medium">RM 50</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700">
                            <span>Top Up</span>
                            <span class="font-medium">RM 30</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
