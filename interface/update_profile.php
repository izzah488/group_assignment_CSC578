<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

require_once '../dbconnection.php'; // Include your database connection file

$userID = $_SESSION['userID'];
$redirect_url = 'editprofile.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['fName'] ?? '');
    $lastName = trim($_POST['lName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    $errors = [];

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($currentPassword)) {
        $errors[] = "All required fields must be filled.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Fetch current user data for password verification and existing profile pic
    $currentDbData = null;
    try {
        $stmt = $pdo->prepare("SELECT pw, proPic FROM users WHERE userID = :userID");
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $currentDbData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error fetching user data for update: " . $e->getMessage());
        $errors[] = "An internal error occurred. Please try again.";
    }

    if (!$currentDbData) {
        $errors[] = "User not found or session invalid.";
    } else {
        // Verify current password
        if (!password_verify($currentPassword, $currentDbData['pw'])) {
            $errors[] = "Current password is incorrect.";
        }
    }

    // Handle new password
    $hashedNewPassword = null;
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 6) {
            $errors[] = "New password must be at least 6 characters long.";
        } else {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    }

    // Handle profile picture upload
    $profilePicPath = $currentDbData['proPic'] ?? null; // Keep existing path by default
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profileImage'];
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetDir = '../uploads/profile_pics/'; // Create this directory if it doesn't exist
        $targetFilePath = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
            $errors[] = "File is not an image.";
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for profile picture.";
        }

        // Check file size (e.g., 5MB limit)
        if ($file["size"] > 5 * 1024 * 1024) {
            $errors[] = "Sorry, your file is too large. Max 5MB.";
        }

        // Check if $errors is empty before moving the file
        if (empty($errors)) {
            // Create target directory if it doesn't exist
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                $profilePicPath = $targetFilePath;
            } else {
                $errors[] = "Sorry, there was an error uploading your profile picture.";
            }
        }
    }

    if (!empty($errors)) {
        // Redirect back with error messages
        $queryString = http_build_query(['status' => 'error', 'message' => urlencode(implode(' ', $errors))]);
        header("Location: $redirect_url?$queryString");
        exit();
    }

    // Prepare update query
    $updateFields = [
        'fName' => $firstName,
        'lName' => $lastName,
        'email' => $email,
        'proPic' => $profilePicPath
    ];
    $setClauses = [];
    $bindParams = [':userID' => $userID];

    foreach ($updateFields as $field => $value) {
        $setClauses[] = "$field = :$field";
        $bindParams[":$field"] = $value;
    }

    if ($hashedNewPassword !== null) {
        $setClauses[] = "pw = :pw";
        $bindParams[':pw'] = $hashedNewPassword;
    }

    $sql = "UPDATE users SET " . implode(', ', $setClauses) . " WHERE userID = :userID";

    try {
        $stmt = $pdo->prepare($sql);
        foreach ($bindParams as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();

        // Redirect with success
        header("Location: $redirect_url?status=success");
        exit();

    } catch (PDOException $e) {
        // Check for duplicate email error (specific to your database setup)
        if ($e->getCode() == 23000) { // SQLSTATE for Integrity Constraint Violation
            $error_message = "This email is already registered.";
        } else {
            error_log("Database error during profile update: " . $e->getMessage());
            $error_message = "An error occurred while updating your profile. Please try again.";
        }
        $queryString = http_build_query(['status' => 'error', 'message' => urlencode($error_message)]);
        header("Location: $redirect_url?$queryString");
        exit();
    }

} else {
    // Not a POST request, redirect to edit profile page
    header("Location: $redirect_url");
    exit();
}
?>