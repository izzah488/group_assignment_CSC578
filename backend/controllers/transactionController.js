const Transaction = require('../models/Transaction');

exports.getAllTransactions = async (req, res) => {
  try {
    const transactions = await Transaction.find().sort({ date: -1 });
    res.json(transactions);
  } catch (error) {
    res.status(500).json({ error: 'Server Error' });
  }
};

exports.createTransaction = async (req, res) => {
  try {
    const { title, amount, type } = req.body;
    const newTransaction = new Transaction({ title, amount, type });
    await newTransaction.save();
    res.status(201).json(newTransaction);
  } catch (error) {
    res.status(500).json({ error: 'Server Error' });
  }
};

exports.getSummary = async (req, res) => {
  try {
    const transactions = await Transaction.find();
    const income = transactions
      .filter(t => t.type === 'income')
      .reduce((acc, t) => acc + t.amount, 0);
    const expense = transactions
      .filter(t => t.type === 'expense')
      .reduce((acc, t) => acc + t.amount, 0);
    const balance = income - expense;
    res.json({ totalIncome: income, totalExpense: expense, balance });
  } catch (error) {
    res.status(500).json({ error: 'Server Error' });
  }
};
