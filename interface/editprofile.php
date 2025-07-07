<?php
$success = false;
// TODO: Fetch current user data from database
$currentUser = [
    'first_name' => 'Rebecca',
    'last_name' => 'Louis',
    'email' => 'rebecca@gmail.com',
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = htmlspecialchars($_POST['first_name'] ?? '');
    $last_name = htmlspecialchars($_POST['last_name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $current_password = htmlspecialchars($_POST['current_password'] ?? '');
    $new_password = htmlspecialchars($_POST['new_password'] ?? '');
    // TODO: Update user in the database
    $success = true;
    $currentUser['first_name'] = $first_name;
    $currentUser['last_name'] = $last_name;
    $currentUser['email'] = $email;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    .logout-link {
      background: linear-gradient(90deg, #fbd38d 0%, #f6ad55 100%);
      color: #c05621;
      font-weight: 600;
      padding: 0.8rem 1.5rem;
      border-radius: 0.9rem;
      width: 100%;
      transition: filter 0.2s;
      box-shadow: 0 2px 8px 0 rgba(251,211,141,0.10);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1rem;
    }
    .logout-link:hover {
      filter: brightness(1.08);
    }
    .main-content {
      margin-left: 17rem;
      padding: 3.5rem 2rem 2rem 2rem;
      flex: 1;
    }
    .profile-form {
      background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%);
      padding: 2.5rem 2.5rem 2rem 2.5rem;
      border-radius: 2rem;
      box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
      max-width: 32rem;
      margin: 0 auto;
    }
    .profile-form label {
      display: block;
      margin-bottom: 1rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: #4a5568;
    }
    .profile-form input {
      width: 100%;
      padding: 0.75rem;
      margin-top: 0.25rem;
      border: 1px solid #e2e8f0;
      border-radius: 0.5rem;
      background-color: #f8fafc;
    }
    .profile-form input:focus {
      border-color: #a78bfa;
      outline: none;
      box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.3);
    }
    .form-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
      margin-top: 1.5rem;
    }
    .cancel-btn {
      background-color: #fbd38d;
      color: #c05621;
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 600;
    }
    .cancel-btn:hover {
      background-color: #f6ad55;
    }
    .save-btn {
      background: linear-gradient(to right, #8e2de2, #4a00e0);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 600;
    }
    .save-btn:hover {
      filter: brightness(1.1);
    }
    .modal-overlay {
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1000;
    }
    .modal-content {
      background-color: white;
      padding: 2rem;
      border-radius: 0.75rem;
      text-align: center;
      max-width: 20rem;
      width: 100%;
    }
    .modal-icon {
      color: #10b981;
      font-size: 3rem;
      margin-bottom: 1rem;
    }
    .modal-button {
      background-color: #e5e7eb;
      color: #374151;
      padding: 0.5rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 500;
    }
    .modal-button:hover {
      background-color: #d1d5db;
    }
  </style>
</head>
<body class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <!-- User Info -->
      <div class="flex items-center mb-8">
        <img src="https://placehold.co/40x40/FF69B4/FFFFFF?text=R" alt="Profile Pic" class="w-10 h-10 rounded-full mr-3">
        <div>
          <p class="text-sm font-medium text-gray-700">Hi, <?php echo htmlspecialchars($currentUser['first_name']); ?>!</p>
          <p class="text-xs text-gray-500">Premium User</p>
        </div>
      </div>

      <!-- Dashboard Button -->
      <button onclick="window.location.href='dashboard.php'" class="menu-btn w-full mb-4 bg-gradient-to-r from-purple-500 to-purple-700 text-white rounded-lg py-2 font-semibold flex items-center justify-center gap-2">
        ☰ Dashboard
      </button>

      <!-- Navigation -->
      <nav class="nav-links">
        <a href="savings.php" class="nav-link">⭐ Savings</a>
        <a href="editprofile.php" class="nav-link active">👤 Profile</a>
        <a href="statistic.php" class="nav-link">📈 Statistics</a>
        <a href="budget.php" class="nav-link">⬇ Budget</a>
        <a href="expenses.php" class="nav-link">⬆ Expenses</a>
      </nav>
    </div>

    <!-- Logout button at bottom -->
    <div class="mt-6">
      <button onclick="window.location.href='index.php'" class="logout-link">⏻ Log Out</button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Profile</h1>
    <p class="text-gray-600 mb-8">View and edit your profile, change settings, and manage your data.</p>

    <?php if ($success): ?>
      <div style="background:#d1fae5;color:#065f46;border-radius:0.7rem;padding:1rem;text-align:center;margin-bottom:1rem;">
        Profile updated successfully!
      </div>
    <?php endif; ?>
    <form method="POST" action="" style="background:#f9f9ff; border-radius:1.5rem; padding:2rem; box-shadow:0 4px 16px 0 rgba(138,43,226,0.08); max-width:400px; margin:2rem auto;">
      <input type="text" name="first_name" placeholder="First Name" required value="<?php echo htmlspecialchars($currentUser['first_name']); ?>" style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
      <input type="text" name="last_name" placeholder="Last Name" required value="<?php echo htmlspecialchars($currentUser['last_name']); ?>" style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
      <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($currentUser['email']); ?>" style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
      <input type="password" name="current_password" placeholder="Current Password" required style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
      <input type="password" name="new_password" placeholder="New Password" style="width:100%;padding:0.75rem;margin-bottom:1rem;border-radius:0.7rem;border:1px solid #e0b0ff;">
      <div class="form-buttons">
        <button type="button" class="cancel-btn" onclick="window.history.back()">Cancel</button>
        <button type="submit" class="save-btn">Save</button>
      </div>
    </form>
  </main>

  <!-- Success Modal -->
  <div id="successModal" class="modal-overlay hidden">
    <div class="modal-content">
      <div class="flex justify-center mb-4">
        <i class="fas fa-check-circle modal-icon"></i>
      </div>
      <p class="text-xl font-semibold text-gray-800 mb-6">Saved successfully!</p>
      <button onclick="hideSuccessModalAndRedirect()" class="modal-button">Done</button>
    </div>
  </div>

  <script>
    function showSuccessModal() {
      document.getElementById('successModal').classList.remove('hidden');
    }

    function hideSuccessModalAndRedirect() {
      document.getElementById('successModal').classList.add('hidden');
      window.location.href = 'profile.html';
    }
  </script>
</body>
</html>
