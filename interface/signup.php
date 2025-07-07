<?php
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');
    $confirm_password = htmlspecialchars($_POST['confirm_password'] ?? '');
    
    // TODO: Validate and save to database
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // TODO: Check if user already exists, hash password, save to database
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Mate - Sign Up</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%);
            min-height: 100vh;
        }
        .header-gradient {
            background: linear-gradient(90deg, #a259ff 0%, #6a11cb 100%);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Header/Navigation Bar -->
    <header class="header-gradient text-white p-4 shadow-lg flex items-center justify-between rounded-b-lg">
        <div class="flex items-center space-x-3">
            <!-- Logo/Icon placeholder -->
            <div class="bg-white p-2 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-purple-600">
                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 11.25a.75.75 0 0 0-1.5 0v2.25a.75.75 0 0 0 1.5 0v-2.25Zm-3 0a.75.75 0 0 0-1.5 0v2.25a.75.75 0 0 0 1.5 0v-2.25Zm-3 0a.75.75 0 0 0-1.5 0v2.25a.75.75 0 0 0 1.5 0v-2.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            <span class="text-xl font-semibold">Money Mate</span>
        </div>
        <nav class="hidden md:flex space-x-6">
            <a href="login.php" class="hover:text-gray-200 transition-colors duration-200">HOME</a>
            <a href="about_us_page2.php" class="hover:text-gray-200 transition-colors duration-200">ABOUT US</a>
            <a href="login.php" class="hover:text-gray-200 transition-colors duration-200">LOGIN</a>
        </nav>
    </header>

    <!-- Main Content Area - Centered Sign-Up Card -->
    <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg w-full max-w-md">
            <!-- Back Arrow -->
            <div class="mb-4">
                <button onclick="window.location.href='login.php'" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-700">
                        <path fill-rule="evenodd" d="M11.03 4.53a.75.75 0 0 0-1.06 0L3.47 10.03a.75.75 0 0 0 0 1.06l6.5 6.5a.75.75 0 1 0 1.06-1.06L5.81 11.5H20.25a.75.75 0 0 0 0-1.5H5.81l5.22-5.22a.75.75 0 0 0 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign-Up</h2>
            <p class="text-gray-600 mb-6">Have an account? <a href="login.php" class="text-blue-600 hover:underline">Login Here!</a></p>

            <?php if ($success): ?>
                <div style="background:#d1fae5;color:#065f46;border-radius:0.7rem;padding:1rem;text-align:center;margin-bottom:1rem;">
                    Account created successfully! You can now login.
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div style="background:#fee2e2;color:#991b1b;border-radius:0.7rem;padding:1rem;text-align:center;margin-bottom:1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Sign-Up Form -->
            <form method="POST" action="" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email" id="email" name="email" placeholder="E-mail Address" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 rounded-lg font-semibold shadow-md
                               hover:from-blue-600 hover:to-blue-800 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Sign-Up to Money Tracker
                </button>
            </form>
        </div>
    </main>
</body>
</html>
