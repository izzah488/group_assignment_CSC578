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
        .form-card {
            background-color: white;
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            max-width: 36rem;
            margin: 0 auto;
            position: relative;
        }
    </style>
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
                    <p class="text-xs text-gray-500">Premium User</p>
                </div>
            </div>
            <!-- Dashboard Button -->
            <button onclick="window.location.href='dashbord.html'" class="menu-btn w-full mb-4">☰ Dashboard</button>
            <!-- Navigation Links -->
            <nav class="nav-links flex flex-col space-y-2">
                <a href="savings.html">⭐ Savings</a>
                <a href="editprofile.html">👤 Profile</a>
                <a href="statistics.html">📈 Statistics</a>
                <a href="budget.html" class="active">⬇ Budget</a>
                <a href="expenses.html">⬆ Expenses</a>
            </nav>
        </div>
        <!-- Logout Button -->
        <button onclick="window.location.href='home.html'" class="logout w-full">⏻ Log Out</button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
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
                    <label for="budgetLimit" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">RM</span>
                        <input type="number" id="budgetLimit" value="2500.00"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-gray-800 font-medium text-lg"
                               placeholder="Enter amount" readonly>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">The money budget limit is RM 2,500.00.</p>
                </div>

                <!-- Month Dropdown -->
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select id="month"
                            class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-gray-800" disabled>
                        <option value="march" selected>March</option>
                        <option value="april">April</option>
                        <option value="may">May</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">This budget is for March only.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-between space-x-4">
                <button onclick="window.location.href='budget.html'" class="flex-1 py-3 px-4 rounded-xl shadow-lg bg-red-400 text-white font-semibold hover:bg-red-500 transition-colors duration-200">
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

edit profie
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
  <link rel="stylesheet" href="editprofile.css" />
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="user-info">
        <img src="avatar.png" alt="User Avatar" class="avatar">
        <p>Hi, Rebecca!</p>
      </div>
      <button class="menu-btn">☰ Dashboard</button>
      <nav class="nav-links">
        <a href="#">⭐ Savings</a>
        <a class="active" href="#">👤 Profile</a>
        <a href="#">📈 Statistics</a>
        <a href="#">⬇ Budget</a>
        <a href="#">⬆ Expenses</a>
      </nav>
      <button class="logout">⏻ Log Out</button>
    </aside>

    <main class="main-content">
      <h1>Edit Profile</h1>
      <p class="subtitle">View and edit your profile, change settings, and manage your data.</p>

      <form class="profile-form">
        <label>First Name:
          <input type="text" value="Rebecca" />
        </label>
        <label>Last Name:
          <input type="text" value="Louis" />
        </label>
        <label>Email:
          <input type="email" value="rebecca@gmail.com" />
        </label>
        <label>Current Password:
          <input type="password" value="********" />
        </label>
        <label>New Password:
          <input type="password" />
        </label>
        <label>Confirm Password:
          <input type="password" />
        </label>

        <div class="form-buttons">
          <button type="button" class="cancel-btn">Cancel</button>
          <button type="submit" class="save-btn" onclick="showSuccessModal()">Save</button>
        </div>
      </form>
    </main>
  </div>
</body>
</html>