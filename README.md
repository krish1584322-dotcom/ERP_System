# ERP System

## Overview

The ERP System is a web-based application developed using PHP and MySQL as part of a Full Stack Developer Intern assignment. It allows users to manage customers and items while generating reports. The system provides a simple and user-friendly interface for performing basic ERP operations.

---

## Features

### Customer Management

* Add new customers
* View customer records
* Search customers
* Update customer information
* Delete customers
* Select customer district from a dropdown list

### Item Management

* Add new items
* View item records
* Search items
* Update item information
* Delete items
* Manage item quantity and unit price
* Select item category and subcategory

### Reports

* Invoice Report with date range filter
* Invoice Item Report with date range filter
* Item Report including item name, category, subcategory, quantity, and unit price

---

## Technologies Used

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* Bootstrap 5
* XAMPP

---

## Project Structure

```text
erp_system/
│
├── index.php
├── README.md
├── database.sql
│
├── config/
│   └── database.php
│
├── customer/
├── item/
├── reports/
├── assets/
│   ├── css/
│   └── js/
└── screenshots/
```

---

## Database Tables

* customer
* district
* item
* item_category
* item_subcategory
* invoice
* invoice_item

---

## Assumptions

The following assumptions were made during the development of this project:

* Customer district information is maintained in the `district` table.
* Each customer belongs to one district.
* Every item has a unique item code.
* Each item belongs to one category and one subcategory.
* Every subcategory belongs to a single category.
* Item quantity represents the current available stock.
* Invoice details are stored in the `invoice` table.
* Invoice line items are stored in the `invoice_item` table.
* Unit prices of invoice items are stored separately to preserve historical invoice data.
* The system is intended to run on a local XAMPP environment.

---

## Prerequisites

Before running the project, install the following software:

* XAMPP
* PHP 8.x or later
* MySQL
* A modern web browser (Google Chrome, Microsoft Edge, or Firefox)

---

## Local Setup

### Step 1

Download and install XAMPP.

### Step 2

Open the XAMPP Control Panel and start:

* Apache
* MySQL

### Step 3

Copy the project folder into the XAMPP `htdocs` directory.

```text
C:\xampp\htdocs\erp_system
```

### Step 4

Open phpMyAdmin.

```text
http://localhost/phpmyadmin
```

### Step 5

Create a database named:

```text
erp_system
```

### Step 6

Import the provided `database.sql` file into the newly created database.

### Step 7

Open `config/database.php` and verify the database connection.

```php
<?php

$conn = new mysqli("localhost", "root", "", "erp_system");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>
```

### Step 8

Open the project in your web browser.

```text
http://localhost/erp_system/
```

The ERP System should now be running successfully.

---

## Main Pages

```text
/customer/add_customer.php
/customer/view_customer.php

/item/add_item.php
/item/view_item.php

/reports/invoice_report.php
/reports/invoice_item_report.php
/reports/item_report.php
```

---

## Author

Developed by **M. Ramakrishnan** as a **Full Stack Developer Intern Assignment** using PHP and MySQL.
