<?php // signup.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Money Mate - Sign Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .nav-link {
        position: relative;
        transition: all 0.3s ease;
        color: #fff !important;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 0;
        height: 2px;
        background: white;
        transition: width 0.3s ease;
    }
    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }
  </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-100">
  <?php include 'navbar.php'; ?>

  <main class="flex-grow flex items-center justify-center p-6">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
      <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Sign Up</h1>
      <form id="signupForm" onsubmit="handleSignup(event)">
        <div class="mb-6 text-center">
          <label for="profilePic" class="cursor-pointer">
            <img id="previewImage" src="https://placehold.co/100x100?text=Preview" alt="Profile Preview"
                 class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-indigo-300 shadow-md mb-3">
            <span class="text-indigo-600 hover:text-indigo-800 font-medium">Upload Profile Picture</span>
          </label>
          <input type="file" id="profilePic" name="profilePic" accept="image/*" class="hidden">
        </div>

        <div class="mb-4">
          <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
          <input type="text" id="firstName" name="firstName" placeholder="Enter your first name"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
          <input type="text" id="lastName" name="lastName" placeholder="Enter your last name"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" id="email" name="email" placeholder="you@example.com"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" id="password" name="password" placeholder="Minimum 6 characters"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required minlength="6">
        </div>

        <div class="mb-6">
          <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 rounded-lg font-semibold shadow-md hover:from-blue-600 hover:to-blue-800 transition-all duration-300">
          Sign-Up to Money Mate
        </button>
      </form>
    </div>
  </main>

  <script>
    // Profile picture preview
    document.getElementById('profilePic').addEventListener('change', function (e) {
      const file = e.target.files[0];
      const previewImage = document.getElementById('previewImage');

      if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
          previewImage.src = event.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        previewImage.src = "https://placehold.co/100x100?text=Preview";
      }
    });

    function handleSignup(event) {
      event.preventDefault(); // Prevent default form submission

      const firstName = document.getElementById('firstName').value;
      const lastName = document.getElementById('lastName').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const profilePicInput = document.getElementById('profilePic');
      let profilePicDataUrl = document.getElementById('previewImage').src; // Get current preview image src

      if (password !== confirmPassword) {
        alert("Passwords do not match!"); // Using alert as per original code, consider custom modal for better UX
        return;
      }

      // Check if a new image was selected, otherwise use the placeholder or existing preview
      if (profilePicInput.files.length > 0) {
          const reader = new FileReader();
          reader.onload = function(e) {
              profilePicDataUrl = e.target.result;
              saveUserDataAndRedirect();
          };
          reader.readAsDataURL(profilePicInput.files[0]);
      } else {
          saveUserDataAndRedirect();
      }

      function saveUserDataAndRedirect() {
          const userData = {
              firstName: firstName,
              lastName: lastName,
              email: email,
              profilePic: profilePicDataUrl // Save the base64 image data
          };
          localStorage.setItem('userData', JSON.stringify(userData));
          alert("Sign up successful! Please log in."); // Changed alert message
          window.location.href = 'login.html'; // Redirect to login.html
      }
    }
  </script>
</body>
</html>