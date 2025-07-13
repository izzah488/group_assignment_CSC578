CREATE DATABASE IF NOT EXISTS `money_mate_db`;

USE money_mate_db;

CREATE TABLE users (
    userID BIGINT AUTO_INCREMENT PRIMARY KEY,
    fName VARCHAR(255) NOT NULL,
    lName VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    pw VARCHAR(255) NOT NULL, -- Store hashed passwords
    proPic VARCHAR(255),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE budget (
    budgetID BIGINT AUTO_INCREMENT PRIMARY KEY,
    userID BIGINT NOT NULL,
    budgetDate DATE NOT NULL,
    totBudget DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (userID) REFERENCES users(userID),
    UNIQUE (userID, budgetDate) -- A user can only have one budget per month
);

CREATE TABLE expenses (
    expenseID BIGINT AUTO_INCREMENT PRIMARY KEY,
    userID BIGINT NOT NULL,
    expTitle VARCHAR(255) NOT NULL,
    expAmount DECIMAL(10, 2) NOT NULL,
    catLookupID BIGINT NOT NULL, -- New column to store the ID from expenseCategories
    expDate DATE NOT NULL,
    expCreatdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (catLookupID) REFERENCES expCatLookup(catLookupID) -- New Foreign Key constraint
);

CREATE TABLE savingGoals (
    savingID BIGINT AUTO_INCREMENT PRIMARY KEY,
    userID BIGINT NOT NULL,
    savTitle VARCHAR(255) NOT NULL,
    savAmount DECIMAL(10, 2) NOT NULL,
    targetDate DATE NOT NULL,
    curSavings DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE balance (
    balanceID BIGINT AUTO_INCREMENT PRIMARY KEY,
    userID BIGINT NOT NULL,
    currentBal DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (userID) REFERENCES users(userID),
    UNIQUE (userID, date) -- A user can only have one balance entry per day
);

CREATE TABLE expCatLookup (
    catLookupID BIGINT AUTO_INCREMENT PRIMARY KEY,
    catName VARCHAR(255) NOT NULL UNIQUE -- e.g., 'Groceries', 'Utilities', 'Rent'
);

INSERT INTO expCatLookup (catName) VALUES
('Food'),
('Transport'),
('Shopping'),
('Utilities'),
('Bill'),
('Top Up'),
('Entertainment');

DROP TABLE IF EXISTS budget;

SHOW CREATE TABLE EXPENSES;
DESCRIBE USERS;
DESCRIBE expenses;

DROP DATABASE money_mate_db;

CREATE TABLE userlog (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userID INT NULL, -- Link to your users table's userID
    userEmail VARCHAR(255) NOT NULL,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP, -- Automatically set when a new log entry is created
    logout DATETIME NULL -- This will be updated by your logout script
);

ALTER TABLE userlog MODIFY COLUMN id INT NOT NULL AUTO_INCREMENT;
ALTER TABLE userlog MODIFY COLUMN userID INT NULL;


