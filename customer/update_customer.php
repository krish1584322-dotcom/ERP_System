<?php
require_once __DIR__ . '/../config/database.php';

$id = (int) ($_POST['customer_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$contactNumber = trim($_POST['contact_number'] ?? '');
$districtId = (int) ($_POST['district_id'] ?? 0);

$sql = "UPDATE customer
        SET title = ?, first_name = ?, last_name = ?,
            contact_number = ?, district_id = ?
        WHERE customer_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssii",
    $title,
    $firstName,
    $lastName,
    $contactNumber,
    $districtId,
    $id
);

if ($stmt->execute()) {
    header("Location: view_customer.php?message=Customer updated successfully");
} else {
    header("Location: view_customer.php?error=Unable to update customer");
}

exit;