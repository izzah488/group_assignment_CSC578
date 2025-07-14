<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

require_once '../dbconnection.php'; // Include your database connection

$userID = $_SESSION['userID'];
$userData = null;

// Fetch user data from the database to pre-populate the form
try {
    $stmt = $pdo->prepare("SELECT fName, lName, email, proPic FROM users WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching user data for edit: " . $e->getMessage());
    // Optionally, handle this error for the user
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
        const output = document.getElementById('previewImage');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }

    function showSuccessModal() {
      document.getElementById('successModal').classList.remove('hidden');
    }

    function hideSuccessModalAndRedirect() {
      document.getElementById('successModal').classList.add('hidden');
      window.location.href = 'profile.php';
    }

    function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorModal').classList.remove('hidden');
    }

    function hideErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
    }

    // This script runs after the form submission and checks for a URL parameter
    // indicating success or failure.
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('status')) {
            if (urlParams.get('status') === 'success') {
                showSuccessModal();
            } else if (urlParams.get('status') === 'error') {
                const message = urlParams.get('message') || 'An unknown error occurred.';
                showErrorModal(decodeURIComponent(message));
            }
        }
    });
  </script>
</head>
<body class="flex min-h-screen bg-gradient-to-br from-gray-100 to-purple-100 font-[Inter]">
  <?php include 'sidebar.php'; ?>

  <main class="flex-grow p-8 ml-72">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Edit Profile</h1>
    <p class="text-gray-600 mb-8">Update your personal information and preferences here.</p>

    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-2xl mx-auto">
      <form id="editProfileForm" action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="flex flex-col items-center mb-8">
          <label for="profileImageInput" class="cursor-pointer">
            <img id="previewImage" src="<?php echo htmlspecialchars($userData['proPic'] ?? 'https://placehold.co/100x100/cbd5e1/000000?text=P'); ?>" alt="Profile Picture" class="w-28 h-28 rounded-full object-cover border-4 border-purple-400 shadow-lg mb-4" />
            <span class="block text-center text-purple-600 hover:text-purple-800 font-medium">Change Profile Picture</span>
          </label>
          <input type="file" id="profileImageInput" name="profileImage" accept="image/*" class="hidden" onchange="previewImage(event)">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div>
            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
            <input type="text" id="firstName" name="fName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter your first name" value="<?php echo htmlspecialchars($userData['fName'] ?? ''); ?>">
          </div>
          <div>
            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
            <input type="text" id="lastName" name="lName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter your last name" value="<?php echo htmlspecialchars($userData['lName'] ?? ''); ?>">
          </div>
          <div class="md:col-span-2">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="you@example.com" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>">
          </div>
          <div class="md:col-span-2">
            <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password(required)</label>
            <input type="password" id="currentPassword" name="currentPassword" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter current password">
          </div>
          <div class="md:col-span-2">
            <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password (required)</label>
            <input type="password" id="newPassword" name="newPassword" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter new password (min 6 characters)">
          </div>
        </div>

        <div class="flex justify-end space-x-4">
          <button type="button" onclick="window.location.href='profile.php'" class="px-6 py-3 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-colors duration-200">
            CANCEL
          </button>
          <button type="submit" class="px-6 py-3 rounded-xl bg-purple-600 text-white font-semibold hover:bg-purple-700 transition-colors duration-200">
            SAVE CHANGES
          </button>
        </div>
      </form>
    </div>
  </main>

  <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-xl text-center">
      <div class="text-green-500 text-6xl mb-4">
        <i class="fas fa-check-circle"></i>
      </div>
      <p class="text-lg font-semibold text-gray-800 mb-4">Profile updated successfully!</p>
      <button onclick="hideSuccessModalAndRedirect()" class="bg-gray-200 px-4 py-2 rounded-lg">Done</button>
    </div>
  </div>

  <div id="errorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-xl text-center">
      <div class="text-red-500 text-6xl mb-4">
        <i class="fas fa-exclamation-circle"></i>
      </div>
      <p id="errorMessage" class="text-lg font-semibold text-gray-800 mb-4">An error occurred.</p>
      <button onclick="hideErrorModal()" class="bg-gray-200 px-4 py-2 rounded-lg">Dismiss</button>
    </div>
  </div>

</body>
</html>