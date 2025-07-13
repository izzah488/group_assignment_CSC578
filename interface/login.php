<?php
// Start session to store user data upon successful login
session_start();

// Include your configuration file and database connection file
// Assuming 'login.php' is in a subdirectory (e.g., 'interface/')
// and 'config.php' and 'dbconnection.php' are in the parent directory (project root).
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../dbconnection.php';
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get input from the form
    $email = htmlspecialchars($_POST['email'] ?? '');
    $plain_text_password_from_form = htmlspecialchars($_POST['pw'] ?? ''); // 'pw' because your HTML input name="pw"

    // Basic validation for empty fields
    if (empty($email) || empty($plain_text_password_from_form)) {
        $login_error = 'Please enter both email and password.';
    } else {
        // Prepare a SQL statement using PDO ($dbh)
            $stmt = $pdo->prepare("SELECT userID, email, pw FROM users WHERE email = :email");

        // Check if the statement preparation was successful
        if ($stmt === false) {
            $login_error = 'Database error: Unable to prepare statement.';
            error_log("Database prepare error: " . implode(":", $dbh->errorInfo()));
        } else {
            // Bind the email parameter
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            // Fetch the user data. PDO::FETCH_ASSOC returns an associative array.
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a user with that email exists
            if ($user) {
                $hashed_password_from_db = $user['pw']; // 'pw' is the column name in your DB

                // Verify the provided password (plain_text) against the hashed password from the database
                if (password_verify($plain_text_password_from_form, $hashed_password_from_db)) {
                    // Password is correct, set session variables
                    $_SESSION['userID'] = $user['userID'];
                    // You might want to store other user data in the session, e.g., $_SESSION['email'] = $user['email'];

                    // Redirect to the dashboard
                    header('Location: dashboard.php');
                    exit; // Always exit after a header redirect
                } else {
                    // Password does not match
                    $login_error = 'Invalid email or password.';
                }
            } else {
                // No user found with that email
                $login_error = 'Invalid email or password.';
            }
        }
    }
}

// Close the database connection at the end of the script (optional for simple scripts as PHP does this automatically)
$dbh = null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Mate - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-4">
    <?php include 'header.php'; ?>

    <div class="login-card">
        <form method="POST" action="">
            <div class="flex items-center mb-6">
                <button type="button" onclick="window.location.href='h.php'" class="p-2 rounded-full back-btn mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
                <h2 class="text-3xl font-bold text-gray-800">Login</h2>
            </div>

            <p class="text-gray-600 mb-6">
                Don't have account yet? <a href="signup.php" class="signup-link">Sign-Up here!</a>
            </p>

            <?php if ($login_error): ?>
                <div class="mb-4 text-red-600 text-center font-semibold"><?php echo $login_error; ?></div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email address</label>
                <input type="email" id="email" name="email" placeholder="E-mail Address" required class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#a259ff] focus:border-transparent transition-all duration-200">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                <input type="password" id="password" name="pw" placeholder="Password" required class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#a259ff] focus:border-transparent transition-all duration-200">
            </div>

            <button type="submit" class="w-full login-btn">
                Login
            </button>
        </form>
    </div>
</body>
</html>