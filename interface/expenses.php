<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Money Expenses</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f0f2f5;
    }
    .container { display: flex; min-height: 100vh; }
    .sidebar { background-color: #fff; width: 16rem; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; border-top-right-radius: 1.5rem; border-bottom-right-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .user-info { display: flex; align-items: center; margin-bottom: 2rem; }
    .avatar { width: 2.5rem; height: 2.5rem; border-radius: 9999px; margin-right: 0.75rem; object-fit: cover; background-color: #cbd5e1; }
    .menu-btn, .logout { padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; width: 100%; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .menu-btn { background: linear-gradient(to right, #8e2de2, #4a00e0); color: white; }
    .logout { background-color: #fbd38d; color: #c05621; }
    .logout:hover { background-color: #f6ad55; }
    .nav-links { display: flex; flex-direction: column; gap: 0.5rem; }
    .nav-links a { padding: 0.75rem 1.5rem; border-radius: 0.75rem; color: #4a00e0; font-weight: 500; display: flex; align-items: center; gap: 0.75rem; transition: 0.2s; }
    .nav-links a.active, .nav-links a:hover { background-color: #e0b0ff; }
    .main-content { flex: 1; padding: 2rem; }
    .form-card { background-color: white; padding: 2rem; border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); max-width: 36rem; margin: 0 auto; position: relative; }
    .back-btn { position: absolute; top: 1.5rem; left: 1.5rem; background-color: #e2e8f0; color: #4a5568; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; }
    .back-btn:hover { background-color: #cbd5e1; }
    .input { width: 100%; padding: 0.75rem; margin-bottom: 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; background-color: #f8fafc; }
    .input:focus { border-color: #a78bfa; box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.3); outline: none; }
    .btn-group { display: flex; justify-content: space-between; gap: 1rem; margin-top: 1.5rem; }
    .btn-group button { flex: 1; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; }
    .btn-group button[type="reset"] { background-color: #fbd38d; color: #c05621; }
    .btn-group button[type="submit"] { background: linear-gradient(to right, #8e2de2, #4a00e0); color: white; }
    .btn-group button:hover { filter: brightness(1.1); }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="user-info">
        <img src="https://placehold.co/40x40/cbd5e1/000000?text=P" alt="User Avatar" class="avatar">
        <p class="text-sm font-medium text-gray-700">Hi, Rebecca!</p>
      </div>
      <button onclick="location.href='dashboard.html'" class="menu-btn">☰ Dashboard</button>
      <nav class="nav-links">
        <a href="dashboard.html">⭐ Savings</a>
        <a href="editprofile.html">👤 Profile</a>
        <a href="dashboard.html">📈 Statistics</a>
        <a href="budget.html">⬇ Budget</a>
        <a href="expenses.html" class="active">⬆ Expenses</a>
      </nav>
      <button onclick="location.href='home.html'" class="logout">⏻ Log Out</button>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Money Expenses</h1>
      <p class="text-gray-600 mb-8">Add new money expenses.</p>

      <div class="form-card">
        <button onclick="location.href='expenses.html'" class="back-btn">←</button>
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add Money Expenses</h2>
        <form id="expenseForm">
          <input type="text" id="title" placeholder="Title" class="input" required />
          <input type="number" id="amount" placeholder="RM" class="input" required />
          <select id="category" class="input" required>
            <option disabled selected value="">Category</option>
            <option>Food</option>
            <option>Transport</option>
            <option>Shopping</option>
            <option>Utilities</option>
            <option>Bill</option>
            <option>Top Up</option>
            <option>Entertainment</option>
          </select>
          <input type="date" id="date" class="input" required />

          <div class="btn-group">
            <button type="reset">CANCEL</button>
            <button type="submit">ADD</button>
          </div>
        </form>
        <p id="message" class="text-center text-green-600 mt-4 font-medium hidden">Expense added successfully!</p>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('expenseForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      
      const title = document.getElementById('title').value;
      const amount = document.getElementById('amount').value;
      const category = document.getElementById('category').value;
      const date = document.getElementById('date').value;

      try {
        const res = await fetch('http://localhost:5000/api/transactions', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ title, amount, category, date })
        });

        const data = await res.json();
        if (res.ok) {
          document.getElementById('message').classList.remove('hidden');
          document.getElementById('expenseForm').reset();
          setTimeout(() => {
            document.getElementById('message').classList.add('hidden');
          }, 3000);
        } else {
          alert(data.message || 'Something went wrong!');
        }
      } catch (err) {
        console.error(err);
        alert('Error connecting to server.');
      }
    });
  </script>
</body>
</html>
