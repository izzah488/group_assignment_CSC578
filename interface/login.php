<?php
// Start session to store user data upon successful login
session_start();


require_once '../config.php';
require_once '../dbconnection.php';

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
        $stmt = $dbh->prepare("SELECT userID, pw FROM users WHERE email = :email");

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
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .navbar {
            background: linear-gradient(90deg, #a259ff 0%, #6a11cb 100%);
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
            border-bottom-left-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
        }
        .navbar .active,
        .navbar a.bg-white {
            background: linear-gradient(90deg, #e0b0ff 0%, #f3e8ff 100%);
            color: #6a11cb !important;
        }
        .navbar a {
            transition: background 0.2s, color 0.2s;
            border-radius: 1.5rem;
            padding: 0.5rem 1.5rem;
        }
        .navbar a:hover {
            background: linear-gradient(90deg, #e0b0ff 0%, #f3e8ff 100%);
            color: #6a11cb !important;
        }
        .login-card {
            background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%);
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            border-radius: 2rem;
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
            max-width: 26rem;
            width: 100%;
            margin-top: 7rem;
            transition: transform 0.3s;
        }
        .login-card:hover {
            transform: scale(1.03);
        }
        .login-btn {
            background: linear-gradient(to right, #a259ff, #6a11cb);
            color: white;
            font-weight: 700;
            border-radius: 1.2rem;
            box-shadow: 0 2px 12px 0 rgba(138,43,226,0.13);
            transition: filter 0.2s, transform 0.2s;
            font-size: 1.15rem;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .login-btn:hover {
            filter: brightness(1.08);
            transform: scale(1.04);
        }
        /* Purple theme for focus/hover */
        input:focus {
            outline: none;
            border-color: #a259ff;
            box-shadow: 0 0 0 2px #e0b0ff;
        }
        .back-btn {
            background: #f3e8ff;
            color: #a259ff;
            border: none;
            transition: background 0.2s, color 0.2s;
        }
        .back-btn:hover {
            background: #e0b0ff;
            color: #6a11cb;
        }
        .signup-link {
            color: #a259ff;
            font-weight: 500;
            transition: color 0.2s;
        }
        .signup-link:hover {
            color: #6a11cb;
            text-decoration: underline;
        }
        @media (max-width: 640px) {
            .login-card {
                padding: 1.5rem 1rem 1rem 1rem;
                margin-top: 4rem;
            }
            .navbar .container {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-4">
<<<<<<< HEAD
    <nav class="navbar w-full fixed top-0 left-0 z-10 shadow-lg rounded-b-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-white p-2 rounded-full shadow-md">
                    <img src="https://img.icons8.com/color/48/000000/combo-chart--v2.png" alt="Money Mate Logo" class="w-6 h-6">
                </div>
                <span class="text-white text-2xl font-semibold">Money Mate</span>
            </div>
            <div class="flex space-x-6">
                <a href="home.php" class="text-white text-lg font-medium hover:text-gray-200 transition-colors duration-200">HOME</a>
                <a href="about_us_page2.php" class="text-white text-lg font-medium hover:text-gray-200 transition-colors duration-200">ABOUT US</a>
                <a href="login.php" class="bg-white text-[#a259ff] px-6 py-2 rounded-full font-semibold shadow-md hover:bg-gray-100 transition-colors duration-200">LOGIN</a>
            </div>
        </div>
    </nav>

  <?php include 'navbar.php'; ?>


    <div class="login-card">
        <form method="POST" action="">
            <div class="flex items-center mb-6">
                <button type="button" onclick="window.location.href='home.php'" class="p-2 rounded-full back-btn mr-4">
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