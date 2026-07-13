<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/erp_system/assets/css/style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1>ERP System</h1>
        <p class="text-muted">Customer, Item, and Report Management</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4>Customer Management</h4>
                    <p>Add, view, search, edit, and delete customer records.</p>
                    <a href="customer/add_customer.php" class="btn btn-primary">Add Customer</a>
                    <a href="customer/view_customer.php" class="btn btn-outline-primary">View Customers</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4>Item Management</h4>
                    <p>Maintain item, category, subcategory, stock, and price details.</p>
                    <a href="item/add_item.php" class="btn btn-success">Add Item</a>
                    <a href="item/view_item.php" class="btn btn-outline-success">View Items</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4>Reports</h4>
                    <p>View invoice summaries, invoice items, and stock reports.</p>
                    <a href="reports/invoice_report.php" class="btn btn-dark">Invoice Report</a>
                    <a href="reports/item_report.php" class="btn btn-outline-dark mt-2">Item Report</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/erp_system/assets/js/app.js"></script>
</body>
</html>