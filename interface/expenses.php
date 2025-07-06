<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Chart</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }
        .sidebar {
            background-color: #ffffff;
            border-top-right-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
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
        .nav-links a {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            color: #4a00e0;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: background-color 0.2s ease-in-out;
        }
        .nav-links a.active,
        .nav-links a:hover {
            background-color: #e0b0ff; /* Light purple for active/hover */
        }
        .logout {
            background-color: #fbd38d; /* Light orange */
            color: #c05621; /* Darker orange text */
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background-color 0.2s ease-in-out;
        }
        .logout:hover {
            background-color: #f6ad55; /* Slightly darker orange on hover */
        }
        /* Custom styles for the pie chart colors */
        .pie-chart-legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .pie-chart-legend-color {
            width: 1.25rem; /* 20px */
            height: 1.25rem; /* 20px */
            border-radius: 0.25rem; /* 4px */
            margin-right: 0.5rem; /* 8px */
        }
        /* Specific colors from the image */
        .color-food { background-color: #a78bfa; /* Purple-400 */ }
        .color-transport { background-color: #fcd34d; /* Yellow-300 */ }
        .color-bill { background-color: #2dd4bf; /* Teal-400 */ }
        .color-topup { background-color: #4ade80; /* Green-400 */ }
        .color-entertainment { background-color: #60a5fa; /* Blue-400 */ }

        .expense-category-header {
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 1.125rem; /* text-lg */
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            margin-top: 1.5rem;
        }
        .expense-category-list {
            padding: 1rem 1.5rem;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .expense-category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb; /* gray-200 */
        }
        .expense-category-item:last-child {
            border-bottom: none;
        }
        .chart-placeholder {
            width: 250px;
            height: 250px;
            background-color: #cbd5e1; /* Placeholder color */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4a5568;
            font-size: 0.9rem;
            text-align: center;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
        }
        .add-expenses-btn {
            background-image: linear-gradient(to right, #8e2de2, #4a00e0);
        }
        .add-expenses-btn:hover {
            filter: brightness(1.1);
        }
        .no-data-message {
            text-align: center;
            color: #6b7280; /* gray-500 */
            font-style: italic;
            padding: 1rem;
        }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white p-6 flex flex-col justify-between rounded-r-3xl shadow-lg">
        <div>
            <!-- User Info -->
            <div class="flex items-center mb-8">
                <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" alt="Profile Picture" class="rounded-full mr-3">
                <div>
                    <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
                    <p class="text-xs text-gray-500">Premium User</p>
                </div>
            </div>
            <!-- Dashboard Button -->
            <button onclick="window.location.href='dashbord.html'" class="menu-btn w-full mb-4">☰ Dashboard</button>
            <!-- Navigation Links -->
            <nav class="nav-links flex flex-col space-y-2">
                <a href="savings.html">⭐ Savings</a>
                <a href="profile.html">👤 Profile</a>
                <a href="statistics.html">📈 Statistics</a>
                <a href="budget.html">⬇ Budget</a>
                <a href="expenses.html" class="active">⬆ Expenses</a>
            </nav>
        </div>
        <!-- Logout Button -->
        <button onclick="window.location.href='home.html'" class="logout w-full">⏻ Log Out</button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
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

        <section class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Expenses Chart (March)</h2>
            <div class="flex flex-col md:flex-row items-center justify-around">
                <!-- Chart Placeholder -->
                <div class="chart-placeholder flex-shrink-0 mb-6 md:mb-0">
                    No data to display.
                </div>

                <!-- Legend -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="pie-chart-legend-item">
                        <span class="pie-chart-legend-color color-food"></span>
                        Food
                    </div>
                    <div class="pie-chart-legend-item">
                        <span class="pie-chart-legend-color color-transport"></span>
                        Transport
                    </div>
                    <div class="pie-chart-legend-item">
                        <span class="pie-chart-legend-color color-bill"></span>
                        Bill
                    </div>
                    <div class="pie-chart-legend-item">
                        <span class="pie-chart-legend-color color-topup"></span>
                        Top Up
                    </div>
                    <div class="pie-chart-legend-item">
                        <span class="pie-chart-legend-color color-entertainment"></span>
                        Entertainment
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Expense Categories</h2>

            <!-- Food Category -->
            <div class="expense-category-header bg-purple-500 text-white shadow-md">Food</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

            <!-- Transport Category -->
            <div class="expense-category-header bg-yellow-500 text-gray-800 shadow-md">Transport</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

            <!-- Bill Category -->
            <div class="expense-category-header bg-teal-500 text-white shadow-md">Bill</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

            <!-- Top Up Category -->
            <div class="expense-category-header bg-green-500 text-white shadow-md">Top Up</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>

            <!-- Entertainment Category -->
            <div class="expense-category-header bg-blue-500 text-white shadow-md">Entertainment</div>
            <div class="expense-category-list bg-white shadow-md rounded-b-lg">
                <div class="no-data-message">No expenses in this category yet.</div>
            </div>
        </section>

        <!-- Add New Expenses Button -->
        <button onclick="window.location.href='add_money_expenses.html'" class="mt-6 w-full py-4 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center space-x-2 add-expenses-btn">
            <i class="fas fa-plus-circle"></i>
            <span>Add New Expenses</span>
        </button>
    </main>

</body>
</html>

