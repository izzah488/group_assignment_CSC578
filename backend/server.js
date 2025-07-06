const express = require('express');
const cors = require('cors');
const app = express();
const PORT = 5000;

app.use(cors());
app.use(express.json());

// Dummy storage (In-memory)
let savings = [];

// Add a new saving
app.post('/api/savings', (req, res) => {
  const { title, amount, date } = req.body;

  if (!title || !amount || !date) {
    return res.status(400).json({ message: "Incomplete data" });
  }

  const newSaving = {
    id: savings.length + 1,
    title,
    amount: parseFloat(amount),
    date
  };

  savings.push(newSaving);
  res.status(201).json({ message: "Saving added successfully", saving: newSaving });
});

// Get all savings
app.get('/api/savings', (req, res) => {
  res.status(200).json(savings);
});

app.listen(PORT, () => console.log(`✅ Server running on http://localhost:${PORT}`));
