<?php // editprofile.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="flex min-h-screen bg-gradient-to-br from-gray-100 to-purple-100 font-[Inter]">
  <?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <main class="flex-grow p-8 ml-72">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Edit Profile</h1>
    <p class="text-gray-600 mb-8">Update your personal information and preferences here.</p>

    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-2xl mx-auto">
      <div class="flex flex-col items-center mb-8">
        <label for="profileImageInput" class="cursor-pointer">
          <img id="previewImage" src="https://placehold.co/100x100/cbd5e1/000000?text=P" alt="Profile Picture" class="w-28 h-28 rounded-full object-cover border-4 border-purple-400 shadow-lg mb-4" />
          <span class="block text-center text-purple-600 hover:text-purple-800 font-medium">Change Profile Picture</span>
        </label>
        <input type="file" id="profileImageInput" accept="image/*" class="hidden" onchange="previewImage(event)">
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
          <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
          <input type="text" id="firstName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter your first name">
        </div>
        <div>
          <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
          <input type="text" id="lastName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter your last name">
        </div>
        <div class="md:col-span-2">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="you@example.com">
        </div>
        <div class="md:col-span-2">
          <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
          <input type="password" id="currentPassword" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter current password">
        </div>
        <div class="md:col-span-2">
          <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
          <input type="password" id="newPassword" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all duration-200" placeholder="Enter new password (min 6 characters)">
        </div>
      </div>

      <div class="flex justify-end space-x-4">
        <button onclick="window.location.href='profile.html'" class="px-6 py-3 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-colors duration-200">
          CANCEL
        </button>
        <button onclick="showSuccessModal()" class="px-6 py-3 rounded-xl bg-purple-600 text-white font-semibold hover:bg-purple-700 transition-colors duration-200">
          SAVE CHANGES
        </button>
      </div>
    </div>
  </main>

  <!-- Success Modal -->
  <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-xl text-center">
      <div class="text-green-500 text-6xl mb-4">
        <i class="fas fa-check-circle"></i>
      </div>
      <p class="text-lg font-semibold text-gray-800 mb-4">Saved successfully!</p>
      <button onclick="hideSuccessModalAndRedirect()" class="bg-gray-200 px-4 py-2 rounded-lg">Done</button>
    </div>
  </div>

  <!-- Scripts -->
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
      const firstName = document.getElementById('firstName').value;
      const lastName = document.getElementById('lastName').value;
      const email = document.getElementById('email').value;
      const imageSrc = document.getElementById('previewImage').src;

      // Save to localStorage
      const userData = {
          firstName: firstName,
          lastName: lastName,
          email: email,
          profilePic: imageSrc
      };
      localStorage.setItem('userData', JSON.stringify(userData));

      document.getElementById('successModal').classList.remove('hidden');
    }

    function hideSuccessModalAndRedirect() {
      document.getElementById('successModal').classList.add('hidden');
      window.location.href = 'profile.html';
    }

    // Load sidebar info and profile data from localStorage
    window.addEventListener('DOMContentLoaded', () => {
      const userDataString = localStorage.getItem('userData');
      if (userDataString) {
        const userData = JSON.parse(userDataString);

        // Populate sidebar
        document.getElementById('sidebarName').textContent = `Hi, ${userData.firstName}!`;
        document.getElementById('sidebarProfilePic').src = userData.profilePic || "https://placehold.co/40x40/cbd5e1/000000?text=P";

        // Populate edit profile form
        document.getElementById('firstName').value = userData.firstName;
        document.getElementById('lastName').value = userData.lastName;
        document.getElementById('email').value = userData.email;
        document.getElementById('previewImage').src = userData.profilePic || "https://placehold.co/100x100/cbd5e1/000000?text=P";
      } else {
        // Default values if no user data is found
        document.getElementById('sidebarName').textContent = 'Hi, User!';
        document.getElementById('sidebarProfilePic').src = "https://placehold.co/40x40/cbd5e1/000000?text=P";
        document.getElementById('previewImage').src = "https://placehold.co/100x100/cbd5e1/000000?text=P";
      }
    });
  </script>
</body>
</html>
