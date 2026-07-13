<?php
require_once __DIR__ . '/../config/database.php';

$search = trim($_GET['search'] ?? '');

$sql = "SELECT
            i.item_id,
            i.item_code,
            i.item_name,
            i.quantity,
            i.unit_price,
            c.category_name,
            s.subcategory_name
        FROM item i
        INNER JOIN item_category c ON i.category_id = c.category_id
        INNER JOIN item_subcategory s ON i.subcategory_id = s.subcategory_id";

if ($search !== '') {
    $sql .= " WHERE i.item_code LIKE ?
              OR i.item_name LIKE ?
              OR c.category_name LIKE ?
              OR s.subcategory_name LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchValue = "%" . $search . "%";
    $stmt->bind_param(
        "ssss",
        $searchValue,
        $searchValue,
        $searchValue,
        $searchValue
    );
    $stmt->execute();
    $items = $stmt->get_result();
} else {
    $sql .= " ORDER BY i.item_id DESC";
    $items = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Item List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h3>Item List</h3>
            <a href="add_item.php" class="btn btn-primary">Add Item</a>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>

        <form method="GET" class="row mb-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Search item code, item name, or category">
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary">Search</button>
                <a href="view_item.php" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($item = $items->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['item_code']) ?></td>
                                <td><?= htmlspecialchars($item['item_name']) ?></td>
                                <td><?= htmlspecialchars($item['category_name']) ?></td>
                                <td><?= htmlspecialchars($item['subcategory_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['unit_price'], 2) ?></td>
                                <td>
                                    <a href="edit_item.php?id=<?= $item['item_id'] ?>"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <a href="delete_item.php?id=<?= $item['item_id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this item?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>