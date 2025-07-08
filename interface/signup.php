<?php
session_start();
include '../dbconnection.php';

$signup_error = '';
$signup_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $firstName = trim($_POST['fName'] ?? '');
    $lastName = trim($_POST['lName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['pw'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate required fields
    if (!$firstName || !$lastName || !$email || !$password || !$confirmPassword) {
        $signup_error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error = 'Invalid email address.';
    } elseif ($password !== $confirmPassword) {
        $signup_error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT userID FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $signup_error = 'Email is already registered.';
        } else {
            // Handle profile picture upload (optional)
            $profilePicPath = null;
            if (isset($_FILES['proPic']) && $_FILES['proPic']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "uploads/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = uniqid() . '_' . basename($_FILES['proPic']['name']);
                $targetFile = $targetDir . $fileName;
                if (move_uploaded_file($_FILES['proPic']['tmp_name'], $targetFile)) {
                    $profilePicPath = $targetFile;
                }
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (fName, lName, email, pw, proPic) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $profilePicPath);
            if ($stmt->execute()) {
                $signup_success = 'Registration successful! You can now <a href="login.php">login</a>.';
            } else {
                $signup_error = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>
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

  <?php if ($signup_error): ?>
    <div class="mb-4 text-red-600 text-center font-semibold"><?= htmlspecialchars($signup_error) ?></div>
  <?php elseif ($signup_success): ?>
    <div class="mb-4 text-green-600 text-center font-semibold"><?= $signup_success ?></div>
  <?php endif; ?>

  <header class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-4 shadow-lg flex items-center justify-between rounded-b-lg">
    <div class="flex items-center space-x-3">
      <div class="bg-white p-2 rounded-full">
        <img src="https://img.icons8.com/color/48/000000/combo-chart--v2.png" alt="Logo" class="w-6 h-6">
      </div>
      <span class="text-xl font-semibold">Money Mate</span>
    </div>
    <nav class="hidden md:flex space-x-6">
      <a href="home.php" class="hover:text-gray-200 transition">HOME</a>
      <a href="about_us_page2.php" class="hover:text-gray-200 transition">ABOUT US</a>
      <a href="login.php" class="hover:text-gray-200 transition">LOGIN</a>
    </nav>
  </header>

  <main class="flex-grow flex items-center justify-center p-6">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
      <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Sign Up</h1>
      <form id="signupForm" method="POST" enctype="multipart/form-data">
        <div class="mb-6 text-center">
          <label for="profilePic" class="cursor-pointer">
            <img id="previewImage" src="https://placehold.co/100x100?text=Preview" alt="Profile Preview"
                 class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-indigo-300 shadow-md mb-3">
            <span class="text-indigo-600 hover:text-indigo-800 font-medium">Upload Profile Picture</span>
          </label>
          <input type="file" id="proPic" name="proPic" accept="image/*" class="hidden">
        </div>

        <div class="mb-4">
          <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
          <input type="text" id="fName" name="fName" placeholder="Enter your first name"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
          <input type="text" id="lName" name="lName" placeholder="Enter your last name"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" id="email" name="email" placeholder="you@example.com"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" id="pw" name="pw" placeholder="Minimum 6 characters"
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
    document.getElementById('proPic').addEventListener('change', function (e) {
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
  </script>
</body>
</html>