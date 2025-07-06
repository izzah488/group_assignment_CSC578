const express = require('express');
const router = express.Router();
const { getTransactions, addTransaction } = require('../controllers/transactionController');

// Make sure these are functions
router.get('/', getTransactions);
router.post('/', addTransaction);

module.exports = router;
