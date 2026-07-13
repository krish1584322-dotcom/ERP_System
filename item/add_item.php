<?php
require_once __DIR__ . '/../config/database.php';

$categories = $conn->query(
    "SELECT category_id, category_name
     FROM item_category
     ORDER BY category_name"
);

$subcategories = $conn->query(
    "SELECT subcategory_id, category_id, subcategory_name
     FROM item_subcategory
     ORDER BY subcategory_name"
);

$data = [
    'item_code' => '',
    'item_name' => '',
    'category_id' => '',
    'subcategory_id' => '',
    'quantity' => '',
    'unit_price' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    $itemCode = $data['item_code'];
    $itemName = $data['item_name'];
    $categoryId = (int) $data['category_id'];
    $subcategoryId = (int) $data['subcategory_id'];
    $quantity = (int) $data['quantity'];
    $unitPrice = (float) $data['unit_price'];

    if (
        strlen($itemCode) < 2 ||
        strlen($itemName) < 2 ||
        $categoryId <= 0 ||
        $subcategoryId <= 0 ||
        $quantity < 0 ||
        $unitPrice < 0
    ) {
        $error = 'Please enter valid item details.';
    } else {
        $check = $conn->prepare(
            "SELECT subcategory_id
             FROM item_subcategory
             WHERE subcategory_id = ? AND category_id = ?"
        );
        $check->bind_param("ii", $subcategoryId, $categoryId);
        $check->execute();

        if ($check->get_result()->num_rows === 0) {
            $error = 'The selected subcategory does not belong to this category.';
        } else {
            $sql = "INSERT INTO item
                    (item_code, item_name, category_id, subcategory_id, quantity, unit_price)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssiiid",
                $itemCode,
                $itemName,
                $categoryId,
                $subcategoryId,
                $quantity,
                $unitPrice
            );

            if ($stmt->execute()) {
                header("Location: view_item.php?message=Item saved successfully");
                exit;
            }

            $error = "Unable to save item. Item code must be unique.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Add Item</h3>
        </div>

        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Item Code</label>
                    <input type="text" name="item_code" class="form-control"
                           value="<?= htmlspecialchars($data['item_code']) ?>"
                           maxlength="50" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="item_name" class="form-control"
                           value="<?= htmlspecialchars($data['item_name']) ?>"
                           maxlength="150" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Category</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="">Select category</option>

                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <option value="<?= $category['category_id'] ?>"
                                <?= $data['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                        <option value="">Select subcategory</option>

                        <?php while ($subcategory = $subcategories->fetch_assoc()): ?>
                            <option value="<?= $subcategory['subcategory_id'] ?>"
                                data-category="<?= $subcategory['category_id'] ?>"
                                <?= $data['subcategory_id'] == $subcategory['subcategory_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($subcategory['subcategory_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control"
                           value="<?= htmlspecialchars($data['quantity']) ?>"
                           min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" class="form-control"
                           value="<?= htmlspecialchars($data['unit_price']) ?>"
                           min="0" step="0.01" required>
                </div>

                <button type="submit" class="btn btn-success">Save Item</button>
                <a href="view_item.php" class="btn btn-secondary">View Items</a>
            </form>
        </div>
    </div>
</div>



</body>
</html>