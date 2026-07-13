<?php
require_once __DIR__ . '/../config/database.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM item WHERE item_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    exit("Item not found.");
}

$categories = $conn->query(
    "SELECT category_id, category_name FROM item_category ORDER BY category_name"
);

$subcategories = $conn->query(
    "SELECT subcategory_id, category_id, subcategory_name
     FROM item_subcategory
     ORDER BY subcategory_name"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h3 class="mb-0">Edit Item</h3>
        </div>

        <div class="card-body">
            <form action="update_item.php" method="POST">
                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Item Code</label>
                    <input type="text" name="item_code" class="form-control"
                           value="<?= htmlspecialchars($item['item_code']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="item_name" class="form-control"
                           value="<?= htmlspecialchars($item['item_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <option value="<?= $category['category_id'] ?>"
                                <?= $item['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                        <?php while ($subcategory = $subcategories->fetch_assoc()): ?>
                            <option value="<?= $subcategory['subcategory_id'] ?>"
                                data-category="<?= $subcategory['category_id'] ?>"
                                <?= $item['subcategory_id'] == $subcategory['subcategory_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($subcategory['subcategory_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control"
                           value="<?= $item['quantity'] ?>" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" class="form-control"
                           value="<?= $item['unit_price'] ?>" min="0" step="0.01" required>
                </div>

                <button type="submit" class="btn btn-warning">Update Item</button>
                <a href="view_item.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>



</body>
</html>