<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Record</title>
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
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 16rem;
        background-color: #ffffff;
        border-top-right-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
        z-index: 10;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
        background-color: #e0b0ff;
      }
      .logout {
        background-color: #ef4444;
        color: white;
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
        background-color: #dc2626;
      }
      .primary-btn-gradient {
        background-image: linear-gradient(to right, #8e2de2, #4a00e0);
      }
      .primary-btn-gradient:hover {
        filter: brightness(1.1);
      }
      .no-data-message {
        text-align: center;
        color: #6b7280;
        font-style: italic;
        padding: 1rem;
      }
      .chart-placeholder {
        width: 250px;
        height: 250px;
        background-color: #cbd5e1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5568;
        font-size: 0.9rem;
        text-align: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
      }
    </style>
</head>
<body class="bg-gray-100">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="flex flex-col justify-between h-full">
      <div>
        <div class="flex items-center mb-8">
          <img src="https://placehold.co/40x40/FF69B4/FFFFFF?text=R" alt="Profile Picture" class="rounded-full mr-3" />
          <div>
            <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
            <p class="text-xs text-gray-500">Premium User</p>
          </div>
        </div>
        <button onclick="window.location.href='dashboard.html'" class="w-full menu-btn mb-4">☰ Dashboard</button>
        <nav class="nav-links mb-auto">
          <a href="savings.html">⭐️ Savings</a>
          <a href="profile.html">👤 Profile</a>
          <a href="statistic.html">📈 Statistics</a>
          <a href="budget.html">⬇️ Budget</a>
          <a href="expenses.html">⬆️ Expenses</a>
        </nav>
      </div>
      <!--button onclick="window.location.href='logout.php'" class="logout w-full mt-10"-->
        <i class="fas fa-sign-out-alt"></i> Log Out
      </button>
    </div>
  </aside>
  <?include 'sidebar.php'; ?>
  <!-- Main Content Area -->
  <main class="ml-64 min-h-screen flex flex-col items-center justify-start p-8">
    <div class="w-full max-w-2xl">
      <div class="flex items-center mb-8">
        <a href="#" class="text-gray-500 hover:text-gray-700 mr-4">
          <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Budget Record</h1>
          <p class="text-gray-600">View past budget.</p>
        </div>
      </div>
      <!-- Budget Record Table -->
      <div class="bg-white p-8 rounded-2xl shadow-lg w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Monthly Budget History</h2>
        <div class="grid grid-cols-2 gap-y-4 text-gray-800 font-medium text-lg">
          <div class="col-span-1">March</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">February</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">January</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">December</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">November</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">October</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">September</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">August</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">July</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">June</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">May</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
          <div class="col-span-1">April</div>
          <div class="col-span-1 text-right">RM 2000.00</div>
        </div>
        <p class="text-gray-500 text-sm mt-8 text-center">Record will be permanently erased after 12 months.</p>
      </div>
    </div>
  </main>
</body>