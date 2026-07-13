<?php
require_once __DIR__ . '/../config/database.php';

$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

$sql = "SELECT
            inv.invoice_number,
            inv.invoice_date,
            CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
            i.item_code,
            i.item_name,
            cat.category_name,
            ii.unit_price
        FROM invoice inv
        INNER JOIN customer c ON inv.customer_id = c.customer_id
        INNER JOIN invoice_item ii ON inv.invoice_id = ii.invoice_id
        INNER JOIN item i ON ii.item_id = i.item_id
        INNER JOIN item_category cat ON i.category_id = cat.category_id";

$conditions = [];
$params = [];
$types = '';

if ($fromDate !== '') {
    $conditions[] = "inv.invoice_date >= ?";
    $params[] = $fromDate;
    $types .= 's';
}

if ($toDate !== '') {
    $conditions[] = "inv.invoice_date <= ?";
    $params[] = $toDate;
    $types .= 's';
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY inv.invoice_date DESC, inv.invoice_number";

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $reportRows = $stmt->get_result();
} else {
    $reportRows = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Item Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-3">Invoice Item Report</h3>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control"
                           value="<?= htmlspecialchars($fromDate) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control"
                           value="<?= htmlspecialchars($toDate) ?>">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="invoice_item_report.php" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Invoice Number</th>
                        <th>Invoice Date</th>
                        <th>Customer Name</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Item Category</th>
                        <th>Item Unit Price</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $reportRows->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                            <td><?= htmlspecialchars($row['invoice_date']) ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['item_code']) ?></td>
                            <td><?= htmlspecialchars($row['item_name']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td><?= number_format($row['unit_price'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>