# Stock Management System

A web-based **Stock Management System** built using **PHP** and **MySQL**.  
This project is designed to help organizations and warehouses manage products, monitor stock levels, record purchases and sales, and generate useful inventory reports.

---

## Project Objective

The objective of this project is to design and implement a database system that manages and monitors inventory within an organization or warehouse.  
The system tracks stock quantities, product movements, sales transactions, purchases, suppliers, warehouses, and historical stock operations to ensure accurate inventory management.

---

## Features

- Manage products
- Manage categories
- Manage suppliers
- Manage warehouses
- Record purchases
- Record sales
- Automatically update stock quantities using database triggers
- Prevent selling more than available stock
- Store stock transaction history
- Generate stock, sales, and purchase reports
- Dashboard with summary statistics

---

## Technologies Used

- **PHP**
- **MySQL**
- **HTML**
- **CSS**
- **XAMPP** (for local development)

---

## Database Tables

This project contains the following main tables:

1. **users**
2. **suppliers**
3. **warehouses**
4. **categories**
5. **products**
6. **purchases**
7. **sales**
8. **stock_transactions**

---

## Database Features

- Primary Keys
- Foreign Keys
- Constraints
- Triggers
- Views
- Relational Schema
- Reports using SQL queries

---

## SQL Concepts Used

The project demonstrates the use of:

- `CREATE TABLE`
- `INSERT`
- `UPDATE`
- `DELETE`
- `SELECT`
- `JOIN`
- `GROUP BY`
- `ORDER BY`
- `COUNT`
- `SUM`
- `AVG`
- `TRIGGERS`
- `VIEWS`

---

## System Modules

The web interface includes the following pages:

- **Dashboard**
- **Products**
- **Add Product**
- **Edit Product**
- **Categories**
- **Suppliers**
- **Warehouses**
- **Purchase**
- **Sale**
- **Transactions**
- **Reports**

---

## Reports Included

- Stock Report
- Sales Report
- Purchase Report
- Low Stock Products
- Top Selling Products
- Transaction History
- Total Stock Value
- Supplier Purchase Summary

---

## Project Structure

```bash
stock-management-ui/
│
├── db.php
├── db.example.php
├── header.php
├── footer.php
├── index.php
├── products.php
├── add_product.php
├── edit_product.php
├── delete_product.php
├── suppliers.php
├── categories.php
├── warehouses.php
├── purchase.php
├── sale.php
├── transactions.php
├── reports.php
├── style.css
├── stock_management_system.sql
└── README.md