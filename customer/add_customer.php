<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';

$districts = $conn->query(
    "SELECT district_id, district_name FROM district ORDER BY district_name"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $contactNumber = trim($_POST['contact_number'] ?? '');
    $districtId = (int) ($_POST['district_id'] ?? 0);

    $allowedTitles = ['Mr', 'Mrs', 'Miss', 'Dr'];

    if (
        !in_array($title, $allowedTitles, true) ||
        strlen($firstName) < 2 ||
        strlen($lastName) < 2 ||
        !preg_match('/^[0-9+() -]{7,20}$/', $contactNumber) ||
        $districtId <= 0
    ) {
        $error = 'Please enter valid customer details.';
    } else {
        $sql = "INSERT INTO customer
                (title, first_name, last_name, contact_number, district_id)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssssi',
            $title,
            $firstName,
            $lastName,
            $contactNumber,
            $districtId
        );

        if ($stmt->execute()) {
            header('Location: view_customer.php?message=Customer saved successfully');
            exit;
        }

        $error = 'Unable to save customer: ' . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Add Customer</h3>
        </div>

        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <select name="title" class="form-select" required>
                        <option value="">Select title</option>
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Miss">Miss</option>
                        <option value="Dr">Dr</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control"
                           minlength="2" maxlength="50" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control"
                           minlength="2" maxlength="50" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control"
                           pattern="[0-9+() -]{7,20}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">District</label>
                    <select name="district_id" class="form-select" required>
                        <option value="">Select district</option>

                        <?php while ($district = $districts->fetch_assoc()): ?>
                            <option value="<?= $district['district_id'] ?>">
                                <?= htmlspecialchars($district['district_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Save Customer</button>
                <a href="view_customer.php" class="btn btn-secondary">View Customers</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>