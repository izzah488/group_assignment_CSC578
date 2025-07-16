<?php
// This file contains the HTML structure for the sidebar.
// It is intended to be included in other HTML/PHP files.

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define default values for user profile data
$userName = "Guest";
$userLastName = "";
$userImage = "https://placehold.co/40x40/cbd5e1/000000?text=P"; // Default image

// Fetch user data only if a user is logged in
if (isset($_SESSION['userID'])) {
    // Include your configuration and database connection files
    // Adjust paths if sidebar.php is in a different directory relative to config.php/dbconnection.php
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../dbconnection.php';

    global $pdo; // Access the global database handle

    $userId = $_SESSION['userID'];

    try {
        $sql = "SELECT fName, lName, proPic FROM users WHERE userID = :userId";
        $query = $pdo->prepare($sql);
        $query->bindParam(':userId', $userId, PDO::PARAM_INT);
        $query->execute();
        $userProfile = $query->fetch(PDO::FETCH_ASSOC);

        if ($userProfile) {
            $userName = htmlspecialchars($userProfile['fName']);
            $userLastName = htmlspecialchars($userProfile['lName']);
            if ($userProfile['proPic']) {
                $userImage = htmlspecialchars($userProfile['proPic']);
            }
        }
    } catch (PDOException $e) {
        // Log the error but continue with default values to prevent page breakage
        error_log("Error fetching user profile in sidebar: " . $e->getMessage());
    }
}
?>
<aside id="main-sidebar" class="sidebar w-64 p-6 bg-white shadow-md rounded-r-3xl flex flex-col justify-between fixed top-0 bottom-0 left-0 h-full z-10">
  <div>
    <!-- User Info -->
    <div class="flex items-center mb-6">
      <img id="sidebarProfilePic" src="<?= $userImage ?>" class="rounded-full w-10 h-10 mr-3 object-cover" />
      <div>
        <p id="sidebarName" class="text-sm font-medium text-gray-700">Hi, <?= $userName ?> <?= $userLastName ?>!</p>
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
