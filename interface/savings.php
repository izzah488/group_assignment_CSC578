<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../dbconnection.php'; // This will make $conn (which is $pdo) available

$userID = $_SESSION['userID'];

// --- Handle DELETE request ---
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['savingID'])) {
    $savingID = $_POST['savingID'];
    try {
        $stmt = $pdo->prepare("DELETE FROM savingGoals WHERE savingID = :savingID AND userID = :userID");
        $stmt->bindParam(':savingID', $savingID, PDO::PARAM_INT);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete saving goal.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// --- Handle UPDATE request ---
if (isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['savingID'])) {
    $savingID = $_POST['savingID'];
    $savTitle = $_POST['savTitle'];
    $savAmount = $_POST['savAmount'];
    $currentSavings = $_POST['currentSavings'];
    $targetDate = $_POST['targetDate'];

    try {
        $stmt = $pdo->prepare("UPDATE savingGoals SET savTitle = :savTitle, savAmount = :savAmount, curSavings = :curSavings, targetDate = :targetDate WHERE savingID = :savingID AND userID = :userID");
        $stmt->bindParam(':savTitle', $savTitle, PDO::PARAM_STR);
        $stmt->bindParam(':savAmount', $savAmount, PDO::PARAM_STR);
        $stmt->bindParam(':curSavings', $currentSavings, PDO::PARAM_STR);
        $stmt->bindParam(':targetDate', $targetDate, PDO::PARAM_STR);
        $stmt->bindParam(':savingID', $savingID, PDO::PARAM_INT);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update saving goal.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// --- Handle ADD CURRENT SAVINGS request ---
if (isset($_POST['action']) && $_POST['action'] === 'addCurrentSavings' && isset($_POST['savingID']) && isset($_POST['amountToAdd'])) {
    $savingID = $_POST['savingID'];
    $amountToAdd = $_POST['amountToAdd'];

    try {
        $stmt = $pdo->prepare("UPDATE savingGoals SET curSavings = curSavings + :amountToAdd WHERE savingID = :savingID AND userID = :userID");
        $stmt->bindParam(':amountToAdd', $amountToAdd, PDO::PARAM_STR);
        $stmt->bindParam(':savingID', $savingID, PDO::PARAM_INT);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add savings.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// --- Fetch saving goals for the current user ---
$savingsGoals = [];
try {
    $stmt = $pdo->prepare("SELECT savingID, savTitle, savAmount, targetDate, curSavings FROM savingGoals WHERE userID = :userID ORDER BY targetDate ASC");
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $savingsGoals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching saving goals: " . $e->getMessage());
    // Optionally, send an empty array or an error message to the client
    $savingsGoals = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Savings</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f0f2f5;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 16rem;
      height: 100vh;
      background-color: #ffffff;
      border-top-right-radius: 1.5rem;
      border-bottom-right-radius: 1.5rem;
      z-index: 10;
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                  0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .menu-btn {
      background: linear-gradient(to right, #8e2de2, #4a00e0);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .nav-links a {
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      color: #4a00e0;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      width: 100%;
    }
    .main-content {
      margin-left: 14rem; /* This places content immediately next to the 16rem sidebar */
      flex: 1;
      padding: 2rem;
    }
  </style>
</head>
<body class="flex min-h-screen">
  <?php require_once  'sidebar.php'; ?>

  <main class="main-content w-full">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Savings</h1>
        <p class="text-gray-600">Track your savings goals.</p>
      </div>
      <div class="flex items-center space-x-4 mt-4 md:mt-0">
        <span class="text-gray-700 font-medium">March 2025</span>
        <i class="fas fa-calendar-alt text-xl text-gray-500"></i>
      </div>
    </header>

    <section id="savingsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"></section>

    <section class="flex justify-center mt-8">
      <a href="new_saving.php" class="w-full max-w-md py-4 px-4 rounded-xl shadow-lg text-white font-semibold text-lg flex items-center justify-center gap-2 new-saving-btn" style="background-image: linear-gradient(to right, #8e2de2, #4a00e0);">
        <i class="fas fa-plus-circle"></i>
        <span>New Savings</span>
      </a>
    </section>
  </main>

  <div id="newSavingModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-xl w-full relative">
      <button onclick="toggleNewSavingModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add Saving Goal</h2>
      <div class="space-y-6">
        <input type="text" id="savingFor" placeholder="e.g., New Laptop" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
        <input type="number" id="budgetAmount" placeholder="Target Amount (e.g., 5000.00)" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
        <input type="number" id="currentSavingsInitial" placeholder="Current Savings (optional, e.g., 1000.00)" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
        <input type="date" id="targetDate" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
      </div>
      <div class="mt-8 flex justify-between space-x-4">
        <button onclick="toggleNewSavingModal()" class="flex-1 py-3 px-4 rounded-xl bg-red-400 text-white">CANCEL</button>
        <button onclick="saveSaving()" class="flex-1 py-3 px-4 rounded-xl bg-green-600 text-white">SAVE</button>
      </div>
    </div>
  </div>

  <div id="editSavingModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-xl w-full relative">
      <button onclick="toggleEditSavingModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Saving Goal</h2>
      <div class="space-y-6">
        <input type="hidden" id="editSavingID">
        <input type="text" id="editSavingFor" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
        <input type="number" id="editBudgetAmount" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
        <input type="number" id="editCurrentSavings" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
        <input type="date" id="editTargetDate" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
      </div>
      <div class="mt-8 flex justify-between space-x-4">
        <button onclick="toggleEditSavingModal()" class="flex-1 py-3 px-4 rounded-xl bg-red-400 text-white">CANCEL</button>
        <button onclick="updateSaving()" class="flex-1 py-3 px-4 rounded-xl bg-green-600 text-white">UPDATE</button>
      </div>
    </div>
  </div>

  <div id="addCurrentSavingsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-sm w-full relative">
      <button onclick="toggleAddCurrentSavingsModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add Savings to Goal</h2>
      <p class="text-center text-gray-600 mb-4" id="addSavingsGoalTitle"></p>
      <div class="space-y-4">
        <input type="hidden" id="addSavingsSavingID">
        <input type="number" id="amountToAdd" placeholder="Amount to add (e.g., 50.00)" class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50">
      </div>
      <div class="mt-8 flex justify-between space-x-4">
        <button onclick="toggleAddCurrentSavingsModal()" class="flex-1 py-3 px-4 rounded-xl bg-red-400 text-white">CANCEL</button>
        <button onclick="addSavingsToGoal()" class="flex-1 py-3 px-4 rounded-xl bg-green-600 text-white">ADD</button>
      </div>
    </div>
  </div>

  <script>
    // PHP data passed to JavaScript
    const savings = <?php echo json_encode($savingsGoals); ?>;

    let editSavingID = null; // Used for editing a goal
    let addSavingsSavingID = null; // Used for adding current savings to a specific goal

    function toggleNewSavingModal() {
      // This modal is no longer used for adding new savings;
      // instead, the "New Savings" button now links to new_saving.php
      // Keeping the function for consistency if other parts of the UI trigger it.
      document.getElementById('newSavingModal').classList.toggle('hidden');
    }

    function toggleEditSavingModal() {
      document.getElementById('editSavingModal').classList.toggle('hidden');
    }

    function toggleAddCurrentSavingsModal() {
      document.getElementById('addCurrentSavingsModal').classList.toggle('hidden');
      if (!document.getElementById('addCurrentSavingsModal').classList.contains('hidden')) {
        document.getElementById("amountToAdd").value = ""; // Clear amount when opening
      }
    }

    function renderSavings() {
      const container = document.getElementById("savingsList");
      container.innerHTML = "";

      if (savings.length === 0) {
        container.innerHTML = `<div class="col-span-full text-center text-gray-500 italic py-10">No savings goals added yet.</div>`;
        return;
      }

      savings.forEach((item, index) => {
        const progress = (parseFloat(item.curSavings) / parseFloat(item.savAmount)) * 100;
        const progressBarWidth = Math.min(progress, 100); // Cap at 100%

        const card = document.createElement("div");
        card.className = "bg-white p-4 rounded-lg shadow";
        card.innerHTML = `
          <h3 class="text-xl font-semibold text-purple-700">${item.savTitle}</h3>
          <p class="text-gray-700 mt-1">Target: <strong>RM ${parseFloat(item.savAmount).toFixed(2)}</strong></p>
          <p class="text-700">Current: <strong>RM ${parseFloat(item.curSavings).toFixed(2)}</strong></p>
          <p class="text-gray-600 text-sm mb-4">Target Date: ${item.targetDate}</p>
          <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
            <div class="bg-purple-600 h-2.5 rounded-full" style="width: ${progressBarWidth}%"></div>
          </div>
          <p class="text-sm text-gray-600 mb-4">${progressBarWidth.toFixed(1)}% complete</p>
          <div class="flex flex-wrap gap-2">
            <button onclick="editSaving(${item.savingID})" class="bg-yellow-400 text-white px-3 py-1 rounded">Edit</button>
            <button onclick="deleteSaving(${item.savingID})" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
            <button onclick="openAddCurrentSavingsModal(${item.savingID}, '${item.savTitle}')" class="bg-blue-500 text-white px-3 py-1 rounded">Add Savings</button>
          </div>
        `;
        container.appendChild(card);
      });
    }

    async function editSaving(savingID) {
      editSavingID = savingID;
      const item = savings.find(s => s.savingID == savingID); // Find by savingID
      if (item) {
          document.getElementById("editSavingID").value = item.savingID;
          document.getElementById("editSavingFor").value = item.savTitle;
          document.getElementById("editBudgetAmount").value = item.savAmount;
          document.getElementById("editCurrentSavings").value = item.curSavings;
          document.getElementById("editTargetDate").value = item.targetDate;
          toggleEditSavingModal();
      }
    }

    async function updateSaving() {
      const savingID = document.getElementById("editSavingID").value;
      const savTitle = document.getElementById("editSavingFor").value.trim();
      const savAmount = parseFloat(document.getElementById("editBudgetAmount").value.trim());
      const currentSavings = parseFloat(document.getElementById("editCurrentSavings").value.trim());
      const targetDate = document.getElementById("editTargetDate").value;

      if (!savTitle || isNaN(savAmount) || savAmount <= 0 || !targetDate) {
        alert("Please fill in all fields correctly (Target Amount must be a positive number).");
        return;
      }
      if (isNaN(currentSavings) || currentSavings < 0) {
        alert("Current Savings must be a non-negative number.");
        return;
      }

      const response = await fetch('savings.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
              action: 'update',
              savingID: savingID,
              savTitle: savTitle,
              savAmount: savAmount,
              currentSavings: currentSavings,
              targetDate: targetDate
          })
      });

      const result = await response.json();
      if (result.status === 'success') {
          toggleEditSavingModal();
          location.reload(); // Reload to reflect changes from database
      } else {
          alert("Error updating saving goal: " + result.message);
      }
    }

    async function deleteSaving(savingID) {
      if (confirm("Are you sure you want to delete this saving goal?")) {
        const response = await fetch('savings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'delete',
                savingID: savingID
            })
        });

        const result = await response.json();
        if (result.status === 'success') {
            location.reload(); // Reload to reflect changes from database
        } else {
            alert("Error deleting saving goal: " + result.message);
        }
      }
    }

    function openAddCurrentSavingsModal(savingID, savTitle) {
      addSavingsSavingID = savingID;
      document.getElementById("addSavingsGoalTitle").textContent = `Goal: ${savTitle}`;
      document.getElementById("addSavingsSavingID").value = savingID; // Set hidden input
      toggleAddCurrentSavingsModal();
    }

    async function addSavingsToGoal() {
      const amountToAdd = parseFloat(document.getElementById("amountToAdd").value.trim());
      const savingID = document.getElementById("addSavingsSavingID").value;

      if (isNaN(amountToAdd) || amountToAdd <= 0) {
        alert("Please enter a valid positive amount to add.");
        return;
      }

      if (savingID) {
        const response = await fetch('savings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'addCurrentSavings',
                savingID: savingID,
                amountToAdd: amountToAdd
            })
        });

        const result = await response.json();
        if (result.status === 'success') {
            toggleAddCurrentSavingsModal();
            location.reload(); // Reload to reflect changes from database
        } else {
            alert("Error adding savings: " + result.message);
        }
      } else {
        alert("Error: Could not find saving goal ID.");
      }
    }

    window.onload = () => {
      renderSavings();
      // No need for updateSavingsCount() as data is fetched live from DB
    };
  </script>
</body>
</html>