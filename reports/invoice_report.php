<?php
require_once __DIR__ . '/../config/database.php';

$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

$sql = "SELECT
            inv.invoice_number,
            inv.invoice_date,
            CONCAT(c.title, ' ', c.first_name, ' ', c.last_name) AS customer_name,
            d.district_name,
            COUNT(ii.invoice_item_id) AS item_count,
            SUM(ii.quantity * ii.unit_price) AS invoice_amount
        FROM invoice inv
        INNER JOIN customer c ON inv.customer_id = c.customer_id
        INNER JOIN district d ON c.district_id = d.district_id
        INNER JOIN invoice_item ii ON inv.invoice_id = ii.invoice_id";

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

$sql .= " GROUP BY
            inv.invoice_id,
            inv.invoice_number,
            inv.invoice_date,
            c.title,
            c.first_name,
            c.last_name,
            d.district_name
          ORDER BY inv.invoice_date DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $invoices = $stmt->get_result();
} else {
    $invoices = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-3">Invoice Report</h3>

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
                    <a href="invoice_report.php" class="btn btn-secondary">Clear</a>
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
                        <th>Customer</th>
                        <th>Customer District</th>
                        <th>Item Count</th>
                        <th>Invoice Amount</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($invoice = $invoices->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                            <td><?= htmlspecialchars($invoice['invoice_date']) ?></td>
                            <td><?= htmlspecialchars($invoice['customer_name']) ?></td>
                            <td><?= htmlspecialchars($invoice['district_name']) ?></td>
                            <td><?= $invoice['item_count'] ?></td>
                            <td><?= number_format($invoice['invoice_amount'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>