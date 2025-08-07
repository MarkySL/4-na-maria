// transactionHistory.js

class TransactionHistory {
    constructor() {
        this.transactions = [];
    }

    // Add a new transaction
    addTransaction(transaction) {
        const { id, date, amount, description, status } = transaction;
        if (!id || !date || !amount || !description || !status) {
            throw new Error("All transaction fields are required.");
        }
        this.transactions.push(transaction);
    }

    // Get all transactions
    getAllTransactions() {
        return this.transactions;
    }

    // Filter transactions by status
    filterByStatus(status) {
        return this.transactions.filter(transaction => transaction.status === status);
    }

    // Find a transaction by ID
    findTransactionById(id) {
        return this.transactions.find(transaction => transaction.id === id);
    }

    // Remove a transaction by ID
    removeTransactionById(id) {
        this.transactions = this.transactions.filter(transaction => transaction.id !== id);
    }
}

// Example usage
const history = new TransactionHistory();

history.addTransaction({
    id: 1,
    date: "2023-10-01",
    amount: 25.0,
    description: "Laundry service - Wash & Fold",
    status: "Completed"
});

history.addTransaction({
    id: 2,
    date: "2023-10-02",
    amount: 15.0,
    description: "Dry Cleaning",
    status: "Pending"
});

console.log("All Transactions:", history.getAllTransactions());
console.log("Completed Transactions:", history.filterByStatus("Completed"));
console.log("Find Transaction by ID:", history.findTransactionById(1));

history.removeTransactionById(1);
console.log("After Removal:", history.getAllTransactions());