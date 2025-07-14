<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../dbconnection.php'; // Assuming you have a db_connection.php file

$userID = $_SESSION['userID'];
$userData = null;

// Fetch user data from the database
try {
    $stmt = $pdo->prepare("SELECT fName, lName, email, proPic FROM users WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log error or display a user-friendly message
    error_log("Error fetching user data: " . $e->getMessage());
    // Optionally redirect or show an error
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

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

    .logout-link {
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

    .logout-link:hover {
      background-color: #dc2626;
    }

    .main-content {
      margin-left: 16rem;
      flex: 1;
      padding: 2rem;
    }

    .profile-card {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 0 auto;
    }

    .profile-field {
      margin-bottom: 1.5rem;
    }

    .profile-field label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: #4a5568;
      margin-bottom: 0.5rem;
    }

    .profile-field input[type="text"],
    .profile-field input[type="email"],
    .profile-field input[type="password"] {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #e2e8f0;
      border-radius: 0.5rem;
      background-color: #f9fafb;
      font-size: 1rem;
      color: #2d3748;
      pointer-events: none; /* Make inputs read-only visually */
    }

    .profile-field input[readonly] {
      background-color: #edf2f7;
      cursor: default;
    }
  </style>
</head>
<body class="flex min-h-screen">
  <?php include 'sidebar.php'; ?>

  <main class="main-content">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Profile</h1>
    <p class="text-gray-600 mb-8">View your profile information.</p>

    <div class="profile-card">
      <div class="flex flex-col items-center mb-8">
        <img id="profilePageImage" src="<?php echo htmlspecialchars($userData['proPic'] ?? 'https://placehold.co/100x100/cbd5e1/000000?text=P'); ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover border-4 border-purple-300 shadow-lg mb-4" />
        <h2 id="profilePageName" class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars(($userData['fName'] ?? 'User') . ' ' . ($userData['lName'] ?? 'Name')); ?></h2>
      </div>

      <div class="profile-field">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="fName" value="<?php echo htmlspecialchars($userData['fName'] ?? ''); ?>" readonly />
      </div>
      <div class="profile-field">
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" name="lName" value="<?php echo htmlspecialchars($userData['lName'] ?? ''); ?>" readonly />
      </div>
      <div class="profile-field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" readonly />
      </div>
      <div class="profile-field flex justify-between items-center">
        <div class="flex-grow">
          <label for="password">Current Password</label>
          <input type="password" id="password" name="currentPassword" value="********" readonly />
        </div>
      </div>

      <button onclick="window.location.href='editprofile.php'" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg mt-6 transition-colors duration-200">
        Edit Profile
      </button>
    </div>
  </main>

  <script>
    // This script block is largely simplified as data comes from PHP now.
    // However, if sidebar.php also needs dynamic data, you might pass it
    // via a global JS variable or refactor sidebar to also get data from PHP.
    // For now, we'll assume sidebar.php has its own way to get data, or it's static.

    // Example for sidebar (if not handled by sidebar.php directly):
    // const sidebarName = document.getElementById('sidebarName');
    // const sidebarProfilePic = document.getElementById('sidebarProfilePic');
    // if (sidebarName && sidebarProfilePic) {
    //   sidebarName.textContent = `Hi, <?php echo htmlspecialchars($userData['fName'] ?? 'User'); ?>!`;
    //   sidebarProfilePic.src = `<?php echo htmlspecialchars($userData['proPic'] ?? 'https://placehold.co/40x40/cbd5e1/000000?text=P'); ?>`;
    // }
  </script>
</body>
</html>