<?php
session_start();

// Include your database connection and configuration files
// Assuming dbconnection.php sets up a PDO connection and makes it globally available as $dbh
require_once __DIR__ . '/../config.php'; // Assuming this defines LOG_FILE_PATH if used
require_once __DIR__ . '/../dbconnection.php'; // This should provide the $dbh PDO object

// Access the global PDO database handle
global $dbh;

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
    } elseif (strlen($password) < 6) { // Added minimum password length validation
        $signup_error = 'Password must be at least 6 characters long.';
    } else {
        try {
            // Check if email already exists
            $stmt = $dbh->prepare("SELECT userID FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $signup_error = 'Email is already registered.';
            } else {
                // Handle profile picture upload (optional)
                $profilePicPath = null;
                if (isset($_FILES['proPic']) && $_FILES['proPic']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "staff_images/"; // Assuming 'staff_images' is where user images go
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
                    }
                    $fileName = uniqid() . '_' . basename($_FILES['proPic']['name']);
                    $targetFile = $targetDir . $fileName;
                    
                    // Validate file type (basic example, enhance for production)
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
                    if (!in_array($imageFileType, $allowedTypes)) {
                        $signup_error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for profile pictures.";
                    } elseif ($_FILES['proPic']['size'] > 500000) { // 500KB max size
                        $signup_error = "Sorry, your file is too large (max 500KB).";
                    } else {
                        if (move_uploaded_file($_FILES['proPic']['tmp_name'], $targetFile)) {
                            $profilePicPath = $fileName; // Store just the filename in DB
                        } else {
                            $signup_error = "Sorry, there was an error uploading your file.";
                        }
                    }
                }
                
                // If there were file upload errors, don't proceed with user insertion
                if ($signup_error) {
                    // Error message already set
                } else {
                    // Hash the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Insert user into database
                    $stmt = $dbh->prepare("INSERT INTO users (fName, lName, email, pw, userimage) VALUES (:fName, :lName, :email, :pw, :userimage)");
                    $stmt->bindParam(':fName', $firstName, PDO::PARAM_STR);
                    $stmt->bindParam(':lName', $lastName, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':pw', $hashedPassword, PDO::PARAM_STR);
                    $stmt->bindParam(':userimage', $profilePicPath, PDO::PARAM_STR); // Use userimage column

                    if ($stmt->execute()) {
                        $signup_success = 'Registration successful! You can now <a href="login.php" class="text-indigo-600 hover:underline">login</a>.';
                        // Clear form fields after successful submission
                        $_POST = array();
                        $_FILES = array(); // Clear file data too
                    } else {
                        $signup_error = 'Registration failed. Please try again.';
                    }
                }
            }
        } catch (PDOException $e) {
            // Log the error securely
            error_log("Sign Up DB Error: " . $e->getMessage(), 3, LOG_FILE_PATH);
            $signup_error = 'A database error occurred. Please try again later.';
        }
    }
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
            <?php if ($signup_error): ?>
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-center font-semibold">
                    <?= htmlspecialchars($signup_error) ?>
                </div>
            <?php elseif ($signup_success): ?>
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-center font-semibold">
                    <?= $signup_success ?>
                </div>
            <?php endif; ?>
            <form id="signupForm" method="POST" enctype="multipart/form-data">
                <div class="mb-6 text-center">
                    <label for="proPic" class="cursor-pointer">
                        <img id="previewImage" src="staff_images/default.png" alt="Profile Preview"
                             class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-indigo-300 shadow-md mb-3">
                        <span class="text-indigo-600 hover:text-indigo-800 font-medium">Upload Profile Picture</span>
                    </label>
                    <input type="file" id="proPic" name="proPic" accept="image/*" class="hidden">
                </div>

                <div class="mb-4">
                    <label for="fName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" id="fName" name="fName" placeholder="Enter your first name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_POST['fName'] ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label for="lName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" id="lName" name="lName" placeholder="Enter your last name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_POST['lName'] ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label for="pw" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
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
                // If no file selected, revert to default image (assuming default.png exists in staff_images)
                previewImage.src = "staff_images/default.png";
            }
        });
    </script>
</body>
</html>
