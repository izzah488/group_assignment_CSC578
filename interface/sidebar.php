

<aside class="sidebar">
  <div class="flex flex-col justify-start h-full">
    <div class="flex items-center mb-8">
      <img id="sidebarProfilePic" src="https://placehold.co/40x40/cbd5e1/000000?text=P" class="rounded-full mr-3" />
      <div>
        <p class="text-sm font-medium text-gray-700">Hi, <span id="sidebarName">Rebecca</span>!</p>
        <p class="text-xs text-gray-500">Premium User</p>
      </div>
    </div>

    <button onclick="window.location.href='dashboard.html'" class="menu-btn w-full mb-4">
      ☰ Dashboard
    </button>

    <nav class="nav-links mb-auto">
      <a href="savings.html">⭐️ Savings</a>
      <a href="profile.html">👤 Profile</a>
      <a href="statistic.html">📈 Statistics</a>
      <a href="budget.html">⬇️ Budget</a>
      <a href="expenses.html" class="active">⬆️ Expenses</a>
    </nav>

    <button onclick="window.location.href='home.html'" class="logout w-full mt-10">
      <i class="fa-solid fa-power-off"></i>
      Log Out
    </button>
  </div>
</aside>


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