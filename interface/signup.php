<?php
session_start();
require_once __DIR__ . '/../dbconnection.php';
// --- Initialize variables for form data and errors ---
$errors = [];
$formData = []; // To pre-fill form fields if there are errors

// --- Handle form submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $firstName = htmlspecialchars(trim($_POST['fName'] ?? ''));
    $lastName = htmlspecialchars(trim($_POST['lName'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['pw'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Store submitted data to re-populate form fields in case of errors
    $formData = [
        'fName' => $firstName,
        'lName' => $lastName,
        'email' => $email,
    ];

    // Basic server-side validation
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Handle profile picture upload
    $profilePicPath = null; // Default to null
    if (isset($_FILES['proPic']) && $_FILES['proPic']['error'] == UPLOAD_ERR_OK) { // Corrected name from 'profilePic' to 'proPic'
        $fileTmpPath = $_FILES['proPic']['tmp_name'];
        $fileName = $_FILES['proPic']['name'];
        $fileSize = $_FILES['proPic']['size'];
        $fileType = $_FILES['proPic']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($fileExtension, $allowedfileExtensions)) {
            $errors[] = "Invalid file type for profile picture. Only JPG, JPEG, PNG, GIF allowed.";
        }
        if ($fileSize > $maxFileSize) {
            $errors[] = "Profile picture size exceeds 5MB limit.";
        }

        if (empty($errors)) { // Only proceed if file type and size are valid
            // Generate a unique file name
            $newFileName = uniqid() . '-' . md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = 'uploads/'; // Directory to store uploaded images (relative to this script)

            // Create the directory if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true); // 0777 grants full permissions, adjust for production
            }

            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $profilePicPath = $dest_path;
            } else {
                $errors[] = "There was an error moving the uploaded file. Check server permissions.";
            }
        }
    }

    // If no validation errors, proceed with database insertion
    if (empty($errors)) {
        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Check if email already exists using PDO prepared statement
            $stmt_check_email = $pdo->prepare("SELECT userID FROM users WHERE email = :email");
            $stmt_check_email->execute([':email' => $email]);
            $existingUser = $stmt_check_email->fetch();

            if ($existingUser) {
                $errors[] = "Email already registered. Please use a different email or log in.";
            } else {
                // Insert new user into the database using PDO prepared statement
                // Corrected parameter names to match your form data names (fName, lName, pw, proPic)
                $stmt_insert = $pdo->prepare("INSERT INTO users (fName, lName, email, pw, proPic) VALUES (:fName, :lName, :email, :pw, :proPic)");

                $stmt_insert->execute([
                    ':fName' => $firstName,
                    ':lName' => $lastName,
                    ':email' => $email,
                    ':pw' => $hashedPassword, // Use :pw for password
                    ':proPic' => $profilePicPath // Use :proPic for profile picture
                ]);

                $_SESSION['message'] = "Sign up successful! Please log in.";
                header("Location: login.php"); // Redirect to login.php on success
                exit();
            }
        } catch (\PDOException $e) {
            // Catch database-related errors during query execution
            error_log("Login DB Error: " . $e->getMessage(), 3, LOG_FILE_PATH);
            $login_error = 'An internal error occurred. Please try again later.';
        }
    }

    // If there are errors (either validation or database-related),
    // store them in session to display on the form upon re-render
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $formData; // Store form data to re-populate fields
        // No explicit redirect needed here as form and processing are in the same file.
        // The script will continue to render the HTML below, displaying errors.
    }
}

// --- Retrieve any errors or success messages from the session ---
// These are populated either from a redirect (e.g., if a previous attempt
// failed and redirected back to this page) or from the current POST request handling.
if (isset($_SESSION['errors'])) {
    $errors = array_merge($errors, $_SESSION['errors']); // Merge any existing errors with new ones
    unset($_SESSION['errors']); // Clear errors after displaying
}
if (isset($_SESSION['form_data'])) {
    $formData = array_merge($formData, $_SESSION['form_data']); // Merge any existing form data with new ones
    unset($_SESSION['form_data']); // Clear form data after populating
}
$successMessage = '';
if (isset($_SESSION['message'])) {
    $successMessage = $_SESSION['message'];
    unset($_SESSION['message']); // Clear message after displaying
}

?>


//<body class="flex flex-col min-h-screen bg-gray-100">//
<?php
// Set page title for header
$pageTitle = "Money Mate - Sign Up";
require_once 'header.php';
?>

    <main class="flex-grow flex items-center justify-center p-6 pt-24 pb-12"> <!-- Added pt-24 and pb-12 -->
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Sign Up</h1>

            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMessage)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <form id="signupForm" action="signup.php" method="POST" enctype="multipart/form-data">
                <div class="mb-6 text-center">
                    <label for="profilePic" class="cursor-pointer">
                        <img id="previewImage" src="https://placehold.co/100x100?text=Preview" alt="Profile Preview"
                             class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-indigo-300 shadow-md mb-3">
                        <span class="text-indigo-600 hover:text-indigo-800 font-medium">Upload Profile Picture</span>
                    </label>
                    <input type="file" id="profilePic" name="proPic" accept="image/*" class="hidden">
                </div>

                <div class="mb-4">
                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" id="firstName" name="fName" placeholder="Enter your first name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required
                           value="<?php echo htmlspecialchars($formData['fName'] ?? ''); ?>"> <!-- Corrected formData key -->
                </div>

                <div class="mb-4">
                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" id="lastName" name="lName" placeholder="Enter your last name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required
                           value="<?php echo htmlspecialchars($formData['lName'] ?? ''); ?>"> <!-- Corrected formData key -->
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required
                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="pw" placeholder="Minimum 6 characters"
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
        // Profile picture preview (client-side, remains the same)
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
    </script>
<?php
require_once __DIR__ . '/../includes/footer.php'; // Include footer if you have one
?>