<?php
// TODO: Fetch user data from database
$user = [
    'first_name' => 'Rebecca',
    'last_name' => 'Louis',
    'email' => 'rebecca@gmail.com',
    'type' => 'Premium User',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }
    .sidebar {
      background: linear-gradient(135deg, #fff 60%, #e0b0ff 100%);
      width: 17rem;
      padding: 2rem 1.5rem 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-top-right-radius: 2rem;
      border-bottom-right-radius: 2rem;
      box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 10;
    }
    .nav-links {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    .nav-links a {
      padding: 0.7rem 1.3rem;
      border-radius: 0.8rem;
      color: #4a00e0;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: background 0.2s, color 0.2s;
      font-size: 1.05rem;
      letter-spacing: 0.01em;
      width: 100%;
      text-align: left;
    }
    .nav-links a.active,
    .nav-links a:hover {
      background: linear-gradient(90deg, #e0b0ff 0%, #f3e8ff 100%);
      color: #4a00e0;
    }
    .main-content {
      margin-left: 17rem;
      padding: 3.5rem 2rem 2rem 2rem;
    }
    .profile-card {
      background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%);
      padding: 2.5rem 2.5rem 2rem 2.5rem;
      border-radius: 2rem;
      box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
      max-width: 32rem;
      margin: 0 auto;
      text-align: left;
    }
    .profile-field {
      padding: 0.75rem 0;
      border-bottom: 1px solid #e2e8f0;
      margin-bottom: 1rem;
    }
    .profile-field:last-of-type {
      border-bottom: none;
      margin-bottom: 0;
    }
    .profile-field label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: #4a5568;
      margin-bottom: 0.25rem;
    }
    .profile-field input {
      width: 100%;
      padding: 0.5rem 0;
      border: none;
      font-size: 1rem;
      color: #1a202c;
      font-weight: 600;
      background-color: transparent;
      outline: none;
    }
    .edit-profile-btn {
      background: linear-gradient(to right, #e0c3fc, #8ec5fc);
      color: #4B0082;
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 600;
      transition: background-color 0.2s ease-in-out;
      display: block;
      width: fit-content;
      margin: 2rem auto 0;
    }
    .edit-profile-btn:hover {
      filter: brightness(1.05);
    }
  </style>
</head>
<body>
  
  <?include 'sidebar.php'; ?>

  <!-- Main Content -->
  <main class="main-content">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Profile</h1>
    <p class="text-gray-600 mb-8">View your profile information.</p>

    <div class="profile-card">
      <div class="profile-field">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly />
      </div>
      <div class="profile-field">
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly />
      </div>
      <div class="profile-field">
        <label for="email">Email</label>
        <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly />
      </div>
      <div class="profile-field flex justify-between items-center">
        <div class="flex-grow">
          <label for="password">Current Password</label>
          <input type="password" id="password" value="********" readonly />
        </div>
        <i class="fas fa-eye-slash text-gray-500 cursor-pointer ml-4"></i>
      </div>

      <button onclick="window.location.href='editprofile.php'" class="edit-profile-btn">Edit Profile</button>
    </div>
  </main>
</body>
</html>
