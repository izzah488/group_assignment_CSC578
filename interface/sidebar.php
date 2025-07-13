<?php
// This file contains the HTML structure for the sidebar.
// It is intended to be included in other HTML/PHP files.
?>
<aside class="w-60 p-4 bg-white shadow-xl rounded-r-3xl flex flex-col justify-between fixed top-0 bottom-0 left-0 h-full z-10">
  <div>
    <!-- User Info -->
    <div class="flex items-center mb-6">
      <img id="sidebarProfilePic" src="https://placehold.co/40x40/cbd5e1/000000?text=P" class="rounded-full w-10 h-10 mr-3" />
      <div>
        <p id="sidebarName" class="text-sm font-medium text-gray-700">Hi, User!</p>
        
      </div>
    </div>

    <!-- Dashboard Button -->
    <button onclick="window.location.href='dashboard.php'" class="w-full bg-gradient-to-r from-purple-500 to-purple-700 text-white font-semibold py-2 px-3 rounded-lg mb-4 flex items-center justify-center gap-2">
      ☰ Dashboard
    </button>

    <!-- Nav Links -->
    <nav class="flex flex-col gap-2 mb-auto">
      <a href="savings.php" class="py-2 px-3 rounded-lg text-purple-700 hover:bg-purple-100 flex items-center gap-2">⭐️ Savings</a>
      <a href="profile.php" class="py-2 px-3 rounded-lg text-purple-700 hover:bg-purple-100 flex items-center gap-2 active">👤 Profile</a>
      <a href="budget.php" class="py-2 px-3 rounded-lg text-purple-700 hover:bg-purple-100 flex items-center gap-2">⬇️ Budget</a>
      <a href="expenses.php" class="py-2 px-3 rounded-lg text-purple-700 hover:bg-purple-100 flex items-center gap-2">⬆️ Expenses</a>
    </nav>
  </div>

  <!-- Logout Button -->
  <div>
    <button onclick="window.location.href='logout.php'" class="bg-red-500 hover:bg-red-600 text-white mt-10 font-semibold py-2 px-3 rounded-lg flex items-center gap-2">
      <i class="fas fa-sign-out-alt"></i> Log Out
    </button>
  </div>
</aside>
